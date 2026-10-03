<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../modelos/usuario.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../modelos/modelo_bitacora.php';

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

            $rol = trim(strtolower($user['rol'] ?? 'usuario'));
            $rol = $rol === 'administrador' ? 'administrador' : 'usuario';
            $idBiblioteca = (int) ($user['id_biblioteca'] ?? 0);
            if ($rol !== 'administrador' && ((int) ($user['id_empleado'] ?? 0) <= 0 || $idBiblioteca <= 0)) {
                $message = 'El usuario no tiene un empleado vinculado a una biblioteca.';
            } else {
                session_regenerate_id(true);
                $_SESSION['user'] = $user['nombre'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_rol'] = $rol;
                $_SESSION['id_empleado'] = (int) ($user['id_empleado'] ?? 0);
                $_SESSION['id_biblioteca'] = $idBiblioteca > 0 ? $idBiblioteca : null;
                $redirectTo = !empty($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : 'main2.php';
                unset($_SESSION['redirect_after_login']);
                $ok = true;
                $message = 'Autenticación correcta.';

                $conex = Conexion::conectar();
                registrar_bitacora($conex, $_SESSION['user_id'], 'Login', 'Usuario', 'Inicio de sesión: ' . $_SESSION['user']);
            }
        }
    }
}

echo json_encode(['success' => $ok, 'message' => $message, 'redirect' => $ok ? $redirectTo : null]);

?>
