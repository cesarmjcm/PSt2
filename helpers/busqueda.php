<?php

function normalizarTextoBusqueda($valor): string
{
    $texto = trim((string) $valor);
    if (class_exists('\Normalizer')) {
        $normalizado = \Normalizer::normalize($texto, \Normalizer::FORM_D);
        if ($normalizado !== false) {
            $sinDiacriticos = preg_replace('/\p{Mn}+/u', '', $normalizado);
            if ($sinDiacriticos !== null) {
                $texto = $sinDiacriticos;
            }
        }
    } elseif (function_exists('iconv')) {
        $transliterado = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);
        if ($transliterado !== false) {
            $texto = $transliterado;
        }
    }

    return function_exists('mb_strtolower')
        ? mb_strtolower($texto, 'UTF-8')
        : strtolower($texto);
}

function coincideTextoBusqueda(array $valores, string $busqueda): bool
{
    $termino = normalizarTextoBusqueda($busqueda);
    if ($termino === '') {
        return true;
    }

    return strpos(normalizarTextoBusqueda(implode(' ', array_map('strval', $valores))), $termino) !== false;
}

function filtrarFilasPorBusqueda(array $filas, string $busqueda): array
{
    if (trim($busqueda) === '') {
        return $filas;
    }

    return array_values(array_filter($filas, static function (array $fila) use ($busqueda): bool {
        return coincideTextoBusqueda(array_values($fila), $busqueda);
    }));
}
