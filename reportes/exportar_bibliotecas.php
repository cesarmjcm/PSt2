<?php
require_once __DIR__ . '/../include/guardian.php';
require_once __DIR__ . '/funciones_pdf.php';

procesarReportePdf(
    'biblioteca.php',
    'Biblioteca',
    'mostrarBibliotecas',
    'Reporte de bibliotecas',
    [
        ['etiqueta' => 'ID', 'clave' => 'id'],
        ['etiqueta' => 'Biblioteca', 'clave' => 'nombre'],
        ['etiqueta' => 'Parroquia', 'clave' => 'id_parroquia_nombre'],
        ['etiqueta' => 'Correo', 'clave' => 'correo'],
        ['etiqueta' => 'Redes sociales', 'clave' => 'redes_sociales'],
        ['etiqueta' => 'Dirección', 'clave' => 'direccion'],
    ],
    'reporte_bibliotecas'
);
