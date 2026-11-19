<?php
require_once __DIR__ . '/../include/guardian.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planificación de Actividad</title>
    <link rel="stylesheet" href="./css/main.css?v=3">
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
                    <?php
                    $menuMaestros = [
                        'municipio' => 'Municipios',
                        'parroquia' => 'Parroquias',
                        'comuna' => 'Comunas',
                        'biblioteca' => 'Bibliotecas',
                        'institucion' => 'Instituciones',
                        'cargo' => 'Cargos',
                        'empleado' => 'Empleados',
                        'nv_act' => 'Niveles de impacto',
                        'espacio' => 'Espacios culturales',
                        'tipo_actividad' => 'Tipos de actividad',
                    ];
                    foreach ($menuMaestros as $maestroKey => $maestroLabel):
                        if (puedeVisualizarMaestro($maestroKey)):
                    ?>
                        <li><a href="./maestro.php?tabla=<?php echo urlencode($maestroKey); ?>"><?php echo htmlspecialchars($maestroLabel, ENT_QUOTES, 'UTF-8'); ?></a></li>
                    <?php
                        endif;
                    endforeach;
                    ?>
                    
                    
                    
                </ul>
            </nav>
        </aside>

        <main class="page-content">
            <?php include '../include/tablamini.php'; ?>
            <?php include '../include/modal_editar.php'; ?>
        </main>
    </div>
    <script src="./js/utilidades.js"></script>
    <script src="./js/validaciones.js"></script>
    <script src="./js/header.js?v=<?= filemtime(__DIR__ . '/js/header.js') ?>"></script>
    <script src="./js/index.js"></script>
    <script src="./js/reportes.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const botonExportar = document.getElementById('btnExportarActividades');
            const botonExportarExcel = document.getElementById('btnExportarExcelActividades');
            const buscador = document.getElementById('buscarActividad');
            if (!buscador) return;

            if (botonExportar) botonExportar.addEventListener('click', () => {
                const termino = buscador.value.trim();
                const urlReporte = '../reportes/exportar_actividades.php' +
                    (termino ? '?q=' + encodeURIComponent(termino) : '');
                abrirReportePdf(
                    urlReporte,
                    'Reporte de actividades',
                    botonExportar,
                    (mensaje) => window.alert(mensaje)
                );
            });
            if (botonExportarExcel) botonExportarExcel.addEventListener('click', () => {
                const termino = buscador.value.trim();
                const urlReporte = '../reportes/exportar_excel.php?reporte=actividad' +
                    (termino ? '&q=' + encodeURIComponent(termino) : '');
                abrirReporteExcel(
                    urlReporte,
                    'Reporte de actividades',
                    botonExportarExcel,
                    (mensaje) => window.alert(mensaje)
                );
            });
        });
    </script>
</body>
</html>
