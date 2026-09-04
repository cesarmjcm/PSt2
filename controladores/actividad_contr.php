<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../helpers/validador.php';
require_once __DIR__ . '/../modelos/actividad.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../modelos/modelo_bitacora.php';

class ActividadController
{
    private $model;
    private $conex;

    public function __construct()
    {
        $this->model = new Actividad();
        
        
        
        
        
        
        $this->conex = Conexion::conectar();
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

        $data = $this->collectInput();
        $errors = $this->model->validarActividad($data);
        if (!empty($errors)) {
            
            
            
            
            error_log('[ActividadController::crear] Validación falló: ' . implode(' | ', $errors)
                . ' | Datos recibidos: ' . json_encode($data, JSON_UNESCAPED_UNICODE));
            $this->error(implode(' ', $errors));
            return;
        }

        try {
            $id = $this->model->crearActividadCompleta($data);
            if ($id) {
                if (!empty($_SESSION['user_id'])) {
                    $okBitacora = registrar_bitacora($this->conex, $_SESSION['user_id'], 'Crear', 'Actividad', 'Actividad registrada: ' . $data['nombre']);
                    if (!$okBitacora) {
                        error_log('[ActividadController::crear] La actividad #' . $id . ' se creó, pero registrar_bitacora() falló.');
                    }
                }
                header('Location: ' . $this->getReturnUrl());
                exit;
            }
        } catch (Exception $e) {
            error_log('[ActividadController::crear] Excepción: ' . $e->getMessage());
            $this->error('No se pudo crear la actividad: ' . $e->getMessage());
            return;
        }

        error_log('[ActividadController::crear] crearActividadCompleta devolvió falso/0 sin excepción. Datos: '
            . json_encode($data, JSON_UNESCAPED_UNICODE));
        $this->error('No se pudo crear la actividad.');
    }

    private function actualizar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }
   
    
        if (($_SESSION['user_rol'] ?? '') !== 'administrador') {
            $this->error('No tienes permisos para editar actividades.');
            return;
        }
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->error('ID de actividad inválido.');
            return;
        }

        $data = $this->collectInput();
        $errors = $this->model->validarActividad($data);
        if (!empty($errors)) {
            error_log('[ActividadController::actualizar] Validación falló: ' . implode(' | ', $errors)
                . ' | id=' . $id . ' | Datos recibidos: ' . json_encode($data, JSON_UNESCAPED_UNICODE));
            $this->error(implode(' ', $errors));
            return;
        }

        try {
            $updated = $this->model->actualizarActividadCompleta($id, $data);
            if ($updated) {
                if (!empty($_SESSION['user_id'])) {
                    $okBitacora = registrar_bitacora($this->conex, $_SESSION['user_id'], 'Editar', 'Actividad', "Actividad #$id actualizada: " . $data['nombre']);
                    if (!$okBitacora) {
                        error_log('[ActividadController::actualizar] La actividad #' . $id . ' se actualizó, pero registrar_bitacora() falló.');
                    }
                }
                header('Location: ' . $this->getReturnUrl());
                exit;
            }
        } catch (Exception $e) {
            error_log('[ActividadController::actualizar] Excepción: ' . $e->getMessage());
            $this->error('No se pudo actualizar la actividad: ' . $e->getMessage());
            return;
        }

        $this->error('No se pudo actualizar la actividad.');
    }

    private function eliminar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->error('ID de actividad inválido.');
            return;
        }

        $deleted = $this->model->eliminarActividad($id);
        if ($deleted) {
            if (!empty($_SESSION['user_id'])) {
                $okBitacora = registrar_bitacora($this->conex, $_SESSION['user_id'], 'Eliminar', 'Actividad', "Actividad #$id eliminada");
                if (!$okBitacora) {
                    error_log('[ActividadController::eliminar] La actividad #' . $id . ' se eliminó, pero registrar_bitacora() falló.');
                }
            }
            header('Location: ' . $this->getReturnUrl());
            exit;
        }

        $this->error('No se pudo eliminar la actividad.');
    }

    private function listar(): void
    {
        
        
        $actividades = $this->model->mostrarActividadesCompletas();
        $this->respond(['success' => true, 'data' => $actividades]);
    }

    private function collectInput(): array
    {
        
        
        
        
        
        
        $fecha = Validador::normalizarTexto($_POST['fecha'] ?? $_POST['fechaActividad'] ?? '');
        $hora = Validador::normalizarTexto($_POST['hora'] ?? $_POST['horaActividad'] ?? '');
        $diaSemana = Validador::normalizarTexto($_POST['dia_semana'] ?? $_POST['diaActividad'] ?? '');

        if ($diaSemana === '' && $fecha !== '') {
            $diaSemana = $this->calcularDiaSemana($fecha);
        }

        $descripcion = Validador::normalizarTexto($_POST['descripcion'] ?? $_POST['descripcionActividad'] ?? '');
        $objetivo = Validador::normalizarTexto($_POST['objetivo'] ?? $_POST['objetivoEnfoque'] ?? '');
        $participantes = intval($_POST['participantes'] ?? $_POST['cantidadParticipantes'] ?? 0);

        
        
        
        
        
        
        
        $tipoUbicacion = Validador::normalizarTexto($_POST['tipo_ubicacion'] ?? '');
        if ($tipoUbicacion === '') {
            
            
            $tipoUbicacion = intval($_POST['id_espacio_cultural'] ?? 0) > 0 ? 'espacio' : 'biblioteca';
        }

        $estadosValidos = ['confirmada', 'ejecutada', 'cancelada'];
        $estado = Validador::normalizarTexto($_POST['estado'] ?? '');
        if (!in_array($estado, $estadosValidos, true)) {
            $estado = 'confirmada';
        }

        return [
            'nombre'               => Validador::normalizarTexto($_POST['nombre'] ?? $_POST['tipoActividad'] ?? ''),
            'descripcion'          => $descripcion,
            'objetivo'             => $objetivo !== '' ? $objetivo : 'No definido',
            'participantes'        => $participantes,
            'fecha'                => $fecha,
            'hora'                 => $hora,
            'dia_semana'           => $diaSemana,
            'estado'               => $estado,
            'nivel_impacto'        => Validador::normalizarTexto($_POST['nivel_impacto'] ?? $_POST['nivel__impacto'] ?? ''),
            'tipo_ubicacion'       => $tipoUbicacion,
            'id_biblioteca'        => intval($_POST['id_biblioteca'] ?? 0),
            'municipio_id'         => intval($_POST['municipio_id'] ?? $_POST['Municipio'] ?? 0),
            'parroquia'            => Validador::normalizarTexto($_POST['parroquia'] ?? ''),
            'comuna'               => Validador::normalizarTexto($_POST['comuna'] ?? ''),
            'id_espacio_cultural'  => intval($_POST['id_espacio_cultural'] ?? 0),
            'id_tipo_actividad'    => intval($_POST['id_tipo_actividad'] ?? 0),
            'responsable'          => Validador::normalizarTexto($_POST['responsable'] ?? $_POST['id_responsable'] ?? ''),
            'telefono_responsable' => Validador::normalizarTexto($_POST['telefono_responsable'] ?? $_POST['telefonoResponsable'] ?? ''),
        ];
    }

    private function calcularDiaSemana(string $fecha): string
    {
        try {
            $fechaObj = new DateTime($fecha);
            $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            return $dias[(int)$fechaObj->format('w')];
        } catch (Exception $e) {
            return '';
        }
    }

    private function getReturnUrl(): string
    {
        $returnUrl = trim($_POST['return_url'] ?? '');
        if ($returnUrl === '') {
            $returnUrl = trim($_SERVER['HTTP_REFERER'] ?? '');
        }

        if ($returnUrl !== '') {
            $returnUrl = filter_var($returnUrl, FILTER_SANITIZE_URL);
            $parsed = parse_url($returnUrl);
            if ($parsed !== false) {
                $hostMatches = empty($parsed['host']) || $parsed['host'] === ($_SERVER['HTTP_HOST'] ?? '');
                if ($hostMatches) {
                    $path = $parsed['path'] ?? $returnUrl;
                    $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
                    return $path . $query;
                }
            }
        }

        return '../src/main2.php';
    }

    private function success(string $message, array $extra = []): void
    {
        if ($this->shouldReturnJson()) {
            $this->respond(array_merge(['success' => true, 'message' => $message], $extra));
        }

        header('Location: ' . $this->getReturnUrl());
        exit;
    }

    private function error(string $message): void
    {
        if ($this->shouldReturnJson()) {
            $this->respond(['success' => false, 'message' => $message]);
        }

        header('Location: ' . $this->getReturnUrl());
        exit;
    }

    private function shouldReturnJson(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return true;
        }

        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    private function respond(array $payload): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

$controller = new ActividadController();
$controller->dispatch();
