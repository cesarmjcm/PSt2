<header>
        <?php
        $current = basename($_SERVER['PHP_SELF'] ?? '');
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $inicioActive = stripos($uri, 'main2') !== false || stripos($current, 'main2') !== false;
        $planActive = stripos($uri, 'index.php') !== false || stripos($uri, 'main.html') !== false || stripos($current, 'index.php') !== false;
        ?>
        <nav>
            <img src="./assets/image.png" alt="Logo" class="nav__logo">
            <div class="nav__links">
                <a href="main2.php" class="nav__link<?= $inicioActive ? ' active' : '' ?>">Inicio</a>
                <a href="index.php" class="nav__link<?= $planActive ? ' active' : '' ?>">Planificación</a>
                <input type="text" class="nav__search" placeholder="Buscar...">
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