<?php
require_once __DIR__ . '/../include/guardian.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planificación de Actividad</title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/fontawesome-all.min.css">
    <link rel="icon" type="image/png" href="./assets/icon__icey.png">
</head>
<body>
    <?php include '../include/header.php'; ?>

    <div class="page-layout">
        <aside class="sidebar-menu" aria-label="Menú de maestros">
            <h2>Panel</h2>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="./maestro.php?tabla=municipio">Municipios</a></li>
                    <li><a href="./maestro.php?tabla=parroquia">Parroquias</a></li>
                    <li><a href="./maestro.php?tabla=comuna">Comunas</a></li>
                    <li><a href="./maestro.php?tabla=biblioteca">Bibliotecas</a></li>
                    <li></li><a href="./maestro.php?tabla=institucion">Instituciones</a></li>
                    <?php if (($_SESSION['user_rol'] ?? '') === 'administrador'): ?>
    <li><a href="./maestro.php?tabla=cargo">Cargos</a></li>
<?php endif; ?>
                    <li><a href="./maestro.php?tabla=empleado">Empleados</a></li>
                    <li><a href="./maestro.php?tabla=nv_act">Niveles de impacto</a></li>
                    <li><a href="./maestro.php?tabla=espacio">Espacios culturales</a></li>
                    <li><a href="./maestro.php?tabla=tipo_actividad">Tipos de actividad</a></li>
                    
                    
                    
                </ul>
            </nav>
        </aside>

        <main class="page-content">
            <?php include '../include/tablamini.php'; ?>
            <?php include '../include/modal_editar.php'; ?>
        </main>
    </div>

    <script src="./js/app.js"></script>
</body>
</html>
