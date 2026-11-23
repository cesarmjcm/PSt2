<header>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$perfilUsuario = (isset($_SESSION['user_profile']) ? $_SESSION['user_profile'] : (null));
if (
    !is_array($perfilUsuario)
    || (int) ((isset($perfilUsuario['user_id']) ? $perfilUsuario['user_id'] : (0))) !== (int) ((isset($_SESSION['user_id']) ? $_SESSION['user_id'] : (0)))
) {
    $perfilUsuario = null;
}
if (!is_array($perfilUsuario) && !empty($_SESSION['user_id'])) {
    require_once __DIR__ . '/../modelos/usuario.php';
    $perfilUsuario = (new Usuario())->obtenerPerfilUsuario((int) $_SESSION['user_id']);
    $_SESSION['user_profile'] = is_array($perfilUsuario)
        ? $perfilUsuario
        : ['user_id' => (int) $_SESSION['user_id']];
}
$perfilUsuario = is_array($perfilUsuario) ? $perfilUsuario : [];

$nombreUsuario = (string) ((isset($_SESSION['user']) ? $_SESSION['user'] : ('Usuario')));
$nombreEmpleado = trim((string) ((isset($perfilUsuario['empleado']) ? $perfilUsuario['empleado'] : (''))));
$nombreBiblioteca = trim((string) ((isset($perfilUsuario['biblioteca']) ? $perfilUsuario['biblioteca'] : (''))));
$etiquetasRoles = [
    'administrador' => 'Administrador',
    'coordinador' => 'Coordinador',
    'usuario' => 'Usuario',
];
$rol = isset($_SESSION['user_rol']) ? $_SESSION['user_rol'] : 'usuario';
$rolUsuario = isset($etiquetasRoles[$rol]) ? $etiquetasRoles[$rol] : 'Usuario';
$escapar = static function ($valor) {
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
};
$current = basename((isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : ('')));
$inicioActive = in_array($current, ['main2.php', 'main.html'], true);
$planActive = $current === 'index.php';
$bitacoraActive = $current === 'bitacora.php';
$esAdmin = isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'administrador';
?>
<nav>
    <img src="./assets/image.png" alt="Logo" class="nav__logo">
    <div class="nav__links">
        <a href="main2.php" class="nav__link<?= $inicioActive ? ' active' : '' ?>">Inicio</a>
        <a href="index.php" class="nav__link<?= $planActive ? ' active' : '' ?>">Planificación</a>
        <a href="solicitud.php" class="nav__link<?= $current === 'solicitud.php' ? ' active' : '' ?>">Solicitudes</a>
        <?php if ($esAdmin): ?>
            <a href="bitacora.php" class="nav__link<?= $bitacoraActive ? ' active' : '' ?>">Bitácora</a>
          
        <?php endif; ?>
        <a href="respaldos.php" class="nav__link<?= $current === 'respaldos.php' ? ' active' : '' ?>">Respaldo BD</a>
                <div class="nav__user">
                    <button id="userMenuButton" class="nav__user-btn" aria-haspopup="true" aria-expanded="false" title="Usuario" type="button">
                        <i class="fas fa-user-circle"></i>
                    </button>
                    <div id="userMenu" class="nav__user-menu" hidden>
                        <div class="nav__user-info">
                            <strong class="nav__user-name"><?= $escapar($nombreUsuario) ?></strong>
                            <p><span>Nombre</span><?= $escapar($nombreEmpleado !== '' ? $nombreEmpleado : 'No disponible') ?></p>
                            <p><span>Biblioteca</span><?= $escapar($nombreBiblioteca !== '' ? $nombreBiblioteca : 'No asignada') ?></p>
                            <p><span>Rol</span><?= $escapar($rolUsuario) ?></p>
                        </div>
                        <a href="configUser.php" id="configBtn">Configuración</a>
                        <a href="logout.php" id="logoutBtn">Cerrar sesión</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
