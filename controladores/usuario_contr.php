<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../include/guardian.php';
require_once __DIR__ . '/../helpers/validador.php';
require_once __DIR__ . '/../modelos/usuario.php';
require_once __DIR__ . '/../modelos/empleado.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../modelos/modelo_bitacora.php';

$conex = Conexion::conectar();

class UsuarioController
{
    private $model;
    private $empleadoModel;

    public function __construct()
    {
        $this->model = new Usuario();
        $this->empleadoModel = new Empleado();
    }

    
    private function telefonosCoinciden(string $a, string $b): bool
    {
        $limpiar = function ($v) {
            return preg_replace('/[^0-9]/', '', (string) $v);
        };
        return $limpiar($a) === $limpiar($b) && $limpiar($a) !== '';
    }

    private function empleadoGestionable(int $idEmpleado)
    {
        $idBiblioteca = esCoordinador() ? (int) (guardian_idBibliotecaSesion() ?? 0) : null;
        return $this->empleadoModel->obtenerEmpleadoPorId($idEmpleado, $idBiblioteca);
    }

    public function dispatch()
    {
        $action = $_REQUEST['action'] ?? '';

        switch ($action) {
            case 'crear':
                $this->crear();
                break;
            case 'actualizar':
                $this->actualizar();
                break;
            case 'eliminar':
                $this->eliminar();
                break;
            case 'listar':
                $this->listar();
                break;
            default:
                $this->error('Acción inválida.');
                break;
        }
    }

    private function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        
        if (!esAdministrador() && !esCoordinador()) {
            $this->error('No tienes permisos para crear usuarios.');
            return;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $clave = trim($_POST['clave'] ?? '');
        $claveConfirmacion = trim($_POST['clave_confirmacion'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $id_empleado = intval($_POST['id_empleado'] ?? 0);
        $rol = esAdministrador() ? trim($_POST['rol'] ?? 'usuario') : 'usuario';
        if (!in_array($rol, ['administrador', 'coordinador', 'usuario'], true)) {
            $rol = 'usuario';
        }

        $errors = $this->model->validarUsuario([
            'nombre' => $nombre,
            'clave' => $clave,
            'telefono' => $telefono,
            'id_empleado' => $id_empleado,
            'rol' => $rol,
        ]);

        if ($clave !== $claveConfirmacion) {
            $errors[] = 'La confirmación de la clave no coincide.';
        }

        if (!empty($errors)) {
            $this->error(implode(' ', $errors));
            return;
        }

        if ($this->model->existeUsuario($nombre)) {
            $this->error('Ya existe un usuario con ese nombre.');
            return;
        }

        $empleado = $this->empleadoGestionable($id_empleado);
        if (!$empleado) {
            $this->error('El empleado seleccionado no existe o no pertenece a tu biblioteca.');
            return;
        }

        if (trim((string) ($empleado['telefono'] ?? '')) !== $telefono) {
            $this->error('El teléfono no coincide con el registrado para ese empleado.');
            return;
        }

        if ($this->model->existeUsuarioPorEmpleado($id_empleado)) {
            $this->error('Este empleado ya tiene un usuario registrado. No se puede crear más de uno por empleado.');
            return;
        }

        try {
            $created = $this->model->crearUsuario($nombre, $clave, $telefono, $id_empleado, $rol);
            if ($created) {
                if (!empty($_SESSION['user_id'])) {
                    $conex = Conexion::conectar();
                    registrar_bitacora(Conexion::conectar(), $_SESSION['user_id'], 'Crear', 'Usuario', 'Usuario registrado: ' . $nombre);
                }
                $this->success('Usuario creado correctamente.');
                return;
            }
        } catch (PDOException $e) {
            $this->error('No se pudo crear el usuario.');
            return;
        }

        $this->error('No se pudo crear el usuario.');
    }

    private function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        $id = intval($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $clave = trim($_POST['clave'] ?? '');
        $claveConfirmacion = trim($_POST['clave_confirmacion'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $id_empleado = intval($_POST['id_empleado'] ?? 0);

        if ($id <= 0) {
            $this->error('ID inválido.');
            return;
        }

        $esAdmin = esAdministrador();
        $esCoordinador = esCoordinador();
        $idSesion = intval($_SESSION['user_id'] ?? 0);

        $usuarioActualizado = $this->model->obtenerUsuarioPorId($id);
        if (!$usuarioActualizado) {
            $this->error('El usuario seleccionado no existe.');
            return;
        }

        if (!$esAdmin && !$esCoordinador && $id !== $idSesion) {
            $this->error('No tienes permisos para editar este usuario.');
            return;
        }
        if (
            $esCoordinador
            && (
                !$this->empleadoGestionable((int) ($usuarioActualizado['id_empleado'] ?? 0))
                || ($id !== $idSesion && ($usuarioActualizado['rol'] ?? '') !== 'usuario')
            )
        ) {
            $this->error('Solo puedes editar usuarios de tu biblioteca.');
            return;
        }

        
        
        $rol = null;
        if ($esAdmin) {
            $rolEnviado = trim($_POST['rol'] ?? '');
            if ($rolEnviado !== '' && in_array($rolEnviado, ['administrador', 'coordinador', 'usuario'], true)) {
                $rol = $rolEnviado;
            }
        }

        $datosValidar = [
            'nombre' => $nombre,
            'clave' => $clave,
            'telefono' => $telefono,
            'id_empleado' => $id_empleado,
        ];
        if ($rol !== null) {
            $datosValidar['rol'] = $rol;
        }

        $errors = $this->model->validarUsuario($datosValidar, true);

        if ($clave !== '' && $clave !== $claveConfirmacion) {
            $errors[] = 'La confirmación de la clave no coincide.';
        }

        if (!empty($errors)) {
            $this->error(implode(' ', $errors));
            return;
        }

        if ($this->model->existeUsuario($nombre, $id)) {
            $this->error('Ya existe otro usuario con ese nombre.');
            return;
        }

        if (!$esAdmin && !$esCoordinador && $id_empleado !== (int) ($_SESSION['id_empleado'] ?? 0)) {
            $this->error('No puedes cambiar el empleado vinculado a tu usuario.');
            return;
        }
        if ($esCoordinador && ($usuarioActualizado['rol'] ?? '') !== 'usuario' && $id_empleado !== (int) $usuarioActualizado['id_empleado']) {
            $this->error('No puedes cambiar el empleado vinculado a un usuario con permisos elevados.');
            return;
        }

        $empleado = $this->empleadoGestionable($id_empleado);
        if (!$empleado) {
            $this->error('El empleado seleccionado no existe o no pertenece a tu biblioteca.');
            return;
        }

        if (trim((string) ($empleado['telefono'] ?? '')) !== $telefono) {
            $this->error('El teléfono no coincide con el registrado para ese empleado.');
            return;
        }

        if ($this->model->existeUsuarioPorEmpleado($id_empleado, $id)) {
            $this->error('Este empleado ya tiene otro usuario registrado.');
            return;
        }

        try {
            $updated = $this->model->actualizarUsuario(
                $id,
                $nombre,
                $telefono,
                $id_empleado,
                $clave === '' ? null : $clave,
                $rol
            );
            if ($updated) {
                if (!empty($_SESSION['user_id'])) {
                    $conex = Conexion::conectar();
                    registrar_bitacora(Conexion::conectar(), $_SESSION['user_id'], 'Editar', 'Usuario', "Usuario #$id actualizado: " . $nombre);
                }
                $this->success('Usuario actualizado correctamente.');
                return;
            }
        } catch (PDOException $e) {
            $this->error('No se pudo actualizar el usuario.');
            return;
        }

        $this->error('No se pudo actualizar el usuario.');
    }

    private function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        if (!esAdministrador() && !esCoordinador()) {
            $this->error('No tienes permisos para eliminar usuarios.');
            return;
        }

        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->error('ID inválido.');
            return;
        }
        if (esCoordinador()) {
            $usuario = $this->model->obtenerUsuarioPorId($id);
            if (
                $id === (int) ($_SESSION['user_id'] ?? 0)
                || !$usuario
                || ($usuario['rol'] ?? '') !== 'usuario'
                || !$this->empleadoGestionable((int) ($usuario['id_empleado'] ?? 0))
            ) {
                $this->error('Solo puedes eliminar usuarios de tu biblioteca.');
                return;
            }
        }

        $deleted = $this->model->eliminarUsuario($id);
        if ($deleted) {
            if (!empty($_SESSION['user_id'])) {
                $conex = Conexion::conectar();
                registrar_bitacora(Conexion::conectar(), $_SESSION['user_id'], 'Eliminar', 'Usuario', "Usuario #$id eliminado");
            }
            $this->success('Usuario eliminado correctamente.');
            return;
        }

        $this->error('No se pudo eliminar el usuario.');
    }

    private function listar()
    {
        $data = $this->model->mostrarUsuarios();

        if (esCoordinador()) {
            $idBiblioteca = (int) (guardian_idBibliotecaSesion() ?? 0);
            $idSesion = (int) ($_SESSION['user_id'] ?? 0);
            $data = array_values(array_filter($data, static function ($usuario) use ($idBiblioteca, $idSesion) {
                return (int) ($usuario['id_biblioteca'] ?? 0) === $idBiblioteca
                    && ((int) $usuario['id'] === $idSesion || ($usuario['rol'] ?? '') === 'usuario');
            }));
        } elseif (!esAdministrador()) {
            $idSesion = intval($_SESSION['user_id'] ?? 0);
            $data = array_values(array_filter($data, function ($u) use ($idSesion) {
                return (int) $u['id'] === $idSesion;
            }));
        }

        $this->respond(['success' => true, 'data' => $data]);
    }

    private function success(string $message, array $extra = [])
    {
        $this->respond(array_merge(['success' => true, 'message' => $message], $extra));
    }

    private function error(string $message)
    {
        $this->respond(['success' => false, 'message' => $message]);
    }

    private function respond(array $payload)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

$controller = new UsuarioController();
$controller->dispatch();
