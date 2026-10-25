<?php
require_once __DIR__ . '/../include/guardian.php';
require_once __DIR__ . '/../modelos/respaldosbd.php';

$mensaje = $_SESSION['respaldo_msg'] ?? '';
unset($_SESSION['respaldo_msg']);

$respaldos = (new RespaldoBD())->listar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Respaldo de la base de datos</title>
    <link rel="stylesheet" href="./css/main.css">
    
    <link rel="stylesheet" href="./css/resp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php require_once __DIR__ . '/../include/header.php'; ?>

<main class="container__respaldo">
    <h1 class="respaldo__title">Respaldo de la base de datos</h1>
    <p class="respaldo__subtitle">Cree una copia de seguridad o restaure la información del sistema.</p>

    <?php if ($mensaje): ?>
        <?php $esError = strpos($mensaje, 'Error') === 0; ?>
        <div class="respaldo__alerta respaldo__alerta--<?= $esError ? 'error' : 'ok' ?>">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php endif; ?>
<div class="center">
    <div class="respaldo__tarjetas">
        <section class="respaldo__tarjeta">
            <h2><i class="fas fa-download"></i> Exportar</h2>
            <p>Crea un respaldo completo (estructura y datos) y lo guarda en el servidor.</p>
            <form method="POST" action="../controladores/resp_cont.php">
                <input type="hidden" name="accion" value="exportar">
                <button type="submit" class="respaldo__btn respaldo__btn--verde">
                    <i class="fas fa-file-export"></i> Crear respaldo ahora
                </button>
            </form>
        </section>

        <section class="respaldo__tarjeta">
            <h2><i class="fas fa-upload"></i> Importar</h2>
            <p><strong>Atención:</strong> reemplaza todos los datos actuales. Antes se crea un respaldo de seguridad automático.</p>
            <?php if (!$respaldos): ?>
                <p>Aún no hay respaldos guardados.</p>
            <?php else: ?>
                <form method="POST" action="../controladores/resp_cont.php"
                      onsubmit="return confirm('Esto reemplaza TODOS los datos actuales. ¿Continuar?');">
                    <input type="hidden" name="accion" value="importar">
                    <select name="archivo" required>
                        <?php foreach ($respaldos as $r): ?>
                            <option value="<?= htmlspecialchars($r['nombre']) ?>">
                                <?= htmlspecialchars($r['nombre']) ?> (<?= date('d/m/Y H:i', $r['fecha']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="respaldo__btn respaldo__btn--rojo">
                        <i class="fas fa-file-import"></i> Importar
                    </button>
                </form>
            <?php endif; ?>
        </section>
    </div>
</div>

</main>

<script src="./js/header.js"></script>
</body>
</html>