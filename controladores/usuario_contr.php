<?php
require_once __DIR__ . '/../include/guardian.php';
require_once __DIR__ . '/../helpers/validador.php';
require_once __DIR__ . '/../modelos/usuario.php';
require_once __DIR__ . '/../modelos/empleado.php';

class UsuarioController
{
    private $model;
    private $empleadoModel;

    public function __construct()
    {
        $this->model = new Usuario();
        $this->empleadoModel = new Empleado();
    }

    /**
     * Compara dos teléfonos ignorando espacios, guiones y el signo +,
     * para que "0412-1234567" y "04121234567" se consideren iguales.
     */
    private function telefonosCoinciden(string $a, string $b): bool
    {
        $limpiar = static fn(string $v): string => preg_replace('/[^0-9]/', '', $v);
        return $limpiar($a) === $limpiar($b) && $limpiar($a) !== '';
    }

    public function dispatch(): void
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

    private function crear(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        // Solo un administrador puede dar de alta nuevos usuarios.
        if (!esAdministrador()) {
            $this->error('No tienes permisos para crear usuarios.');
            return;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $clave = trim($_POST['clave'] ?? '');
        $claveConfirmacion = trim($_POST['clave_confirmacion'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $id_empleado = intval($_POST['id_empleado'] ?? 0);
        $rol = trim($_POST['rol'] ?? 'usuario');
        if (!in_array($rol, ['administrador', 'usuario'], true)) {
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

        $empleado = $this->empleadoModel->obtenerEmpleadoPorId($id_empleado);
        if (!$empleado) {
            $this->error('El empleado seleccionado no existe.');
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
                $this->success('Usuario creado correctamente.');
                return;
            }
        } catch (PDOException $e) {
            $this->error('No se pudo crear el usuario.');
            return;
        }

        $this->error('No se pudo crear el usuario.');
    }

    private function actualizar(): void
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
        $idSesion = intval($_SESSION['user_id'] ?? 0);

        // Un usuario que no es administrador solo puede editar su propio registro.
        if (!$esAdmin && $id !== $idSesion) {
            $this->error('No tienes permisos para editar este usuario.');
            return;
        }

        // El rol solo lo puede fijar un administrador; un usuario básico
        // nunca puede auto-asignarse un rol distinto al que ya tiene.
        $rol = null;
        if ($esAdmin) {
            $rolEnviado = trim($_POST['rol'] ?? '');
            if ($rolEnviado !== '' && in_array($rolEnviado, ['administrador', 'usuario'], true)) {
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

        $empleado = $this->empleadoModel->obtenerEmpleadoPorId($id_empleado);
        if (!$empleado) {
            $this->error('El empleado seleccionado no existe.');
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
                $this->success('Usuario actualizado correctamente.');
                return;
            }
        } catch (PDOException $e) {
            $this->error('No se pudo actualizar el usuario.');
            return;
        }

        $this->error('No se pudo actualizar el usuario.');
    }

    private function eliminar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        if (!esAdministrador()) {
            $this->error('No tienes permisos para eliminar usuarios.');
            return;
        }

        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->error('ID inválido.');
            return;
        }

        $deleted = $this->model->eliminarUsuario($id);
        if ($deleted) {
            $this->success('Usuario eliminado correctamente.');
            return;
        }

        $this->error('No se pudo eliminar el usuario.');
    }

    private function listar(): void
    {
        $data = $this->model->mostrarUsuarios();

        if (!esAdministrador()) {
            $idSesion = intval($_SESSION['user_id'] ?? 0);
            $data = array_values(array_filter($data, fn($u) => (int) $u['id'] === $idSesion));
        }

        $this->respond(['success' => true, 'data' => $data]);
    }

    private function success(string $message, array $extra = []): void
    {
        $this->respond(array_merge(['success' => true, 'message' => $message], $extra));
    }

    private function error(string $message): void
    {
        $this->respond(['success' => false, 'message' => $message]);
    }

    private function respond(array $payload): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

$controller = new UsuarioController();
$controller->dispatch();
