<?php



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

  
    unset($_SESSION['user'], $_SESSION['user_id'], $_SESSION['ultima_actividad'], $_SESSION['creada_en']);

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


function guardian_requerirAdmin(string $modo = 'vista'): void
{
    if (esAdministrador()) {
        return;
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
