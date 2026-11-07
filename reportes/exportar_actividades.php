<?php
require_once __DIR__ . '/../include/guardian.php';
require_once __DIR__ . '/funciones_pdf.php';

procesarReportePdf(
    'actividad.php',
    'Actividad',
    'mostrarActividadesCompletas',
    'Reporte de actividades',
    [
        ['etiqueta' => 'ID', 'clave' => 'id'],
        ['etiqueta' => 'Actividad', 'clave' => 'nombre'],
        ['etiqueta' => 'Tipo', 'clave' => 'tipo_actividad'],
        ['etiqueta' => 'Fecha', 'clave' => 'fecha', 'tipo' => 'fecha'],
        ['etiqueta' => 'Hora', 'clave' => 'hora', 'tipo' => 'hora'],
        [
            'etiqueta' => 'Ubicación',
            'partes' => [
                ['etiqueta' => 'Lugar', 'claves' => ['biblioteca', 'espacio_cultural']],
                ['etiqueta' => 'Municipio', 'clave' => 'municipio'],
                ['etiqueta' => 'Parroquia', 'clave' => 'parroquia'],
                ['etiqueta' => 'Comuna', 'clave' => 'comuna'],
            ],
        ],
        [
            'etiqueta' => 'Descripción',
            'partes' => [
                ['etiqueta' => 'Descripción', 'clave' => 'descripcion'],
                ['etiqueta' => 'Objetivo', 'clave' => 'objetivo'],
            ],
        ],
        ['etiqueta' => 'Participantes', 'clave' => 'participantes'],
        ['etiqueta' => 'Responsable', 'claves' => ['responsable', 'telefono_responsable'], 'separador' => ' | '],
        ['etiqueta' => 'Nivel de impacto', 'clave' => 'nivel_impacto'],
        ['etiqueta' => 'Estado', 'clave' => 'estado'],
    ],
    'reporte_actividades',
    'landscape',
    true
);
