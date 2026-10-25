<?php
require_once __DIR__ . '/../include/guardian.php';      // exige sesión iniciada
require_once __DIR__ . '/../modelos/respaldosbd.php';
require_once __DIR__ . '/../modelos/modelo_bitacora.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
header('Location: ../src/respaldos.php');
    exit;
}

$modelo = new RespaldoBD();
$accion = $_POST['accion'] ?? '';
$idUsuario = $_SESSION['user_id'];

try {
    if ($accion === 'exportar') {
        $archivo = $modelo->exportar();
        registrar_bitacora(Conexion::conectar(), $idUsuario, 'Exportar', 'Respaldo BD', "Respaldo creado: $archivo");
        $_SESSION['respaldo_msg'] = "Respaldo creado: $archivo";

    } elseif ($accion === 'importar') {
        $archivo = $_POST['archivo'] ?? '';
        $modelo->importar($archivo);
        registrar_bitacora(Conexion::conectar(), $idUsuario, 'Importar', 'Respaldo BD', "BD restaurada desde: $archivo");
        $_SESSION['respaldo_msg'] = "Base de datos restaurada desde $archivo";

    } else {
        $_SESSION['respaldo_msg'] = 'Acción no reconocida';
    }
} catch (Exception $e) {
    $_SESSION['respaldo_msg'] = 'Error: ' . $e->getMessage();
}

header('Location: ../src/respaldos.php');
exit;