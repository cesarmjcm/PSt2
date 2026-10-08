<?php
require_once __DIR__ . '/../include/guardian.php';
guardian_requerirAdmin('json');

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

function responderReporte(array $respuesta, int $estado = 200): void
{
    http_response_code($estado);
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Allow: GET');
    responderReporte([
        'success' => false,
        'message' => 'Método no permitido.',
    ], 405);
}

try {
    $archivosAutoload = [
        __DIR__ . '/../librerias/dompdf/vendor/autoload.php',
        __DIR__ . '/../librerias/dompdf/autoload.inc.php',
    ];
    $dompdfDisponible = false;
    foreach ($archivosAutoload as $archivoAutoload) {
        if (!is_file($archivoAutoload) || filesize($archivoAutoload) === 0) {
            continue;
        }

        require_once $archivoAutoload;
        if (class_exists(\Dompdf\Dompdf::class)) {
            $dompdfDisponible = true;
            break;
        }
    }

    if (!$dompdfDisponible) {
        responderReporte([
            'success' => false,
            'message' => 'No se pudo cargar Dompdf. Verifica que librerias/dompdf contenga sus archivos.',
        ], 500);
    }

    require_once __DIR__ . '/../modelos/cargo.php';

    $cargos = (new Cargo())->mostrarCargos();
    $cssPath = __DIR__ . '/reportes_cargo.css';
    $css = file_get_contents($cssPath);
    if ($css === false) {
        throw new RuntimeException('No se pudo cargar el estilo del reporte.');
    }

    $escapar = static function ($valor): string {
        return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    };
    $fechaActual = new DateTimeImmutable('now', new DateTimeZone('America/Caracas'));
    $fechaGeneracion = $fechaActual->format('d/m/Y h:i A');

    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Reporte de cargos</title>
        <style><?php echo $css; ?></style>
    </head>
    <body>
        <header class="reporte-header">
            <p class="reporte-etiqueta">Sistema de gestión</p>
            <h1>Reporte de cargos</h1>
            <p class="reporte-fecha">Generado el <?php echo $escapar($fechaGeneracion); ?></p>
        </header>

        <section class="resumen">
            <strong>Total de cargos:</strong> <?php echo count($cargos); ?>
        </section>

        <table>
            <thead>
                <tr>
                    <th class="col-id">ID</th>
                    <th>Nombre del cargo</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($cargos === []): ?>
                    <tr>
                        <td colspan="3" class="sin-registros">No hay cargos registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($cargos as $cargo): ?>
                        <tr>
                            <td class="col-id"><?php echo $escapar($cargo['id'] ?? ''); ?></td>
                            <td><?php echo $escapar($cargo['nombre'] ?? ''); ?></td>
                            <td><?php echo $escapar($cargo['descripcion'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <footer>Reporte generado por el sistema de gestión.</footer>
    </body>
    </html>
    <?php
    $html = ob_get_clean();

    $opciones = new \Dompdf\Options();
    $opciones->set('isRemoteEnabled', false);
    $opciones->set('defaultFont', 'DejaVu Sans');

    $dompdf = new \Dompdf\Dompdf($opciones);
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    responderReporte([
        'success' => true,
        'filename' => 'reporte_cargos_' . $fechaActual->format('Ymd_His') . '.pdf',
        'pdf' => base64_encode($dompdf->output()),
    ]);
} catch (Throwable $error) {
    error_log('Error al generar el reporte PDF de cargos: ' . $error->getMessage());
    responderReporte([
        'success' => false,
        'message' => 'Ocurrió un error al generar el reporte PDF.',
    ], 500);
}
