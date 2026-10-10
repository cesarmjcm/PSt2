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
    responderReporte(['success' => false, 'message' => 'Método no permitido.'], 405);
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

    require_once __DIR__ . '/../modelos/empleado.php';

    $empleados = (new Empleado())->mostrarEmpleados();

    $css = file_get_contents(__DIR__ . '/reportes_empleado.css');
    if ($css === false) {
        throw new RuntimeException('No se pudo cargar el estilo del reporte.');
    }

    

    $escapar = static function ($valor): string {
        return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    };
    $formatearFecha = static function ($fecha): string {
        if (empty($fecha)) {
            return '—';
        }
        $d = DateTimeImmutable::createFromFormat('Y-m-d', (string) $fecha);
        return $d ? $d->format('d/m/Y') : '—';
    };

    $fechaActual = new DateTimeImmutable('now', new DateTimeZone('America/Caracas'));
    $fechaGeneracion = $fechaActual->format('d/m/Y h:i A');

    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Reporte de empleados</title>
        <style><?php echo $css; ?></style>
    </head>
    <body>
        <table class="membrete">
            <tr>
       
                <td class="membrete-texto">
                    <p class="membrete-pais">República Bolivariana de Venezuela</p>
                    <p class="membrete-inst">Instituto de Cultura del Estado Yaracuy (ICEY)</p>
                    <p class="membrete-sub">Red de Bibliotecas del Estado Yaracuy</p>
                </td>
            </tr>
        </table>

        <header class="reporte-header">
            <h1>Reporte de empleados</h1>
            <p class="reporte-fecha">Generado el <?php echo $escapar($fechaGeneracion); ?></p>
        </header>

        <section class="resumen">
            <strong>Total de empleados:</strong> <?php echo count($empleados); ?>
        </section>

        <table class="datos">
            <thead>
                <tr>
                    <th>Cédula</th>
                    <th>Nombre y apellido</th>
                    <th>Teléfono</th>
                    <th class="col-centro">Género</th>
                    <th class="col-centro">Edad</th>
                    <th>Cargo</th>
                    <th>Biblioteca</th>
                    <th class="col-centro">Inicio cargo</th>
                    <th class="col-centro">Fin cargo</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($empleados === []): ?>
                    <tr>
                        <td colspan="9" class="sin-registros">No hay empleados registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($empleados as $e): ?>
                        <tr>
                            <td><?php echo $escapar($e['cedula'] ?? ''); ?></td>
                            <td><?php echo $escapar(trim(($e['nombre'] ?? '') . ' ' . ($e['apellido'] ?? ''))); ?></td>
                            <td><?php echo $escapar($e['telefono'] ?? ''); ?></td>
                            <td class="col-centro"><?php echo ($e['genero'] ?? '') === 'M' ? 'Masculino' : (($e['genero'] ?? '') === 'F' ? 'Femenino' : $escapar($e['genero'] ?? '')); ?></td>
                            <td class="col-centro"><?php echo $escapar($e['edad'] ?? ''); ?></td>
                            <td><?php echo $escapar($e['id_cargo_nombre'] ?? ''); ?></td>
                            <td><?php echo $escapar($e['id_biblioteca_nombre'] ?? ''); ?></td>
                            <td class="col-centro"><?php echo $escapar($formatearFecha($e['fecha_inicio_cargo'] ?? null)); ?></td>
                            <td class="col-centro"><?php echo $escapar($formatearFecha($e['fecha_fin_cargo'] ?? null)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <footer>Reporte generado por el Sistema de Planificación de la Red de Bibliotecas de Yaracuy.</footer>
    </body>
    </html>
    <?php
    $html = ob_get_clean();

    $opciones = new \Dompdf\Options();
    $opciones->set('isRemoteEnabled', false);
    $opciones->set('defaultFont', 'DejaVu Sans');

    $dompdf = new \Dompdf\Dompdf($opciones);
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    responderReporte([
        'success' => true,
        'filename' => 'reporte_empleados_' . $fechaActual->format('Ymd_His') . '.pdf',
        'pdf' => base64_encode($dompdf->output()),
    ]);
} catch (Throwable $error) {
    error_log('Error al generar el reporte PDF de empleados: ' . $error->getMessage());
    responderReporte([
        'success' => false,
        'message' => 'Ocurrió un error al generar el reporte PDF.',
    ], 500);
}