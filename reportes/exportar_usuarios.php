<?php
require_once __DIR__ . '/../include/guardian.php';
require_once __DIR__ . '/funciones_pdf.php';

guardian_requerirAdmin('json');

procesarReportePdf(
    'usuario.php',
    'Usuario',
    'mostrarUsuarios',
    'Reporte de usuarios',
    [
        ['etiqueta' => 'ID', 'clave' => 'id'],
        ['etiqueta' => 'Usuario', 'clave' => 'nombre'],
        ['etiqueta' => 'Teléfono', 'clave' => 'telefono'],
        ['etiqueta' => 'Empleado', 'clave' => 'id_empleado_nombre'],
        ['etiqueta' => 'Rol', 'clave' => 'rol', 'tipo' => 'rol'],
    ],
    'reporte_usuarios',
    'portrait'
);