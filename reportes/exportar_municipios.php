<?php
require_once __DIR__ . '/../include/guardian.php';
require_once __DIR__ . '/funciones_pdf.php';

procesarReportePdf(
    'municipio.php',
    'Municipio',
    'mostrarMunicipios',
    'Reporte de municipios',
    [
        ['etiqueta' => 'ID', 'clave' => 'id'],
        ['etiqueta' => 'Municipio', 'clave' => 'nombre'],
    ],
    'reporte_municipios'
);
