<?php
require_once __DIR__ . '/../modelos/nv_act.php';
require_once __DIR__ . '/../helpers/validador.php';

class NvActController
{
    private $model;

    public function __construct()
    {
        $this->model = new NivelImpacto();
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

        $nombre = trim($_POST['nombre_impacto'] ?? '');
        if ($nombre === '') {
            $this->error('Nombre es obligatorio.');
            return;
        }
        if (!Validador::esNombrePropioValido($nombre, 2, 20)) {
            $this->error('El nombre debe tener entre 2 y 20 caracteres y solo puede contener letras, espacios y los signos - \'');
            return;
        }
        if ($this->model->existeNombre($nombre)) {
            $this->error('Ya existe un nivel de impacto con ese nombre.');
            return;
        }

        try {
            $created = $this->model->crearNivel($nombre);
            if ($created) {
                $this->success('Nivel de impacto creado correctamente.');
                return;
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->error('Ya existe un nivel de impacto con ese nombre.');
                return;
            }
            $this->error('No se pudo crear el nivel de impacto.');
            return;
        }

        $this->error('No se pudo crear el nivel de impacto.');
    }

    private function actualizar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        $id = intval($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre_impacto'] ?? '');
        if ($id <= 0 || $nombre === '') {
            $this->error('ID y nombre son obligatorios.');
            return;
        }
        if (!Validador::esNombrePropioValido($nombre, 2, 20)) {
            $this->error('El nombre debe tener entre 2 y 20 caracteres y solo puede contener letras, espacios y los signos - \'');
            return;
        }
        if ($this->model->existeNombre($nombre, $id)) {
            $this->error('Ya existe otro nivel de impacto con ese nombre.');
            return;
        }

        try {
            $updated = $this->model->actualizarNivel($id, $nombre);
            if ($updated) {
                $this->success('Nivel de impacto actualizado correctamente.');
                return;
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->error('Ya existe otro nivel de impacto con ese nombre.');
                return;
            }
            $this->error('No se pudo actualizar el nivel de impacto.');
            return;
        }

        $this->error('No se pudo actualizar el nivel de impacto.');
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
            $deleted = $this->model->eliminarNivel($id);
            if ($deleted) {
                $this->success('Nivel de impacto eliminado correctamente.');
                return;
            }
        } catch (Exception $e) {
            $this->error('No se pudo eliminar: este nivel de impacto tiene actividades asociadas.');
            return;
        }

        $this->error('No se pudo eliminar el nivel de impacto.');
    }

    private function listar(): void
    {
        $data = $this->model->mostrarNiveles();
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

$controller = new NvActController();
$controller->dispatch();
