<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../modelos/comuna.php';
require_once __DIR__ . '/../helpers/validador.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../modelos/modelo_bitacora.php';

$conex = Conexion::conectar();

class ComunaController
{
    private $model;

    public function __construct()
    {
        $this->model = new Comuna();
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
        $id_parroquia = intval($_POST['id_parroquia'] ?? 0);
        if ($nombre === '') {
            $this->error('Nombre es obligatorio.');
            return;
        }
        if ($id_parroquia <= 0) {
            $this->error('Debe seleccionar una parroquia.');
            return;
        }
        if (!Validador::esNombrePropioValido($nombre, 2, 30)) {
            $this->error('El nombre debe tener entre 2 y 30 caracteres y solo puede contener letras, espacios y los signos - \'');
            return;
        }
        if ($this->model->existeNombre($nombre, $id_parroquia)) {
            $this->error('Ya existe una comuna con ese nombre en esta parroquia.');
            return;
        }

        try {
            $created = $this->model->crearComuna($nombre, $id_parroquia);
            if ($created) {
                if (!empty($_SESSION['user_id'])) {
                    $conex = Conexion::conectar();
                    registrar_bitacora($_SESSION['user_id'], 'Crear', 'Comuna', 'Comuna registrada: ' . $nombre);
                }
                $this->success('Comuna creada correctamente.');
                return;
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->error('Ya existe una comuna con ese nombre en esta parroquia.');
                return;
            }
            $this->error('No se pudo crear la comuna.');
            return;
        }

        $this->error('No se pudo crear la comuna.');
    }

    private function actualizar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        $id = intval($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $id_parroquia = intval($_POST['id_parroquia'] ?? 0);
        if ($id <= 0 || $nombre === '' || $id_parroquia <= 0) {
            $this->error('ID, nombre y parroquia son obligatorios.');
            return;
        }
        if (!Validador::esNombrePropioValido($nombre, 2, 30)) {
            $this->error('El nombre debe tener entre 2 y 30 caracteres y solo puede contener letras, espacios y los signos - \'');
            return;
        }
        if ($this->model->existeNombre($nombre, $id_parroquia, $id)) {
            $this->error('Ya existe otra comuna con ese nombre en esta parroquia.');
            return;
        }

        try {
            $updated = $this->model->actualizarComuna($id, $nombre, $id_parroquia);
            if ($updated) {
                if (!empty($_SESSION['user_id'])) {
                    $conex = Conexion::conectar();
                    registrar_bitacora($_SESSION['user_id'], 'Editar', 'Comuna', "Comuna #$id actualizada: " . $nombre);
                }
                $this->success('Comuna actualizada correctamente.');
                return;
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->error('Ya existe otra comuna con ese nombre en esta parroquia.');
                return;
            }
            $this->error('No se pudo actualizar la comuna.');
            return;
        }

        $this->error('No se pudo actualizar la comuna.');
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

        try {
            $deleted = $this->model->eliminarComuna($id);
            if ($deleted) {
                if (!empty($_SESSION['user_id'])) {
                    $conex = Conexion::conectar();
                    registrar_bitacora($_SESSION['user_id'], 'Eliminar', 'Comuna', "Comuna #$id eliminada");
                }
                $this->success('Comuna eliminada correctamente.');
                return;
            }
        } catch (Exception $e) {
            $this->error('No se pudo eliminar la comuna.');
            return;
        }

        $this->error('No se pudo eliminar la comuna.');
    }

    private function listar(): void
    {
        $data = $this->model->mostrarComunas();
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

$controller = new ComunaController();
$controller->dispatch();
