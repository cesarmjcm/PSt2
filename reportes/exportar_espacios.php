<?php
require_once __DIR__ . '/../include/guardian.php';
require_once __DIR__ . '/funciones_pdf.php';

procesarReportePdf(
    'espacio.php',
    'Espacio',
    'mostrarEspacios',
    'Reporte de espacios culturales',
    [
        ['etiqueta' => 'ID', 'clave' => 'id'],
        ['etiqueta' => 'Espacio cultural', 'clave' => 'nombre'],
        ['etiqueta' => 'Capacidad', 'clave' => 'capacidad'],
        ['etiqueta' => 'Dirección', 'clave' => 'direccion'],
        ['etiqueta' => 'Método de contacto', 'clave' => 'metodo_contactar'],
    ],
    'reporte_espacios'
);
