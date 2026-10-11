<?php
require_once __DIR__ . '/../include/guardian.php';
require_once __DIR__ . '/funciones_pdf.php';

procesarReportePdf(
    'comuna.php',
    'Comuna',
    'mostrarComunas',
    'Reporte de comunas',
    [
        ['etiqueta' => 'ID', 'clave' => 'id'],
        ['etiqueta' => 'Comuna', 'clave' => 'nombre'],
        ['etiqueta' => 'Parroquia', 'clave' => 'id_parroquia_nombre'],
    ],
    'reporte_comunas'
);
