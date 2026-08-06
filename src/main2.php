<?php

require_once __DIR__ . '/../include/guardian.php';


if (isset($_GET['accion']) && $_GET['accion'] === 'obtener_actividades') {
    require_once __DIR__ . '/../modelos/actividad.php';

    header('Content-Type: application/json; charset=utf-8');

    try {
        $actividadModel = new Actividad();
        $actividades = $actividadModel->mostrarActividades();

        
        foreach ($actividades as &$act) {
            if (!empty($act['fecha'])) {
                $act['fecha'] = date('Y-m-d', strtotime($act['fecha']));
            }
        }

        echo json_encode($actividades);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }

    exit; // Importante: cortamos aquí para no renderizar el HTML
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/main2.css?v=4">
    <link rel="stylesheet" href="./css/fontawesome-all.min.css">
</head>
<body>
    <?php include '../include/header.php'; ?>
    <?php include '../include/metrics.php'; ?>
    <?php include '../include/modal.php'; ?>
    <?php include '../include/calendario.php'; ?>
    <script src="./js/app.js"></script>
    <script src="./js/script.js"></script>
</body>
</html>
