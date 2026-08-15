<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../modelos/espacio.php';
require_once __DIR__ . '/../helpers/validador.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../modelos/modelo_bitacora.php';

$conex = Conexion::conectar();

class EspacioController
{
    private $model;

    public function __construct()
    {
        $this->model = new Espacio();
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
                $this->error('Accion invalida.');
                break;
        }
    }

    private function crear(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $capacidadRaw = $_POST['capacidad'] ?? 0;
        $direccion = trim($_POST['direccion'] ?? '');
        $metodo_contactar = trim($_POST['metodo_contactar'] ?? '');
        if ($nombre === '') {
            $this->error('Nombre es obligatorio.');
            return;
        }
        if (!Validador::esTextoValido($nombre, 2, 30)) {
            $this->error('El nombre debe tener entre 2 y 30 caracteres y solo puede contener letras, números, espacios y los signos - . \'');
            return;
        }
        if (!Validador::esEnteroNoNegativo($capacidadRaw, 100000)) {
            $this->error('La capacidad debe ser un número entero válido.');
            return;
        }
        if ($direccion !== '' && !Validador::esDescripcionValida($direccion, 0, 30)) {
            $this->error('La dirección tiene un formato o longitud inválida (máx. 30 caracteres).');
            return;
        }
        if ($metodo_contactar !== '' && !Validador::esMetodoContactarValido($metodo_contactar, 0, 100)) {
            $this->error('El método de contacto debe ser un teléfono, correo, usuario de red social (@usuario) o enlace válido (máx. 100 caracteres).');
            return;
        }
        if ($this->model->existeNombre($nombre)) {
            $this->error('Ya existe un espacio cultural con ese nombre.');
            return;
        }

        try {
            $created = $this->model->crearEspacio($nombre, intval($capacidadRaw), $direccion, $metodo_contactar);
            if ($created) {
                if (!empty($_SESSION['user_id'])) {
                    $conex = Conexion::conectar();
                    registrar_bitacora($_SESSION['user_id'], 'Crear', 'Espacio cultural', 'Espacio cultural registrado: ' . $nombre);
                }
                $this->success('Espacio cultural creado correctamente.');
                return;
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->error('Ya existe un espacio cultural con ese nombre.');
                return;
            }
            $this->error('No se pudo crear el espacio cultural.');
            return;
        }

        $this->error('No se pudo crear el espacio cultural.');
    }

    private function actualizar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        $id = intval($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $capacidadRaw = $_POST['capacidad'] ?? 0;
        $direccion = trim($_POST['direccion'] ?? '');
        $metodo_contactar = trim($_POST['metodo_contactar'] ?? '');
        if ($id <= 0 || $nombre === '') {
            $this->error('ID y nombre son obligatorios.');
            return;
        }
        if (!Validador::esTextoValido($nombre, 2, 30)) {
            $this->error('El nombre debe tener entre 2 y 30 caracteres y solo puede contener letras, números, espacios y los signos - . \'');
            return;
        }
        if (!Validador::esEnteroNoNegativo($capacidadRaw, 100000)) {
            $this->error('La capacidad debe ser un número entero válido.');
            return;
        }
        if ($direccion !== '' && !Validador::esDescripcionValida($direccion, 0, 30)) {
            $this->error('La dirección tiene un formato o longitud inválida (máx. 30 caracteres).');
            return;
        }
        if ($metodo_contactar !== '' && !Validador::esMetodoContactarValido($metodo_contactar, 0, 100)) {
            $this->error('El método de contacto debe ser un teléfono, correo, usuario de red social (@usuario) o enlace válido (máx. 100 caracteres).');
            return;
        }
        if ($this->model->existeNombre($nombre, $id)) {
            $this->error('Ya existe otro espacio cultural con ese nombre.');
            return;
        }

        try {
            $updated = $this->model->actualizarEspacio($id, $nombre, intval($capacidadRaw), $direccion, $metodo_contactar);
            if ($updated) {
                if (!empty($_SESSION['user_id'])) {
                    $conex = Conexion::conectar();
                    registrar_bitacora($_SESSION['user_id'], 'Editar', 'Espacio cultural', "Espacio cultural #$id actualizado: " . $nombre);
                }
                $this->success('Espacio cultural actualizado correctamente.');
                return;
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->error('Ya existe otro espacio cultural con ese nombre.');
                return;
            }
            $this->error('No se pudo actualizar el espacio cultural.');
            return;
        }

        $this->error('No se pudo actualizar el espacio cultural.');
    }

    private function eliminar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->error('ID inválido.');
            return;
        }

        $deleted = $this->model->eliminarEspacio($id);
        if ($deleted) {
            if (!empty($_SESSION['user_id'])) {
                $conex = Conexion::conectar();
                registrar_bitacora($_SESSION['user_id'], 'Eliminar', 'Espacio cultural', "Espacio cultural #$id eliminado");
            }
            $this->success('Espacio cultural eliminado correctamente.');
            return;
        }

        $this->error('No se pudo eliminar el espacio cultural.');
    }

    private function listar(): void
    {
        $data = $this->model->mostrarEspacios();
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

$controller = new EspacioController();
$controller->dispatch();
