<?php
require_once __DIR__ . '/../helpers/busqueda.php';

function responderReportePdf(array $respuesta, $estado = 200)
{
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($estado);
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
    exit;
}

function procesarReportePdf($archivoModelo, $clase, $metodo, $titulo, array $columnas, $nombreArchivo, $orientacion = 'landscape', $filtrarBiblioteca = false)
{
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header('Allow: GET');
        responderReportePdf([
            'success' => false,
            'message' => 'Método no permitido.',
        ], 405);
    }

    if (isset($_GET['q']) && !is_string($_GET['q'])) {
        responderReportePdf([
            'success' => false,
            'message' => 'El término de búsqueda no es válido.',
        ], 400);
    }
    $busqueda = trim((string) ($_GET['q'] ?? ''));

    $idBiblioteca = null;
    if ($filtrarBiblioteca && !esAdministrador()) {
        $idBiblioteca = (int) guardian_idBibliotecaSesion();
        if ($idBiblioteca <= 0) {
            responderReportePdf([
                'success' => false,
                'message' => 'El usuario no tiene una biblioteca asociada.',
            ], 403);
        }
    }

    try {
        require_once __DIR__ . '/../modelos/' . $archivoModelo;
        $modelo = new $clase();
        $filas = $filtrarBiblioteca
            ? $modelo->{$metodo}($idBiblioteca)
            : $modelo->{$metodo}();
        $filas = filtrarFilasReportePdf($filas, $busqueda);

        generarReportePdf(
            $titulo,
            $columnas,
            $filas,
            $nombreArchivo,
            $orientacion
        );
    } catch (Throwable $error) {
        error_log('Error al generar el reporte PDF ' . $nombreArchivo . ': ' . $error->getMessage());
        responderReportePdf([
            'success' => false,
            'message' => 'Ocurrió un error al generar el reporte PDF.',
        ], 500);
    }
}

function filtrarFilasReportePdf(array $filas, string $busqueda): array
{
    return filtrarFilasPorBusqueda($filas, $busqueda);
}

function cargarDompdfReporte()
{
    $archivosAutoload = [
        __DIR__ . '/../librerias/dompdf/vendor/autoload.php',
        __DIR__ . '/../librerias/dompdf/autoload.inc.php',
    ];

    foreach ($archivosAutoload as $archivoAutoload) {
        if (!is_file($archivoAutoload) || filesize($archivoAutoload) === 0) {
            continue;
        }

        require_once $archivoAutoload;
        if (class_exists('\Dompdf\Dompdf')) {
            return;
        }
    }

    throw new RuntimeException('No se pudo cargar Dompdf.');
}

function escaparReportePdf($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function membreteReportePdf()
{
    $logoGobiernoPath = __DIR__ . '/assets/membrete_gobierno.jpg';
    $logoCulturaPath = __DIR__ . '/assets/membrete_cultura.jpg';
    $logoGobierno = file_get_contents($logoGobiernoPath);
    $logoCultura = file_get_contents($logoCulturaPath);

    if ($logoGobierno === false || $logoCultura === false) {
        throw new RuntimeException('No se pudieron cargar los logos del membrete.');
    }

    return '<table class="membrete-logos"><tr>'
        . '<td><img src="data:image/jpeg;base64,' . base64_encode($logoGobierno) . '" alt="Gobierno Bolivariano de Yaracuy"></td>'
        . '<td><img src="data:image/jpeg;base64,' . base64_encode($logoCultura) . '" alt="Instituto de Cultura del Estado Yaracuy"></td>'
        . '</tr></table>'
        . '<div class="membrete-instituciones">'
        . '<p>Gobierno Bolivariano de Yaracuy</p>'
        . '<p>Instituto de Cultura del Estado Yaracuy</p>'
        . '<p>Red de Bibliotecas Públicas del Estado Yaracuy</p>'
        . '</div>';
}

function enviarReportePdf($dompdf, $nombreArchivo)
{
    $contenido = $dompdf->output();
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $nombreArchivo . '"');
    echo $contenido;
    exit;
}

function valorReportePdf(array $fila, array $columna)
{
    $valores = [];
    $claves = isset($columna['claves']) ? $columna['claves'] : [$columna['clave']];
    foreach ($claves as $clave) {
        $valor = trim((string) (isset($fila[$clave]) ? $fila[$clave] : ''));
        if ($valor !== '') {
            $valores[] = $valor;
        }
    }

    $valor = implode(isset($columna['separador']) ? $columna['separador'] : ' / ', $valores);
    if ($valor === '') {
        return '—';
    }

    if (isset($columna['tipo']) && $columna['tipo'] === 'fecha') {
        $fecha = DateTimeImmutable::createFromFormat('!Y-m-d', $valor);
        if ($fecha !== false) {
            return $fecha->format('d/m/Y');
        }
    }

    return $valor;
}

function generarReportePdf($titulo, array $columnas, array $filas, $nombreArchivo, $orientacion = 'landscape')
{
    cargarDompdfReporte();

    $fechaActual = new DateTimeImmutable('now', new DateTimeZone('America/Caracas'));
    $fechaGeneracion = $fechaActual->format('d/m/Y h:i A');
    $orientacion = $orientacion === 'portrait' ? 'portrait' : 'landscape';

    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title><?php echo escaparReportePdf($titulo); ?></title>
        <style>
            @page { margin: 28px 30px; }
            body { color: #25346b; font-family: "DejaVu Sans", sans-serif; font-size: 9px; }
            header { border-bottom: 2px solid #25346b; margin-bottom: 14px; padding-bottom: 9px; text-align: center; }
            header p { color: #62708f; font-size: 8px; margin: 3px 0; }
            table.membrete-logos { border-collapse: collapse; margin: 0 auto 5px; width: 100%; }
            .membrete-logos td { border: 0; padding: 0; text-align: center; vertical-align: middle; width: 50%; }
            .membrete-logos img { display: inline-block; height: 48px; max-width: 100%; }
            .membrete-instituciones { color: #25346b; font-size: 8px; font-weight: bold; line-height: 1.4; margin-bottom: 8px; text-align: center; text-transform: uppercase; }
            .membrete-instituciones p { color: #25346b; font-size: 8px; margin: 0; }
            h1 { color: #25346b; font-size: 17px; margin: 8px 0 4px; }
            .resumen { background: #f0f3fa; border-left: 4px solid #156939; margin-bottom: 12px; padding: 7px 10px; }
            table { border-collapse: collapse; table-layout: fixed; width: 100%; }
            th { background: #25346b; color: #fff; font-weight: bold; text-align: left; }
            th, td { border: 1px solid #d8e1f3; font-size: 8px; padding: 5px; word-wrap: break-word; }
            tr:nth-child(even) td { background: #f7f8fc; }
            .sin-registros { color: #62708f; padding: 16px; text-align: center; }
            footer { border-top: 1px solid #d8e1f3; color: #62708f; font-size: 7px; margin-top: 18px; padding-top: 6px; text-align: center; }
        </style>
    </head>
    <body>
        <header>
            <?php echo membreteReportePdf(); ?>
            <h1><?php echo escaparReportePdf($titulo); ?></h1>
            <p>Generado el <?php echo escaparReportePdf($fechaGeneracion); ?></p>
        </header>

        <section class="resumen">
            <strong>Total de registros:</strong> <?php echo count($filas); ?>
        </section>

        <table>
            <thead>
                <tr>
                    <?php foreach ($columnas as $columna): ?>
                        <th><?php echo escaparReportePdf($columna['etiqueta']); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php if ($filas === []): ?>
                    <tr>
                        <td colspan="<?php echo count($columnas); ?>" class="sin-registros">No hay registros disponibles.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($filas as $fila): ?>
                        <tr>
                            <?php foreach ($columnas as $columna): ?>
                                <td><?php echo escaparReportePdf(valorReportePdf($fila, $columna)); ?></td>
                            <?php endforeach; ?>
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
    $dompdf->setPaper('A4', $orientacion);
    $dompdf->render();

    enviarReportePdf($dompdf, $nombreArchivo . '_' . $fechaActual->format('Ymd_His') . '.pdf');
}
