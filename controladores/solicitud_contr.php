<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../include/guardian.php';
require_once __DIR__ . '/../modelos/solicitud.php';
require_once __DIR__ . '/../helpers/validador.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../modelos/modelo_bitacora.php';

$conex = Conexion::conectar();

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

    
    private function esCampoEnBlanco(string $valor): bool
    {
        
        $sinEspacios = preg_replace('/[\s\x{00A0}\x{200B}]+/u', '', $valor);
        return $sinEspacios === null || $sinEspacios === '';
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
            'estado' => trim($_POST['estado'] ?? 'Pendiente'),
        ];
    }

    private function validarSolicitud(array $data): array
    {
        $errors = [];

        
        
        $camposObligatorios = [
            'id_institucion'  => 'La institución',
            'fecha_solicitud' => 'La fecha de solicitud',
            'hora_solicitud'  => 'La hora de solicitud',
            'lugar'           => 'El lugar',
            'responsable'     => 'El responsable',
        ];
        foreach ($camposObligatorios as $campo => $etiqueta) {
            $valor = $data[$campo];
            
            $estaVacio = is_string($valor)
                ? $this->esCampoEnBlanco($valor)
                : (int) $valor <= 0;
            if ($estaVacio) {
                $errors[] = "{$etiqueta} no puede estar vacío ni contener solo espacios en blanco.";
            }
        }
        
        
        if (!isset($_POST['participantes']) || $this->esCampoEnBlanco((string) $_POST['participantes'])) {
            $errors[] = 'La cantidad de participantes no puede estar vacía ni contener solo espacios en blanco.';
        }

        
        
        if (!empty($errors)) {
            return $errors;
        }

        
        if (!Validador::esEnteroPositivo($data['id_institucion'])) {
            $errors[] = 'Institución inválida: debe ser un identificador numérico positivo.';
        }
        if (!Validador::esFechaValida($data['fecha_solicitud'])) {
            $errors[] = 'Fecha de solicitud inválida: debe tener el formato AAAA-MM-DD.';
        }
        if (!Validador::esHoraValida($data['hora_solicitud'])) {
            $errors[] = 'Hora de solicitud inválida: debe tener el formato HH:MM (24 horas).';
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
            $errors[] = 'La descripción de la solicitud no puede ser mayor a 250 caracteres y solo puede contener letras, números y signos básicos.';
        }
        if (!in_array($data['estado'], ['pendiente', 'aprobada', 'rechazada', 'cancelada'], true)) {
            $errors[] = 'Estado de solicitud inválido.';
        }

        
        
        $camposTexto = [
            'lugar'       => 'lugar',
            'responsable' => 'responsable',
            'descripcion' => 'descripción',
        ];
        foreach ($camposTexto as $campo => $etiqueta) {
            $valor = $data[$campo];
            if ($valor !== '' && Validador::tieneRepeticionSospechosa($valor)) {
                $errors[] = "El campo {$etiqueta} contiene caracteres o texto repetido de forma sospechosa.";
            }
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
                $data['descripcion'],
                $data['estado']
            );
            if ($created) {
                if (!empty($_SESSION['user_id'])) {
                    $conex = Conexion::conectar();
                    registrar_bitacora($conex, $_SESSION['user_id'], 'Crear', 'Solicitud', 'Solicitud registrada para institución #' . $data['id_institucion']);
                }
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
                $data['descripcion'],
                $data['estado']
            );
            if ($updated) {
                if (!empty($_SESSION['user_id'])) {
                    $conex = Conexion::conectar();
                    registrar_bitacora($conex, $_SESSION['user_id'], 'Editar', 'Solicitud', "Solicitud #$id actualizada");
                }
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
                if (!empty($_SESSION['user_id'])) {
                    $conex = Conexion::conectar();
                    registrar_bitacora($conex, $_SESSION['user_id'], 'Eliminar', 'Solicitud', "Solicitud #$id eliminada");
                }
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