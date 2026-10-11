<?php
require_once __DIR__ . '/../include/guardian.php';
require_once __DIR__ . '/funciones_pdf.php';

procesarReportePdf(
    'institucion.php',
    'Institucion',
    'mostrarInstituciones',
    'Reporte de instituciones',
    [
        ['etiqueta' => 'ID', 'clave' => 'id'],
        ['etiqueta' => 'Institución', 'clave' => 'nombre'],
        ['etiqueta' => 'RIF', 'clave' => 'rif'],
        ['etiqueta' => 'Municipio', 'clave' => 'id_municipio_nombre'],
        ['etiqueta' => 'Correo', 'clave' => 'correo'],
        ['etiqueta' => 'Dirección', 'clave' => 'direccion'],
    ],
    'reporte_instituciones'
);
