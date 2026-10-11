<?php
require_once __DIR__ . '/../include/guardian.php';
require_once __DIR__ . '/funciones_pdf.php';

procesarReportePdf(
    'parroquia.php',
    'Parroquia',
    'mostrarParroquias',
    'Reporte de parroquias',
    [
        ['etiqueta' => 'ID', 'clave' => 'id'],
        ['etiqueta' => 'Parroquia', 'clave' => 'nombre'],
        ['etiqueta' => 'Municipio', 'clave' => 'id_municipio_nombre'],
    ],
    'reporte_parroquias'
);
