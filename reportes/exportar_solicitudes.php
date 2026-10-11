<?php
require_once __DIR__ . '/../include/guardian.php';
require_once __DIR__ . '/funciones_pdf.php';

procesarReportePdf(
    'solicitud.php',
    'Solicitud',
    'mostrarSolicitudes',
    'Reporte de solicitudes',
    [
        ['etiqueta' => 'ID', 'clave' => 'id'],
        ['etiqueta' => 'Institución', 'clave' => 'institucion'],
        ['etiqueta' => 'Fecha solicitada', 'clave' => 'fecha_solicitud', 'tipo' => 'fecha'],
        ['etiqueta' => 'Fecha de registro', 'clave' => 'fecha_registro', 'tipo' => 'fecha'],
        ['etiqueta' => 'Hora', 'clave' => 'hora_solicitud'],
        ['etiqueta' => 'Lugar', 'clave' => 'lugar'],
        ['etiqueta' => 'Responsable', 'clave' => 'responsable'],
        ['etiqueta' => 'Participantes', 'clave' => 'participantes'],
        ['etiqueta' => 'Descripción', 'clave' => 'descripcion'],
        ['etiqueta' => 'Estado', 'clave' => 'estado'],
    ],
    'reporte_solicitudes',
    'landscape',
    true
);
