<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../include/guardian.php';
require_once __DIR__ . '/../modelos/modelo_bitacora.php';

$conex = Conexion::conectar();




guardian_requerirAdmin();


$filtros = [
    'id_usu'      => (isset($_GET['id_usu']) ? $_GET['id_usu'] : ('')),
    'accion'      => (isset($_GET['accion']) ? $_GET['accion'] : ('')),
    'fecha_desde' => (isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : ('')),
    'fecha_hasta' => (isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : ('')),
];



$registros = obtener_bitacora($conex, $filtros);
$usuarios  = obtener_usuarios_para_filtro($conex);

require_once __DIR__ . '/../src/bitacora.php';
?>
