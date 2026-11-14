<?php
require_once __DIR__ . '/../include/guardian.php';
require_once __DIR__ . '/funciones_pdf.php';

procesarReportePdf(
    'tipo_actividad.php',
    'TipoActividad',
    'mostrarTipos',
    'Reporte de tipos de actividad',
    [
        ['etiqueta' => 'ID', 'clave' => 'id'],
        ['etiqueta' => 'Tipo de actividad', 'clave' => 'nombre'],
        ['etiqueta' => 'Descripción', 'clave' => 'descripcion'],
    ],
    'reporte_tipos_actividad'
);
