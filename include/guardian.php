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
    define('GUARDIAN_LOGIN_PATH', 'login.php');
}


if (!defined('GUARDIAN_TIEMPO_INACTIVIDAD_MIN')) {
    define('GUARDIAN_TIEMPO_INACTIVIDAD_MIN', 30);
}


function guardian_redirigirALogin(string $motivo = ''): void
{
    $urlActual = $_SERVER['REQUEST_URI'] ?? null;

  
    unset($_SESSION['user'], $_SESSION['user_id'], $_SESSION['ultima_actividad'], $_SESSION['creada_en']);

    if ($urlActual) {
        $_SESSION['redirect_after_login'] = $urlActual;
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


/**
 * Devuelve true si el usuario autenticado tiene rol de administrador.
 * El rol debe haberse guardado en $_SESSION['user_rol'] durante el login
 * (ver nota en login.php).
 */
function esAdministrador(): bool
{
    return ($_SESSION['user_rol'] ?? '') === 'administrador';
}

/**
 * Corta la ejecución si el usuario autenticado no es administrador.
 * Pensada para usarse al inicio de vistas (redirige) o de controladores
 * AJAX (responde JSON y termina), según $modo.
 */
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
