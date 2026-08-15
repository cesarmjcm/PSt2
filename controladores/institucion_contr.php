<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../modelos/institucion.php';
require_once __DIR__ . '/../helpers/validador.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../modelos/modelo_bitacora.php';

$conex = Conexion::conectar();

class InstitucionController
{
    private $model;

    public function __construct()
    {
        $this->model = new Institucion();
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
            $this->error('Nombre de la institución es obligatorio.');
            return;
        }
        if (!Validador::esNombrePropioValido($nombre, 2, 40)) {
            $this->error('El nombre debe tener entre 2 y 40 caracteres y solo puede contener letras, espacios y los signos - \'');
            return;
        }
        if ($id_municipio <= 0) {
            $this->error('Debe seleccionar un municipio.');
            return;
        }
        if ($this->model->existeNombre($nombre, $id_municipio)) {
            $this->error('Ya existe una institución con ese nombre en este municipio.');
            return;
        }

        try {
            $created = $this->model->crearInstitucion($nombre, $id_municipio);
            if ($created) {
                if (!empty($_SESSION['user_id'])) {
                    $conex = Conexion::conectar();
                    registrar_bitacora($_SESSION['user_id'], 'Crear', 'Institución', 'Institución registrada: ' . $nombre);
                }
                $this->success('Institución creada correctamente.');
                return;
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->error('Ya existe una institución con ese nombre en este municipio.');
                return;
            }
            $this->error('No se pudo crear la institución.');
            return;
        }

        $this->error('No se pudo crear la institución.');
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

        if ($id <= 0 || $nombre === '') {
            $this->error('ID y nombre de la institución son obligatorios.');
            return;
        }
        if (!Validador::esNombrePropioValido($nombre, 2, 40)) {
            $this->error('El nombre debe tener entre 2 y 40 caracteres y solo puede contener letras, espacios y los signos - \'');
            return;
        }
        if ($id_municipio <= 0) {
            $this->error('Debe seleccionar un municipio.');
            return;
        }
        if ($this->model->existeNombre($nombre, $id_municipio, $id)) {
            $this->error('Ya existe otra institución con ese nombre en este municipio.');
            return;
        }

        try {
            $updated = $this->model->actualizarInstitucion($id, $nombre, $id_municipio);
            if ($updated) {
                if (!empty($_SESSION['user_id'])) {
                    $conex = Conexion::conectar();
                    registrar_bitacora($_SESSION['user_id'], 'Editar', 'Institución', "Institución #$id actualizada: " . $nombre);
                }
                $this->success('Institución actualizada correctamente.');
                return;
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->error('Ya existe otra institución con ese nombre en este municipio.');
                return;
            }
            $this->error('No se pudo actualizar la institución.');
            return;
        }

        $this->error('No se pudo actualizar la institución.');
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

        if ($this->model->tieneSolicitudesAsociadas($id)) {
            $this->error('No se pudo eliminar: esta institución tiene solicitudes asociadas.');
            return;
        }

        try {
            $deleted = $this->model->eliminarInstitucion($id);
            if ($deleted) {
                if (!empty($_SESSION['user_id'])) {
                    $conex = Conexion::conectar();
                    registrar_bitacora($_SESSION['user_id'], 'Eliminar', 'Institución', "Institución #$id eliminada");
                }
                $this->success('Institución eliminada correctamente.');
                return;
            }
        } catch (Exception $e) {
            $this->error('No se pudo eliminar la institución.');
            return;
        }

        $this->error('No se pudo eliminar la institución.');
    }

    private function listar(): void
    {
        $data = $this->model->mostrarInstituciones();
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

$controller = new InstitucionController();
$controller->dispatch();
