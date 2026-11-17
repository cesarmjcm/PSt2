<?php
require_once __DIR__ . '/../helpers/busqueda.php';

function responderReporteExcel(array $respuesta, $estado = 200)
{
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($estado);
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
    exit;
}

function valorColumnaExcel(array $fila, array $columna)
{
    if (isset($columna['partes'])) {
        $valores = [];
        foreach ($columna['partes'] as $parte) {
            $valor = valorColumnaExcel($fila, $parte);
            if ($valor !== '') {
                $valores[] = $parte['etiqueta'] . ': ' . $valor;
            }
        }
        return implode(' | ', $valores);
    }

    $claves = isset($columna['claves']) ? $columna['claves'] : [$columna['clave']];
    $valores = [];
    foreach ($claves as $clave) {
        $valor = trim((string) (isset($fila[$clave]) ? $fila[$clave] : ''));
        if ($valor !== '') {
            $valores[] = $valor;
        }
    }
    $valor = implode(isset($columna['separador']) ? $columna['separador'] : ' / ', $valores);

    if ($valor !== '' && isset($columna['tipo'])) {
        if ($columna['tipo'] === 'fecha') {
            $fecha = DateTimeImmutable::createFromFormat('!Y-m-d', $valor);
            if ($fecha !== false) {
                $valor = $fecha->format('d/m/Y');
            }
        } elseif ($columna['tipo'] === 'hora') {
            foreach (['!H:i:s', '!H:i'] as $formato) {
                $hora = DateTimeImmutable::createFromFormat($formato, $valor);
                if ($hora !== false) {
                    $valor = $hora->format('H:i');
                    break;
                }
            }
        } elseif ($columna['tipo'] === 'genero') {
            $valor = $valor === 'M' ? 'Masculino' : ($valor === 'F' ? 'Femenino' : $valor);
        }
    }

    return $valor;
}

function protegerValorCsvExcel($valor): string
{
    $valor = (string) $valor;
    if (preg_match('/^[\t\r\n ]*[=+\-@]/', $valor)) {
        return "'" . $valor;
    }
    return $valor;
}

function generarReporteExcelCsv(array $columnas, array $filas, $nombreArchivo)
{
    $fechaActual = new DateTimeImmutable('now', new DateTimeZone('America/Caracas'));
    $nombre = $nombreArchivo . '_' . $fechaActual->format('Ymd_His') . '.csv';

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $nombre . '"');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');

    $salida = fopen('php://output', 'w');
    if ($salida === false) {
        throw new RuntimeException('No se pudo crear el archivo CSV.');
    }

    fwrite($salida, "\xEF\xBB\xBF");
    $encabezados = [];
    foreach ($columnas as $columna) {
        $encabezados[] = protegerValorCsvExcel($columna['etiqueta']);
    }
    fputcsv($salida, $encabezados, ';', '"', '\\');

    foreach ($filas as $fila) {
        $valores = [];
        foreach ($columnas as $columna) {
            $valores[] = protegerValorCsvExcel(valorColumnaExcel($fila, $columna));
        }
        fputcsv($salida, $valores, ';', '"', '\\');
    }

    fclose($salida);
}

function procesarReporteExcel(array $configuracion)
{
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');

    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {
        header('Allow: GET');
        responderReporteExcel(['success' => false, 'message' => 'Método no permitido.'], 405);
    }
    if (isset($_GET['q']) && !is_string($_GET['q'])) {
        responderReporteExcel(['success' => false, 'message' => 'El término de búsqueda no es válido.'], 400);
    }

    try {
        $modeloArchivo = __DIR__ . '/../modelos/' . $configuracion['modelo'];
        if (!is_file($modeloArchivo)) {
            throw new RuntimeException('No se encontró el modelo del reporte.');
        }
        require_once $modeloArchivo;
        $clase = $configuracion['clase'];
        $modelo = new $clase();

        $filtrarBiblioteca = !empty($configuracion['filtrar_biblioteca']) && !esAdministrador();
        $idBiblioteca = null;
        if ($filtrarBiblioteca) {
            $idBiblioteca = (int) guardian_idBibliotecaSesion();
            if ($idBiblioteca <= 0) {
                responderReporteExcel([
                    'success' => false,
                    'message' => 'El usuario no tiene una biblioteca asociada.',
                ], 403);
            }
        }

        $filas = $filtrarBiblioteca
            ? $modelo->{$configuracion['metodo']}($idBiblioteca)
            : $modelo->{$configuracion['metodo']}();
        $filas = filtrarFilasPorBusqueda($filas, trim((string) ($_GET['q'] ?? '')));
        generarReporteExcelCsv($configuracion['columnas'], $filas, $configuracion['nombre']);
    } catch (Throwable $error) {
        error_log('Error al generar el reporte Excel ' . $configuracion['nombre'] . ': ' . $error->getMessage());
        responderReporteExcel([
            'success' => false,
            'message' => 'Ocurrió un error al generar el archivo Excel.',
        ], 500);
    }
}
