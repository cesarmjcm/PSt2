<?php
// BITACORA_CONTR.PHP - Controlador de la página de historial (solo administrador)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../include/guardian.php';
require_once __DIR__ . '/../modelos/modelo_bitacora.php';

$conex = Conexion::conectar();

// --- Control de acceso: solo administrador puede ver la bitácora ---
// Si tu guardian.php ya expone una función tipo requerirRol('administrador'),
// reemplaza este bloque por esa llamada para mantener todo centralizado.
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
    header('Location: main2.php');
    exit;
}

// --- Filtros desde la URL (GET) ---
$filtros = [
    'id_usu'      => $_GET['id_usu']      ?? '',
    'accion'      => $_GET['accion']      ?? '',
    'fecha_desde' => $_GET['fecha_desde'] ?? '',
    'fecha_hasta' => $_GET['fecha_hasta'] ?? '',
];

$registros = obtener_bitacora($filtros);
$usuarios  = obtener_usuarios_para_filtro();

require_once __DIR__ . '/../src/bitacora.php';
?>
