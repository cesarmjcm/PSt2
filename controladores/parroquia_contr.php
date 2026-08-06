<?php
require_once __DIR__ . '/../modelos/parroquia.php';
require_once __DIR__ . '/../helpers/validador.php';

class ParroquiaController
{
    private $model;

    public function __construct()
    {
        $this->model = new Parroquia();
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
        $id_municipio = intval($_POST['id_municipio'] ?? 0);
        if ($nombre === '') {
            $this->error('Nombre es obligatorio.');
            return;
        }
        if ($id_municipio <= 0) {
            $this->error('Debe seleccionar un municipio.');
            return;
        }
        if (!Validador::esNombrePropioValido($nombre, 2, 30)) {
            $this->error('El nombre debe tener entre 2 y 30 caracteres y solo puede contener letras, espacios y los signos - \'');
            return;
        }
        if ($this->model->existeNombre($nombre, $id_municipio)) {
            $this->error('Ya existe una parroquia con ese nombre en este municipio.');
            return;
        }

        try {
            $created = $this->model->crearParroquia($nombre, $id_municipio);
            if ($created) {
                $this->success('Parroquia creada correctamente.');
                return;
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->error('Ya existe una parroquia con ese nombre en este municipio.');
                return;
            }
            $this->error('No se pudo crear la parroquia.');
            return;
        }

        $this->error('No se pudo crear la parroquia.');
    }

    private function actualizar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        $id = intval($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $id_municipio = intval($_POST['id_municipio'] ?? 0);
        if ($id <= 0 || $nombre === '' || $id_municipio <= 0) {
            $this->error('ID, nombre y municipio son obligatorios.');
            return;
        }
        if (!Validador::esNombrePropioValido($nombre, 2, 30)) {
            $this->error('El nombre debe tener entre 2 y 30 caracteres y solo puede contener letras, espacios y los signos - \'');
            return;
        }
        if ($this->model->existeNombre($nombre, $id_municipio, $id)) {
            $this->error('Ya existe otra parroquia con ese nombre en este municipio.');
            return;
        }

        try {
            $updated = $this->model->actualizarParroquia($id, $nombre, $id_municipio);
            if ($updated) {
                $this->success('Parroquia actualizada correctamente.');
                return;
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->error('Ya existe otra parroquia con ese nombre en este municipio.');
                return;
            }
            $this->error('No se pudo actualizar la parroquia.');
            return;
        }

        $this->error('No se pudo actualizar la parroquia.');
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
            $deleted = $this->model->eliminarParroquia($id);
            if ($deleted) {
                $this->success('Parroquia eliminada correctamente.');
                return;
            }
        } catch (PDOException $e) {
            $this->error('No se pudo eliminar: esta parroquia tiene bibliotecas o comunas asociadas.');
            return;
        }

        $this->error('No se pudo eliminar la parroquia.');
    }

    private function listar(): void
    {
        $data = $this->model->mostrarParroquias();
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

$controller = new ParroquiaController();
$controller->dispatch();
