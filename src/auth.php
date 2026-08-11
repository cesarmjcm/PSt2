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

    if ($user) {
        $storedPassword = $user['clave'] ?? '';
        $passwordInfo = password_get_info($storedPassword);
        $validPassword = false;

        if ($passwordInfo['algo'] !== 0) {
            $validPassword = password_verify($password, $storedPassword);
        } else {
            $validPassword = hash_equals($storedPassword, $password);
        }

        if ($validPassword) {
            if ($passwordInfo['algo'] === 0) {
                $userModel->actualizarClave((int) $user['id'], $password);
            }

            $_SESSION['user'] = $user['nombre'];
            $_SESSION['user_id'] = $user['id'];
            $rol = trim(strtolower($user['rol'] ?? 'usuario'));
            $_SESSION['user_rol'] = $rol === 'administrador' ? 'administrador' : 'usuario';
            $redirectTo = !empty($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : 'main2.php';
            unset($_SESSION['redirect_after_login']);
            $ok = true;
            $message = 'Autenticación correcta.';
        }
    }
}

echo json_encode(['success' => $ok, 'message' => $message, 'redirect' => $ok ? $redirectTo : null]);

?>
