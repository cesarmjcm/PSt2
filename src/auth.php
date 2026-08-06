<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../modelos/usuario.php';

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

$ok = false;
$message = 'Usuario o contraseña incorrectos.';

$redirectTo = null;

if ($username !== '' && $password !== '') {
    $userModel = new Usuario();
    $user = $userModel->obtenerUsuarioPorNombre($username);

    if ($user && password_verify($password, $user['clave'])) {
        $_SESSION['user'] = $user['nombre'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_rol'] = $user['rol'] ?? 'usuario';
        $redirectTo = !empty($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : 'main2.php';
        unset($_SESSION['redirect_after_login']);
        $ok = true;
        $message = 'Autenticación correcta.';
    }
}

echo json_encode(['success' => $ok, 'message' => $message, 'redirect' => $ok ? $redirectTo : null]);

?>
