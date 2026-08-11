<?php
require_once __DIR__ . '/../modelos/solicitud.php';
require_once __DIR__ . '/../helpers/validador.php';

class SolicitudController
{
    private $model;

    public function __construct()
    {
        $this->model = new Solicitud();
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

    private function collectInput(): array
    {
        return [
            'id_institucion' => intval($_POST['id_institucion'] ?? 0),
            'fecha_solicitud' => trim($_POST['fecha_solicitud'] ?? ''),
            'hora_solicitud' => trim($_POST['hora_solicitud'] ?? ''),
            'lugar' => trim($_POST['lugar'] ?? ''),
            'responsable' => trim($_POST['responsable'] ?? ''),
            'participantes' => intval($_POST['participantes'] ?? 0),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
        ];
    }

    private function validarSolicitud(array $data): array
    {
        $errors = [];
        if (!Validador::esEnteroPositivo($data['id_institucion'])) {
            $errors[] = 'Institución inválida.';
        }
        if (!Validador::esFechaValida($data['fecha_solicitud'])) {
            $errors[] = 'Fecha de solicitud inválida.';
        }
        if (!Validador::esHoraValida($data['hora_solicitud'])) {
            $errors[] = 'Hora de solicitud inválida.';
        }
        if (!Validador::esTextoValido($data['lugar'], 2, 100)) {
            $errors[] = 'El lugar debe tener entre 2 y 100 caracteres y puede contener letras, números y signos básicos.';
        }
        if (!Validador::esNombrePropioValido($data['responsable'], 2, 50)) {
            $errors[] = 'El responsable debe tener entre 2 y 50 caracteres y solo puede contener letras, espacios y los signos - \' .';
        }
        if (!Validador::esEnteroNoNegativo($data['participantes'])) {
            $errors[] = 'La cantidad de participantes debe ser un número entero mayor o igual a cero.';
        }
        if (!Validador::esDescripcionValida($data['descripcion'], 0, 250)) {
            $errors[] = 'La descripción de la solicitud no puede ser mayor a 250 caracteres.';
        }
        return $errors;
    }

    private function crear(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        $data = $this->collectInput();
        $errors = $this->validarSolicitud($data);
        if (!empty($errors)) {
            $this->error(implode(' ', $errors));
            return;
        }

        try {
            $created = $this->model->crearSolicitud(
                $data['id_institucion'],
                $data['fecha_solicitud'],
                $data['hora_solicitud'],
                $data['lugar'],
                $data['responsable'],
                $data['participantes'],
                $data['descripcion']
            );
            if ($created) {
                $this->success('Solicitud creada correctamente.');
                return;
            }
        } catch (PDOException $e) {
            $this->error('No se pudo crear la solicitud.');
            return;
        }

        $this->error('No se pudo crear la solicitud.');
    }

    private function actualizar(): void
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

        $data = $this->collectInput();
        $errors = $this->validarSolicitud($data);
        if (!empty($errors)) {
            $this->error(implode(' ', $errors));
            return;
        }

        try {
            $updated = $this->model->actualizarSolicitud(
                $id,
                $data['id_institucion'],
                $data['fecha_solicitud'],
                $data['hora_solicitud'],
                $data['lugar'],
                $data['responsable'],
                $data['participantes'],
                $data['descripcion']
            );
            if ($updated) {
                $this->success('Solicitud actualizada correctamente.');
                return;
            }
        } catch (PDOException $e) {
            $this->error('No se pudo actualizar la solicitud.');
            return;
        }

        $this->error('No se pudo actualizar la solicitud.');
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
            $deleted = $this->model->eliminarSolicitud($id);
            if ($deleted) {
                $this->success('Solicitud eliminada correctamente.');
                return;
            }
        } catch (Exception $e) {
            $this->error('No se pudo eliminar la solicitud.');
            return;
        }

        $this->error('No se pudo eliminar la solicitud.');
    }

    private function listar(): void
    {
        $data = $this->model->mostrarSolicitudes();
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

$controller = new SolicitudController();
$controller->dispatch();