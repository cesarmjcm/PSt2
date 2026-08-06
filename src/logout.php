<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$_SESSION = [];


if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}


if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}


$aceptaJson = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;

if ($aceptaJson) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => true, 'message' => 'Sesión cerrada correctamente.', 'redirect' => 'login.php']);
    exit;
}

header('Location: login.php');
exit;
