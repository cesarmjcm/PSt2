<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../modelos/rango_act.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../modelos/modelo_bitacora.php';

$conex = Conexion::conectar();

class RangoActController
{
    private $model;

    public function __construct()
    {
        $this->model = new RangoAct();
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

        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        if ($nombre === '') {
            $this->error('Nombre es obligatorio.');
            return;
        }

        $created = $this->model->crearRangoAct($nombre, $descripcion);
        if ($created) {
            if (!empty($_SESSION['user_id'])) {
                $conex = Conexion::conectar();
                registrar_bitacora($_SESSION['user_id'], 'Crear', 'Rango de actividad', 'Rango de actividad registrado: ' . $nombre);
            }
            $this->success('Rango de actividad creado correctamente.');
            return;
        }

        $this->error('No se pudo crear el rango de actividad.');
    }

    private function actualizar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        $id = intval($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        if ($id <= 0 || $nombre === '') {
            $this->error('ID y nombre son obligatorios.');
            return;
        }

        $updated = $this->model->actualizarRangoAct($id, $nombre, $descripcion);
        if ($updated) {
            if (!empty($_SESSION['user_id'])) {
                $conex = Conexion::conectar();
                registrar_bitacora($_SESSION['user_id'], 'Editar', 'Rango de actividad', "Rango de actividad #$id actualizado: " . $nombre);
            }
            $this->success('Rango de actividad actualizado correctamente.');
            return;
        }

        $this->error('No se pudo actualizar el rango de actividad.');
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

        $deleted = $this->model->eliminarRangoAct($id);
        if ($deleted) {
            if (!empty($_SESSION['user_id'])) {
                $conex = Conexion::conectar();
                registrar_bitacora($_SESSION['user_id'], 'Eliminar', 'Rango de actividad', "Rango de actividad #$id eliminado");
            }
            $this->success('Rango de actividad eliminado correctamente.');
            return;
        }

        $this->error('No se pudo eliminar el rango de actividad.');
    }

    private function listar(): void
    {
        $data = $this->model->mostrarRangosAct();
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

$controller = new RangoActController();
$controller->dispatch();
