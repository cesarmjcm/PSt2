<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../modelos/tipo_actividad.php';
require_once __DIR__ . '/../helpers/validador.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../modelos/modelo_bitacora.php';

$conex = Conexion::conectar();

class TipoActividadController
{
    private $model;

    public function __construct()
    {
        $this->model = new TipoActividad();
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
        if (!Validador::esNombrePropioValido($nombre, 2, 30)) {
            $this->error('El nombre debe tener entre 2 y 15 caracteres y solo puede contener letras, espacios y los signos - \'');
            return;
        }
        if (Validador::tieneRepeticionSospechosa($nombre)) {
            $this->error('El nombre no puede tener caracteres o patrones repetidos.');
            return;
        }
        if ($descripcion !== '' && !Validador::esDescripcionValida($descripcion, 0, 250)) {
            $this->error('La descripción debe tener entre 0 y 250 caracteres y solo puede contener letras, números y ciertos signos de puntuación.');
            return;
        }
        if ($descripcion !== '' && Validador::tieneRepeticionSospechosa($descripcion)) {
            $this->error('La descripción no puede tener caracteres o patrones repetidos.');
            return;
        }
        if ($this->model->existeNombre($nombre)) {
            $this->error('Ya existe un tipo de actividad con ese nombre.');
            return;
        }

        try {
            $created = $this->model->crearTipo($nombre, $descripcion);
            if ($created) {
                if (!empty($_SESSION['user_id'])) {
                    $conex = Conexion::conectar();
                    registrar_bitacora($_SESSION['user_id'], 'Crear', 'Tipo de actividad', 'Tipo de actividad registrado: ' . $nombre);
                }
                $this->success('Tipo de actividad creado correctamente.');
                return;
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->error('Ya existe un tipo de actividad con ese nombre.');
                return;
            }
            $this->error('No se pudo crear el tipo de actividad.');
            return;
        }

        $this->error('No se pudo crear el tipo de actividad.');
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
        if (!Validador::esNombrePropioValido($nombre, 2, 30)) {
            $this->error('El nombre debe tener entre 2 y 30 caracteres y solo puede contener letras, espacios y los signos - \'');
            return;
        }
        if (Validador::tieneRepeticionSospechosa($nombre)) {
            $this->error('El nombre no puede tener caracteres o patrones repetidos.');
            return;
        }
        if (!Validador::esDescripcionValida($descripcion, 0, 250)) {
            $this->error('La descripción debe tener entre 0 y 250 caracteres y solo puede contener letras, números y ciertos signos de puntuación.');
            return;
        }
        if ($descripcion !== '' && Validador::tieneRepeticionSospechosa($descripcion)) {
            $this->error('La descripción no puede tener caracteres o patrones repetidos.');
            return;
        }
        if ($this->model->existeNombre($nombre, $id)) {
            $this->error('Ya existe otro tipo de actividad con ese nombre.');
            return;
        }

        try {
            $updated = $this->model->actualizarTipo($id, $nombre, $descripcion);
            if ($updated) {
                if (!empty($_SESSION['user_id'])) {
                    $conex = Conexion::conectar();
                    registrar_bitacora($_SESSION['user_id'], 'Editar', 'Tipo de actividad', "Tipo de actividad #$id actualizado: " . $nombre);
                }
                $this->success('Tipo de actividad actualizado correctamente.');
                return;
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->error('Ya existe otro tipo de actividad con ese nombre.');
                return;
            }
            $this->error('No se pudo actualizar el tipo de actividad.');
            return;
        }

        $this->error('No se pudo actualizar el tipo de actividad.');
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
            $deleted = $this->model->eliminarTipo($id);
            if ($deleted) {
                if (!empty($_SESSION['user_id'])) {
                    $conex = Conexion::conectar();
                    registrar_bitacora($_SESSION['user_id'], 'Eliminar', 'Tipo de actividad', "Tipo de actividad #$id eliminado");
                }
                $this->success('Tipo de actividad eliminado correctamente.');
                return;
            }
        } catch (Exception $e) {
            $this->error('No se pudo eliminar el tipo de actividad.');
            return;
        }

        $this->error('No se pudo eliminar el tipo de actividad.');
    }

    private function listar(): void
    {
        $data = $this->model->mostrarTipos();
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

$controller = new TipoActividadController();
$controller->dispatch();
