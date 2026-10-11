<?php
require_once __DIR__ . '/../include/guardian.php';
require_once __DIR__ . '/funciones_pdf.php';

procesarReportePdf(
    'nv_act.php',
    'NivelImpacto',
    'mostrarNiveles',
    'Reporte de niveles de impacto',
    [
        ['etiqueta' => 'ID', 'clave' => 'id'],
        ['etiqueta' => 'Nivel de impacto', 'clave' => 'nombre_impacto'],
    ],
    'reporte_niveles_impacto'
);
