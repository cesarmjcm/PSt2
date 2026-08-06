<?php
require_once __DIR__ . '/../modelos/cargo.php';
require_once __DIR__ . '/../helpers/validador.php';

class CargoController
{
    private $model;

    public function __construct()
    {
        $this->model = new Cargo();
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
        if (!Validador::esNombrePropioValido($nombre, 2, 36)) {
            $this->error('El nombre debe tener entre 2 y 36 caracteres y solo puede contener letras, espacios y los signos - \'');
            return;
        }
        if (Validador::tieneRepeticionSospechosa($nombre)) {
            $this->error('El nombre no puede tener caracteres o patrones repetidos.');
            return;
        }
        if ($descripcion !== '' && !Validador::esDescripcionValida($descripcion, 0, 30)) {
            $this->error('La descripción tiene un formato o longitud inválida (máx. 30 caracteres).');
            return;
        }
        if ($descripcion !== '' && Validador::tieneRepeticionSospechosa($descripcion)) {
            $this->error('La descripción no puede tener caracteres o patrones repetidos.');
            return;
        }
        if ($this->model->existeNombre($nombre)) {
            $this->error('Ya existe un cargo con ese nombre.');
            return;
        }

        try {
            $created = $this->model->crearCargo($nombre, $descripcion);
            if ($created) {
                $this->success('Cargo creado correctamente.');
                return;
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->error('Ya existe un cargo con ese nombre.');
                return;
            }
            $this->error('No se pudo crear el cargo.');
            return;
        }

        $this->error('No se pudo crear el cargo.');
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
        if (!Validador::esNombrePropioValido($nombre, 2, 36)) {
            $this->error('El nombre debe tener entre 2 y 36 caracteres y solo puede contener letras, espacios y los signos - \'');
            return;
        }
        if (Validador::tieneRepeticionSospechosa($nombre)) {
            $this->error('El nombre no puede tener caracteres o patrones repetidos.');
            return;
        }
        if ($descripcion !== '' && !Validador::esDescripcionValida($descripcion, 0, 30)) {
            $this->error('La descripción tiene un formato o longitud inválida (máx. 30 caracteres).');
            return;
        }
        if ($descripcion !== '' && Validador::tieneRepeticionSospechosa($descripcion)) {
            $this->error('La descripción no puede tener caracteres o patrones repetidos.');
            return;
        }
        if ($this->model->existeNombre($nombre, $id)) {
            $this->error('Ya existe otro cargo con ese nombre.');
            return;
        }

        try {
            $updated = $this->model->actualizarCargo($id, $nombre, $descripcion);
            if ($updated) {
                $this->success('Cargo actualizado correctamente.');
                return;
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->error('Ya existe otro cargo con ese nombre.');
                return;
            }
            $this->error('No se pudo actualizar el cargo.');
            return;
        }

        $this->error('No se pudo actualizar el cargo.');
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
            $deleted = $this->model->eliminarCargo($id);
            if ($deleted) {
                $this->success('Cargo eliminado correctamente.');
                return;
            }
        } catch (Exception $e) {
            $this->error('No se pudo eliminar: este cargo tiene empleados asociados.');
            return;
        }

        $this->error('No se pudo eliminar el cargo.');
    }

    private function listar(): void
    {
        $data = $this->model->mostrarCargos();
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

$controller = new CargoController();
$controller->dispatch();
