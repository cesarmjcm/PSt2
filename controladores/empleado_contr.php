<?php
require_once __DIR__ . '/../modelos/empleado.php';
require_once __DIR__ . '/../helpers/validador.php';

class EmpleadoController
{
    private $model;

    public function __construct()
    {
        $this->model = new Empleado();
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

    private function validarDatos(string $nombre, string $apellido, string $telefono, int $id_cargo, string $cedula): ?string
    {
        if ($nombre === '' || $apellido === '' || $telefono === '' || $cedula === '') {
            return 'Nombre, apellido, teléfono y cédula son obligatorios.';
        }
        if ($id_cargo <= 0) {
            return 'Debe seleccionar un cargo.';
        }
        if (!Validador::esNombrePropioValido($nombre, 2, 40)) {
            return 'El nombre debe tener entre 2 y 40 caracteres y solo puede contener letras, espacios y los signos - \'';
        }
        if (!Validador::esNombrePropioValido($apellido, 2, 20)) {
            return 'El apellido debe tener entre 2 y 20 caracteres y solo puede contener letras, espacios y los signos - \'';
        }
        if (!Validador::esTelefonoVenezolano($telefono)) {
            return 'El teléfono debe tener el formato venezolano: empieza en 0, solo números, 11 dígitos en total (ej. 04141234567).';
        }
        if (!Validador::esCedulaValida($cedula)) {
            return 'La cédula debe contener solo números y tener entre 6 y 8 dígitos.';
        }
        return null;
    }

    private function crear(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $id_cargo = intval($_POST['id_cargo'] ?? 0);
        $cedulaRaw = trim($_POST['cedula'] ?? '');

        $errorValidacion = $this->validarDatos($nombre, $apellido, $telefono, $id_cargo, $cedulaRaw);
        if ($errorValidacion !== null) {
            $this->error($errorValidacion);
            return;
        }

        $cedula = (int) $cedulaRaw;

        if ($this->model->existeCedula($cedula)) {
            $this->error('Ya existe un empleado con esa cédula.');
            return;
        }

        try {
            $created = $this->model->crearEmpleado($nombre, $apellido, $telefono, $id_cargo, $cedula);
            if ($created) {
                $this->success('Empleado creado correctamente.');
                return;
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->error('Ya existe un empleado con esa cédula.');
                return;
            }
            $this->error('No se pudo crear el empleado.');
            return;
        }

        $this->error('No se pudo crear el empleado.');
    }

    private function actualizar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        $id = intval($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $id_cargo = intval($_POST['id_cargo'] ?? 0);
        $cedulaRaw = trim($_POST['cedula'] ?? '');

        if ($id <= 0) {
            $this->error('ID inválido.');
            return;
        }

        $errorValidacion = $this->validarDatos($nombre, $apellido, $telefono, $id_cargo, $cedulaRaw);
        if ($errorValidacion !== null) {
            $this->error($errorValidacion);
            return;
        }

        $cedula = (int) $cedulaRaw;

        if ($this->model->existeCedula($cedula, $id)) {
            $this->error('Ya existe otro empleado con esa cédula.');
            return;
        }

        try {
            $updated = $this->model->actualizarEmpleado($id, $nombre, $apellido, $telefono, $id_cargo, $cedula);
            if ($updated) {
                $this->success('Empleado actualizado correctamente.');
                return;
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->error('Ya existe otro empleado con esa cédula.');
                return;
            }
            $this->error('No se pudo actualizar el empleado.');
            return;
        }

        $this->error('No se pudo actualizar el empleado.');
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
            $deleted = $this->model->eliminarEmpleado($id);
            if ($deleted) {
                $this->success('Empleado eliminado correctamente.');
                return;
            }
        } catch (Exception $e) {
            $this->error('No se pudo eliminar el empleado.');
            return;
        }

        $this->error('No se pudo eliminar el empleado.');
    }

    private function listar(): void
    {
        $data = $this->model->mostrarEmpleados();
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

$controller = new EmpleadoController();
$controller->dispatch();
