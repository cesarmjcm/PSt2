
    <div class="page-layout">
        <aside class="sidebar-menu">
            <h2>Maestros</h2>
            <nav class="sidebar-nav">
                <?php foreach ($maestros as $key => $label): ?>
                    <a href="./maestro.php?tabla=<?php echo urlencode($key); ?>"<?php echo $key === $tabla ? ' class="active"' : ''; ?>><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></a>
                <?php endforeach; ?>
            </nav>
        </aside>