<header>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current = basename($_SERVER['PHP_SELF'] ?? '');
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
        
                <div class="nav__user">
                    <button id="userMenuButton" class="nav__user-btn" aria-haspopup="true" aria-expanded="false" title="Usuario" type="button">
                        <i class="fas fa-user-circle"></i>
                    </button>
                    <div id="userMenu" class="nav__user-menu" hidden>
                        <a href="configUser.php" id="configBtn">Configuración</a>
                        
                    </div>
                </div>
            </div>
        </nav>
    </header>
