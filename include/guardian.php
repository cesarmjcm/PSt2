<?php

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../modelos/modelo_bitacora.php';



if (session_status() === PHP_SESSION_NONE) {
  
    $cookieParams = [
        'lifetime' => 0,             
        'path'     => '/',
        'httponly' => true,          
        'samesite' => 'Lax',           
    ];


    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        $cookieParams['secure'] = true;
    }

    session_set_cookie_params($cookieParams);
    session_start();
}


if (!defined('GUARDIAN_LOGIN_PATH')) {
    $scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $projectPath = dirname(dirname($scriptPath));
    define('GUARDIAN_LOGIN_PATH', rtrim($projectPath, '/') . '/src/login.php');
}


if (!defined('GUARDIAN_TIEMPO_INACTIVIDAD_MIN')) {
    define('GUARDIAN_TIEMPO_INACTIVIDAD_MIN', 30);
}


function guardian_redirigirALogin(string $motivo = ''): void
{
    $urlActual = $_SERVER['REQUEST_URI'] ?? null;

    $idUsuario = !empty($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    registrar_bitacora(
        Conexion::conectar(),
        $idUsuario,
        'Acceso Denegado',
        'Seguridad',
        'Intento de acceso sin autenticación a: ' . ($urlActual ?? 'recurso desconocido')
    );

  
    unset($_SESSION['user'], $_SESSION['user_id'], $_SESSION['id_empleado'], $_SESSION['id_biblioteca'], $_SESSION['ultima_actividad'], $_SESSION['creada_en']);

    $esControlador = $urlActual && strpos(parse_url($urlActual, PHP_URL_PATH) ?? '', '/controladores/') !== false;
    if ($urlActual && !$esControlador) {
        $_SESSION['redirect_after_login'] = $urlActual;
    } else {
        unset($_SESSION['redirect_after_login']);
    }

    $destino = GUARDIAN_LOGIN_PATH;
    if ($motivo !== '') {
        $destino .= (strpos($destino, '?') === false ? '?' : '&') . 'motivo=' . urlencode($motivo);
    }

    header('Location: ' . $destino);
    exit;
}


if (empty($_SESSION['user_id'])) {
    guardian_redirigirALogin('sesion_requerida');
}



function esAdministrador(): bool
{
    $rol = trim(strtolower($_SESSION['user_rol'] ?? ''));
    return $rol === 'administrador';
}

function guardian_idBibliotecaSesion(): ?int
{
    if (esAdministrador()) {
        return null;
    }

    static $resuelto = false;
    static $idBibliotecaResuelto = 0;
    if ($resuelto) {
        return $idBibliotecaResuelto;
    }

    $idUsuario = (int) ($_SESSION['user_id'] ?? 0);
    if ($idUsuario <= 0) {
        return 0;
    }

    $stmt = Conexion::conectar()->prepare(
        'SELECT e.id, e.id_biblioteca
         FROM usuario u
         JOIN empleado e ON e.id = u.id_empleado
         WHERE u.id = ?'
    );
    $stmt->execute([$idUsuario]);
    $empleado = $stmt->fetch(PDO::FETCH_ASSOC);
    $idBiblioteca = (int) ($empleado['id_biblioteca'] ?? 0);
    if ($idBiblioteca > 0) {
        $_SESSION['id_biblioteca'] = $idBiblioteca;
        $_SESSION['id_empleado'] = (int) $empleado['id'];
        $idBibliotecaResuelto = $idBiblioteca;
        $resuelto = true;
        return $idBiblioteca;
    }

    unset($_SESSION['id_biblioteca'], $_SESSION['id_empleado']);
    $resuelto = true;
    return 0;
}


function guardian_requerirAdmin(string $modo = 'vista'): void
{
    if (esAdministrador()) {
        return;
    }

    if (!empty($_SESSION['user_id'])) {
        $conexBitacora = Conexion::conectar();
        $recurso = $_SERVER['REQUEST_URI'] ?? ($_SERVER['SCRIPT_NAME'] ?? 'recurso desconocido');
        registrar_bitacora(
            $conexBitacora,
            $_SESSION['user_id'],
            'Acceso Denegado',
            'Seguridad',
            'Intento de acceso sin permisos de administrador a: ' . $recurso
        );
    }

    if ($modo === 'json') {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'No tienes permisos para realizar esta acción.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    http_response_code(403);
    include __DIR__ . '/header.php';
    echo '<main class="page-content"><p>No tienes permisos para acceder a esta página.</p></main>';
    exit;
}


$ahora = time();
$limiteSegundos = GUARDIAN_TIEMPO_INACTIVIDAD_MIN * 60;

if (!empty($_SESSION['ultima_actividad']) && ($ahora - $_SESSION['ultima_actividad']) > $limiteSegundos) {
    guardian_redirigirALogin('sesion_expirada');
}

$_SESSION['ultima_actividad'] = $ahora;


if (empty($_SESSION['creada_en'])) {
    $_SESSION['creada_en'] = $ahora;
}

if (($ahora - $_SESSION['creada_en']) > 300) { 
    session_regenerate_id(true);
    $_SESSION['creada_en'] = $ahora;
}


?>
