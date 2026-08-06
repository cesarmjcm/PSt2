<?php
session_start();
if (!empty($_SESSION['user'])) {
    header('Location: main2.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <?php include '../include/contenedorlogin.php'; ?>
    <script src="./js/app.js"></script>
</body>
</html>