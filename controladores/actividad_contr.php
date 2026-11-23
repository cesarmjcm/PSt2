<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../include/guardian.php';
require_once __DIR__ . '/../helpers/validador.php';
require_once __DIR__ . '/../modelos/actividad.php';
require_once __DIR__ . '/../modelos/empleado.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../modelos/modelo_bitacora.php';

class ActividadController
{
    private $model;
    private $empleadoModel;
    private $conex;

    const TRANSICIONES_ESTADO = [
        'pendiente' => ['pendiente', 'confirmada', 'cancelada'],
        'confirmada' => ['confirmada', 'ejecutada', 'cancelada'],
        'ejecutada' => ['ejecutada'],
        'cancelada' => ['cancelada'],
    ];

    public function __construct()
    {
        $this->model = new Actividad();
        $this->empleadoModel = new Empleado();
        
        
        
        
        
        
        $this->conex = Conexion::conectar();
    }

    public function dispatch()
    {
        $action = (isset($_REQUEST['action']) ? $_REQUEST['action'] : (''));

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

    private function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        $data = $this->collectInput();
        if (!esAdministrador()) {
            $idBibliotecaUsuario = (int) ((guardian_idBibliotecaSesion()));
            if ($idBibliotecaUsuario <= 0) {
                $this->error('El usuario no tiene una biblioteca asociada.');
                return;
            }
            $data['id_biblioteca'] = $data['tipo_ubicacion'] === 'biblioteca' ? $idBibliotecaUsuario : 0;
        }
        $errorResponsable = $this->completarDatosResponsable($data);
        if ($errorResponsable !== null) {
            $this->error($errorResponsable);
            return;
        }
        if (!esAdministrador() && $data['tipo_ubicacion'] === 'espacio' && $data['responsable'] === '') {
            $this->error('Seleccione un responsable de su biblioteca para una actividad en un espacio cultural.');
            return;
        }
        $errors = $this->model->validarActividad($data);
        if (!empty($errors)) {
            
            
            
            
            error_log('[ActividadController::crear] Validación falló: ' . implode(' | ', $errors)
                . ' | Datos recibidos: ' . json_encode($data, JSON_UNESCAPED_UNICODE));
            $this->error(implode(' ', $errors));
            return;
        }

        $hayConflicto = $this->model->existeConflictoHorario($data);
        error_log('[ActividadController::crear] existeConflictoHorario=' . ($hayConflicto ? 'true' : 'false')
            . ' | fecha=' . ((isset($data['fecha']) ? $data['fecha'] : (''))) . ' hora=' . ((isset($data['hora']) ? $data['hora'] : ('')))
            . ' id_biblioteca=' . ((isset($data['id_biblioteca']) ? $data['id_biblioteca'] : (''))) . ' id_espacio_cultural=' . ((isset($data['id_espacio_cultural']) ? $data['id_espacio_cultural'] : (''))));
        if ($hayConflicto) {
            $this->error('Ya existe otra actividad registrada en ese mismo lugar, fecha y hora.');
            return;
        }

        if ($this->model->existeConflictoResponsableHorario($data)) {
            $this->error('El responsable ya tiene otra actividad asignada en esa fecha y hora.');
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
                $this->success('Actividad registrada correctamente.');
                return;
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

    private function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }
   
    
        if (!esAdministrador() && !esCoordinador()) {
            $this->error('No tienes permisos para editar actividades.');
            return;
        }
        $id = intval((isset($_POST['id']) ? $_POST['id'] : (0)));
        if ($id <= 0) {
            $this->error('ID de actividad inválido.');
            return;
        }

        $idBibliotecaUsuario = esAdministrador() ? null : (int) ((guardian_idBibliotecaSesion()));
        $actual = $this->model->obtenerActividadPorId($id, $idBibliotecaUsuario);
        if (!$actual) {
            $this->error('La actividad no existe o no pertenece a tu biblioteca.');
            return;
        }

        $data = $this->collectInput();
        if (esCoordinador()) {
            $data['id_biblioteca'] = $data['tipo_ubicacion'] === 'biblioteca' ? $idBibliotecaUsuario : 0;
        }
        $errorResponsable = $this->completarDatosResponsable($data, $actual);
        if ($errorResponsable !== null) {
            $this->error($errorResponsable);
            return;
        }
        if (esCoordinador() && $data['tipo_ubicacion'] === 'espacio' && $data['responsable'] === '') {
            $this->error('Seleccione un responsable de su biblioteca para una actividad en un espacio cultural.');
            return;
        }
        $estadoActual = strtolower(Validador::normalizarTexto((isset($actual['estado']) ? $actual['estado'] : ('pendiente'))));
        $transicionesEstado = self::TRANSICIONES_ESTADO;
        $estadosPermitidos = isset($transicionesEstado[$estadoActual]) ? $transicionesEstado[$estadoActual] : [$estadoActual];
        if (!in_array($data['estado'], $estadosPermitidos, true)) {
            $this->error("No se puede cambiar una actividad de {$estadoActual} a {$data['estado']}.");
            return;
        }

        $errors = $this->model->validarActividad($data);
        if (!empty($errors)) {
            error_log('[ActividadController::actualizar] Validación falló: ' . implode(' | ', $errors)
                . ' | id=' . $id . ' | Datos recibidos: ' . json_encode($data, JSON_UNESCAPED_UNICODE));
            $this->error(implode(' ', $errors));
            return;
        }

        $hayConflicto = $this->model->existeConflictoHorario($data, $id);
        error_log('[ActividadController::actualizar] existeConflictoHorario=' . ($hayConflicto ? 'true' : 'false')
            . ' | id=' . $id . ' fecha=' . ((isset($data['fecha']) ? $data['fecha'] : (''))) . ' hora=' . ((isset($data['hora']) ? $data['hora'] : ('')))
            . ' id_biblioteca=' . ((isset($data['id_biblioteca']) ? $data['id_biblioteca'] : (''))) . ' id_espacio_cultural=' . ((isset($data['id_espacio_cultural']) ? $data['id_espacio_cultural'] : (''))));
        if ($hayConflicto) {
            $this->error('Ya existe otra actividad registrada en ese mismo lugar, fecha y hora.');
            return;
        }

        if ($this->model->existeConflictoResponsableHorario($data, $id)) {
            $this->error('El responsable ya tiene otra actividad asignada en esa fecha y hora.');
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
                $this->success('Actividad actualizada correctamente.');
                return;
            }
        } catch (Exception $e) {
            error_log('[ActividadController::actualizar] Excepción: ' . $e->getMessage());
            $this->error('No se pudo actualizar la actividad: ' . $e->getMessage());
            return;
        }

        $this->error('No se pudo actualizar la actividad.');
    }

    private function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        $id = intval((isset($_POST['id']) ? $_POST['id'] : (0)));
        if ($id <= 0) {
            $this->error('ID de actividad inválido.');
            return;
        }

        $idBibliotecaUsuario = esAdministrador() ? null : (int) ((guardian_idBibliotecaSesion()));
        if (!$this->model->obtenerActividadPorId($id, $idBibliotecaUsuario)) {
            $this->error('No tienes permisos para eliminar actividades de otra biblioteca.');
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
            $this->success('Actividad eliminada correctamente.');
            return;
        }

        $this->error('No se pudo eliminar la actividad.');
    }

    private function listar()
    {
        
        
        $actividades = $this->model->mostrarActividadesCompletas(esAdministrador() ? null : (int) ((guardian_idBibliotecaSesion())));
        $this->respond(['success' => true, 'data' => $actividades]);
    }

    private function collectInput()
    {
        
        
        
        
        
        
        $fecha = Validador::normalizarTexto((isset($_POST['fecha']) ? $_POST['fecha'] : ((isset($_POST['fechaActividad']) ? $_POST['fechaActividad'] : ('')))));
        $hora = Validador::normalizarTexto((isset($_POST['hora']) ? $_POST['hora'] : ((isset($_POST['horaActividad']) ? $_POST['horaActividad'] : ('')))));
        $diaSemana = Validador::normalizarTexto((isset($_POST['dia_semana']) ? $_POST['dia_semana'] : ((isset($_POST['diaActividad']) ? $_POST['diaActividad'] : ('')))));

        if ($diaSemana === '' && $fecha !== '') {
            $diaSemana = $this->calcularDiaSemana($fecha);
        }

        $descripcion = Validador::normalizarTexto((isset($_POST['descripcion']) ? $_POST['descripcion'] : ((isset($_POST['descripcionActividad']) ? $_POST['descripcionActividad'] : ('')))));
        $objetivo = Validador::normalizarTexto((isset($_POST['objetivo']) ? $_POST['objetivo'] : ((isset($_POST['objetivoEnfoque']) ? $_POST['objetivoEnfoque'] : ('')))));
        $participantes = intval((isset($_POST['participantes']) ? $_POST['participantes'] : ((isset($_POST['cantidadParticipantes']) ? $_POST['cantidadParticipantes'] : (0)))));

        
        
        
        
        
        
        
        $tipoUbicacion = Validador::normalizarTexto((isset($_POST['tipo_ubicacion']) ? $_POST['tipo_ubicacion'] : ('')));
        if ($tipoUbicacion === '') {
            
            
            $tipoUbicacion = intval((isset($_POST['id_espacio_cultural']) ? $_POST['id_espacio_cultural'] : (0))) > 0 ? 'espacio' : 'biblioteca';
        }

        $idBiblioteca = intval((isset($_POST['id_biblioteca']) ? $_POST['id_biblioteca'] : (0)));
        $idEspacioCultural = intval((isset($_POST['id_espacio_cultural']) ? $_POST['id_espacio_cultural'] : (0)));
        if ($tipoUbicacion === 'biblioteca') {
            $idEspacioCultural = 0;
        } elseif ($tipoUbicacion === 'espacio') {
            $idBiblioteca = 0;
        }

        $estadosValidos = ['pendiente', 'confirmada', 'ejecutada', 'cancelada'];
        $estado = Validador::normalizarTexto((isset($_POST['estado']) ? $_POST['estado'] : ('')));
        if (!in_array($estado, $estadosValidos, true)) {
            $estado = 'pendiente';
        }

        return [
            'nombre'               => Validador::normalizarTexto((isset($_POST['nombre']) ? $_POST['nombre'] : ((isset($_POST['tipoActividad']) ? $_POST['tipoActividad'] : (''))))),
            'descripcion'          => $descripcion,
            'objetivo'             => $objetivo !== '' ? $objetivo : 'No definido',
            'participantes'        => $participantes,
            'fecha'                => $fecha,
            'hora'                 => $hora,
            'dia_semana'           => $diaSemana,
            'estado'               => $estado,
            'nivel_impacto'        => Validador::normalizarTexto((isset($_POST['nivel_impacto']) ? $_POST['nivel_impacto'] : ((isset($_POST['nivel__impacto']) ? $_POST['nivel__impacto'] : (''))))),
            'tipo_ubicacion'       => $tipoUbicacion,
            'id_biblioteca'        => $idBiblioteca,
            'municipio_id'         => intval((isset($_POST['municipio_id']) ? $_POST['municipio_id'] : ((isset($_POST['Municipio']) ? $_POST['Municipio'] : (0))))),
            'parroquia'            => Validador::normalizarTexto((isset($_POST['parroquia']) ? $_POST['parroquia'] : (''))),
            'comuna'               => Validador::normalizarTexto((isset($_POST['comuna']) ? $_POST['comuna'] : (''))),
            'id_espacio_cultural'  => $idEspacioCultural,
            'id_tipo_actividad'    => intval((isset($_POST['id_tipo_actividad']) ? $_POST['id_tipo_actividad'] : (0))),
            'id_empleado'          => trim((string)((isset($_POST['id_empleado']) ? $_POST['id_empleado'] : ('')))),
        ];
    }

    private function completarDatosResponsable(array &$data, array $actual = null)
    {
        $idEmpleado = $data['id_empleado'];
        if ($idEmpleado === '') {
            $data['responsable'] = '';
            $data['telefono_responsable'] = '';
            return null;
        }

        if ($idEmpleado === '__actual__' && $actual !== null) {
            $data['responsable'] = (string)((isset($actual['responsable']) ? $actual['responsable'] : ('')));
            $data['telefono_responsable'] = (string)((isset($actual['telefono_responsable']) ? $actual['telefono_responsable'] : ('')));
            return null;
        }

        if (!ctype_digit($idEmpleado) || (int)$idEmpleado <= 0) {
            return 'Seleccione un responsable válido de la lista.';
        }

        $idBibliotecaUsuario = esAdministrador() ? null : (int) ((guardian_idBibliotecaSesion()));
        $empleado = $this->empleadoModel->obtenerEmpleadoPorId((int)$idEmpleado, $idBibliotecaUsuario);
        if (!$empleado) {
            return 'El empleado responsable seleccionado ya no existe.';
        }

        $data['responsable'] = (string)(int)$idEmpleado; // ahora se guarda el id del empleado
        $data['telefono_responsable'] = trim((string)((isset($empleado['telefono']) ? $empleado['telefono'] : (''))));
        return null;
    }

    private function calcularDiaSemana($fecha)
    {
        try {
            $fechaObj = new DateTime($fecha);
            $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            return $dias[(int)$fechaObj->format('w')];
        } catch (Exception $e) {
            return '';
        }
    }

    private function getReturnUrl()
    {
        $returnUrl = trim((isset($_POST['return_url']) ? $_POST['return_url'] : ('')));
        if ($returnUrl === '') {
            $returnUrl = trim((isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ('')));
        }

        if ($returnUrl !== '') {
            $returnUrl = filter_var($returnUrl, FILTER_SANITIZE_URL);
            $parsed = parse_url($returnUrl);
            if ($parsed !== false) {
                $hostMatches = empty($parsed['host']) || $parsed['host'] === ((isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ('')));
                if ($hostMatches) {
                    $path = (isset($parsed['path']) ? $parsed['path'] : ($returnUrl));
                    $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
                    return $path . $query;
                }
            }
        }

        return '../src/main2.php';
    }

    private function success($message, array $extra = [])
    {
        if ($this->shouldReturnJson()) {
            $this->respond(array_merge(['success' => true, 'message' => $message], $extra));
        }

        header('Location: ' . $this->getReturnUrl());
        exit;
    }

    private function error($message)
    {
        if ($this->shouldReturnJson()) {
            $this->respond(['success' => false, 'message' => $message]);
        }

        header('Location: ' . $this->getReturnUrl());
        exit;
    }

    private function shouldReturnJson()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return true;
        }

        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    private function respond(array $payload)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

$controller = new ActividadController();
$controller->dispatch();
