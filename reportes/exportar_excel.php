<?php
require_once __DIR__ . '/../include/guardian.php';
require_once __DIR__ . '/funciones_excel.php';

$reportes = [
    'municipio' => [
        'modelo' => 'municipio.php', 'clase' => 'Municipio', 'metodo' => 'mostrarMunicipios', 'nombre' => 'reporte_municipios',
        'columnas' => [
            ['etiqueta' => 'ID', 'clave' => 'id'],
            ['etiqueta' => 'Municipio', 'clave' => 'nombre'],
        ],
    ],
    'parroquia' => [
        'modelo' => 'parroquia.php', 'clase' => 'Parroquia', 'metodo' => 'mostrarParroquias', 'nombre' => 'reporte_parroquias',
        'columnas' => [
            ['etiqueta' => 'ID', 'clave' => 'id'],
            ['etiqueta' => 'Parroquia', 'clave' => 'nombre'],
            ['etiqueta' => 'Municipio', 'clave' => 'id_municipio_nombre'],
        ],
    ],
    'comuna' => [
        'modelo' => 'comuna.php', 'clase' => 'Comuna', 'metodo' => 'mostrarComunas', 'nombre' => 'reporte_comunas',
        'columnas' => [
            ['etiqueta' => 'ID', 'clave' => 'id'],
            ['etiqueta' => 'Comuna', 'clave' => 'nombre'],
            ['etiqueta' => 'Parroquia', 'clave' => 'id_parroquia_nombre'],
        ],
    ],
    'biblioteca' => [
        'modelo' => 'biblioteca.php', 'clase' => 'Biblioteca', 'metodo' => 'mostrarBibliotecas', 'nombre' => 'reporte_bibliotecas',
        'columnas' => [
            ['etiqueta' => 'ID', 'clave' => 'id'],
            ['etiqueta' => 'Biblioteca', 'clave' => 'nombre'],
            ['etiqueta' => 'Parroquia', 'clave' => 'id_parroquia_nombre'],
            ['etiqueta' => 'Correo', 'clave' => 'correo'],
            ['etiqueta' => 'Redes sociales', 'clave' => 'redes_sociales'],
            ['etiqueta' => 'Dirección', 'clave' => 'direccion'],
        ],
    ],
    'institucion' => [
        'modelo' => 'institucion.php', 'clase' => 'Institucion', 'metodo' => 'mostrarInstituciones', 'nombre' => 'reporte_instituciones',
        'filtrar_biblioteca' => true,
        'columnas' => [
            ['etiqueta' => 'ID', 'clave' => 'id'],
            ['etiqueta' => 'Institución', 'clave' => 'nombre'],
            ['etiqueta' => 'RIF', 'clave' => 'rif'],
            ['etiqueta' => 'Municipio', 'clave' => 'id_municipio_nombre'],
            ['etiqueta' => 'Correo', 'clave' => 'correo'],
            ['etiqueta' => 'Dirección', 'clave' => 'direccion'],
        ],
    ],
    'cargo' => [
        'modelo' => 'cargo.php', 'clase' => 'Cargo', 'metodo' => 'mostrarCargos', 'nombre' => 'reporte_cargos',
        'admin' => true,
        'columnas' => [
            ['etiqueta' => 'ID', 'clave' => 'id'],
            ['etiqueta' => 'Nombre del cargo', 'clave' => 'nombre'],
            ['etiqueta' => 'Descripción', 'clave' => 'descripcion'],
        ],
    ],
    'empleado' => [
        'modelo' => 'empleado.php', 'clase' => 'Empleado', 'metodo' => 'mostrarEmpleados', 'nombre' => 'reporte_empleados',
        'filtrar_biblioteca' => true,
        'columnas' => [
            ['etiqueta' => 'Cédula', 'clave' => 'cedula'],
            ['etiqueta' => 'Nombre y apellido', 'claves' => ['nombre', 'apellido'], 'separador' => ' '],
            ['etiqueta' => 'Teléfono', 'clave' => 'telefono'],
            ['etiqueta' => 'Género', 'clave' => 'genero', 'tipo' => 'genero'],
            ['etiqueta' => 'Edad', 'clave' => 'edad'],
            ['etiqueta' => 'Cargo', 'clave' => 'id_cargo_nombre'],
            ['etiqueta' => 'Biblioteca', 'clave' => 'id_biblioteca_nombre'],
            ['etiqueta' => 'Inicio cargo', 'clave' => 'fecha_inicio_cargo', 'tipo' => 'fecha'],
            ['etiqueta' => 'Fin cargo', 'clave' => 'fecha_fin_cargo', 'tipo' => 'fecha'],
        ],
    ],
    'nv_act' => [
        'modelo' => 'nv_act.php', 'clase' => 'NivelImpacto', 'metodo' => 'mostrarNiveles', 'nombre' => 'reporte_niveles_impacto',
        'columnas' => [
            ['etiqueta' => 'ID', 'clave' => 'id'],
            ['etiqueta' => 'Nivel de impacto', 'clave' => 'nombre_impacto'],
        ],
    ],
    'espacio' => [
        'modelo' => 'espacio.php', 'clase' => 'Espacio', 'metodo' => 'mostrarEspacios', 'nombre' => 'reporte_espacios',
        'columnas' => [
            ['etiqueta' => 'ID', 'clave' => 'id'],
            ['etiqueta' => 'Espacio cultural', 'clave' => 'nombre'],
            ['etiqueta' => 'Parroquia', 'clave' => 'id_parroquia_nombre'],
            ['etiqueta' => 'Capacidad', 'clave' => 'capacidad'],
            ['etiqueta' => 'Dirección', 'clave' => 'direccion'],
            ['etiqueta' => 'Método de contacto', 'clave' => 'metodo_contactar'],
        ],
    ],
    'tipo_actividad' => [
        'modelo' => 'tipo_actividad.php', 'clase' => 'TipoActividad', 'metodo' => 'mostrarTipos', 'nombre' => 'reporte_tipos_actividad',
        'columnas' => [
            ['etiqueta' => 'ID', 'clave' => 'id'],
            ['etiqueta' => 'Tipo de actividad', 'clave' => 'nombre'],
            ['etiqueta' => 'Descripción', 'clave' => 'descripcion'],
        ],
    ],
    'actividad' => [
        'modelo' => 'actividad.php', 'clase' => 'Actividad', 'metodo' => 'mostrarActividadesCompletas', 'nombre' => 'reporte_actividades',
        'filtrar_biblioteca' => true,
        'columnas' => [
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
    ],
    'solicitud' => [
        'modelo' => 'solicitud.php', 'clase' => 'Solicitud', 'metodo' => 'mostrarSolicitudes', 'nombre' => 'reporte_solicitudes',
        'filtrar_biblioteca' => true,
        'columnas' => [
            ['etiqueta' => 'ID', 'clave' => 'id'],
            ['etiqueta' => 'Institución', 'clave' => 'institucion'],
            ['etiqueta' => 'Nombre de la actividad', 'clave' => 'nombre_actividad'],
            ['etiqueta' => 'Fecha solicitada', 'clave' => 'fecha_solicitud', 'tipo' => 'fecha'],
            ['etiqueta' => 'Fecha de registro', 'clave' => 'fecha_registro', 'tipo' => 'fecha'],
            ['etiqueta' => 'Hora', 'clave' => 'hora_solicitud', 'tipo' => 'hora'],
            ['etiqueta' => 'Lugar', 'clave' => 'lugar'],
            ['etiqueta' => 'Responsable', 'clave' => 'responsable'],
            ['etiqueta' => 'Participantes', 'clave' => 'participantes'],
            ['etiqueta' => 'Descripción', 'clave' => 'descripcion'],
            ['etiqueta' => 'Estado', 'clave' => 'estado'],
        ],
    ],
    'usuario' => [
        'modelo' => 'usuario.php', 'clase' => 'Usuario', 'metodo' => 'mostrarUsuarios', 'nombre' => 'reporte_usuarios',
        'admin' => true,
        'columnas' => [
            ['etiqueta' => 'ID', 'clave' => 'id'],
            ['etiqueta' => 'Usuario', 'clave' => 'nombre'],
            ['etiqueta' => 'Teléfono', 'clave' => 'telefono'],
            ['etiqueta' => 'Empleado', 'clave' => 'id_empleado_nombre'],
            ['etiqueta' => 'Rol', 'clave' => 'rol'],
        ],
    ],
];

$reporte = isset($_GET['reporte']) && is_string($_GET['reporte']) ? $_GET['reporte'] : '';
if (!isset($reportes[$reporte])) {
    responderReporteExcel(['success' => false, 'message' => 'El reporte solicitado no es válido.'], 400);
}
if (!empty($reportes[$reporte]['admin']) && !esAdministrador()) {
    guardian_requerirAdmin('json');
}

procesarReporteExcel($reportes[$reporte]);
