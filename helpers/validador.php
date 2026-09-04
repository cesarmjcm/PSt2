<?php

class Validador
{
 
    public static function normalizarTexto(?string $valor): string
    {
        $valor = (string) $valor;
        
        $valor = str_replace(["\r\n", "\r"], ["\n", "\n"], $valor);
        $valor = trim($valor);

        if ($valor === '') {
            return $valor;
        }

        $esUtf8Valido = function_exists('mb_check_encoding')
            ? mb_check_encoding($valor, 'UTF-8')
            : (bool) preg_match('//u', $valor);

        if (!$esUtf8Valido) {
            $detectado = function_exists('mb_detect_encoding')
                ? mb_detect_encoding($valor, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true)
                : 'ISO-8859-1';

            if ($detectado && $detectado !== 'UTF-8') {
                if (function_exists('mb_convert_encoding')) {
                    $convertido = @mb_convert_encoding($valor, 'UTF-8', $detectado);
                } elseif (function_exists('iconv')) {
                    $convertido = @iconv($detectado, 'UTF-8//IGNORE', $valor);
                } else {
                    $convertido = false;
                }
                if ($convertido !== false) {
                    $valor = $convertido;
                }
            }
        }

        return $valor;
    }

    private static function longitud(string $valor): int
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($valor, 'UTF-8');
        }
        
        return preg_match_all('/./us', $valor);
    }

  
    public static function tieneCaracterRepetido(string $valor, int $maxSeguidos = 3): bool
    {
        $valor = self::normalizarTexto($valor);
        if ($valor === '') {
            return false;
        }
        return (bool) preg_match('/(.)\1{' . $maxSeguidos . ',}/u', $valor);
    }


    public static function tieneCadenaRepetida(
        string $valor,
        int $minLongitudPatron = 2,
        int $maxLongitudPatron = 8,
        int $minRepeticiones = 3
    ): bool {
        $valor = self::normalizarTexto($valor);
        if ($valor === '') {
            return false;
        }
        $repeticionesExtra = max(1, $minRepeticiones - 1);
        $patron = '/(.{' . $minLongitudPatron . ',' . $maxLongitudPatron . '}?)\1{' . $repeticionesExtra . ',}/u';
        return (bool) preg_match($patron, $valor);
    }

  
    public static function tieneRepeticionSospechosa(
        string $valor,
        int $maxCaracterSeguido = 3,
        int $minRepeticionesCadena = 3
    ): bool {
        return self::tieneCaracterRepetido($valor, $maxCaracterSeguido)
            || self::tieneCadenaRepetida($valor, 2, 8, $minRepeticionesCadena);
    }

  
    public static function esTextoValido(string $valor, int $min = 2, int $max = 100): bool
    {
        $valor = self::normalizarTexto($valor);
        $len = self::longitud($valor);
        if ($len < $min || $len > $max) {
            return false;
        }
        return (bool) preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü0-9 \'\-\.,\(\)\?\!\:\/]+$/u', $valor);
    }

  
    public static function esNombrePropioValido(string $valor, int $min = 2, int $max = 50): bool
    {
        $valor = self::normalizarTexto($valor);
        $len = self::longitud($valor);
        if ($len < $min || $len > $max) {
            return false;
        }
        return (bool) preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü \'\-]+$/u', $valor);
    }


    public static function esDescripcionValida(string $valor, int $min = 0, int $max = 250): bool
    {
        $valor = self::normalizarTexto($valor);
        $len = self::longitud($valor);
        if ($len < $min || $len > $max) {
            return false;
        }
        if ($len === 0) {
            return true; 
        }
        return (bool) preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü0-9 \'\-\.,()#]+$/u', $valor);
    }

 
    public static function esTelefonoVenezolano(string $valor): bool
    {
        return (bool) preg_match('/^0(4(12|14|16|22|24|26)|2\d{2})\d{7}$/', $valor);
    }

 
    public static function esTelefonoValido(string $valor, int $max = 15): bool
    {
        return (bool) preg_match('/^[0-9\-\+ ]{7,' . $max . '}$/', $valor);
    }

    
    public static function esCedulaValida(string $valor, int $min = 6, int $max = 8): bool
    {
        $valor = trim($valor);
        $len = self::longitud($valor);
        if ($len < $min || $len > $max) {
            return false;
        }
        return (bool) preg_match('/^[0-9]+$/', $valor);
    }

    
    public static function esEnteroPositivo($valor, int $max = 2147483647): bool
    {
        if (!is_numeric($valor)) {
            return false;
        }
        $n = (int) $valor;
        return $n > 0 && $n <= $max;
    }

    
    public static function esEnteroNoNegativo($valor, int $max = 2147483647): bool
    {
        if (!is_numeric($valor)) {
            return false;
        }
        $n = (int) $valor;
        return $n >= 0 && $n <= $max;
    }


    public static function esFechaValida(string $valor): bool
    {
        $d = DateTime::createFromFormat('Y-m-d', $valor);
        return $d !== false && $d->format('Y-m-d') === $valor;
    }

    public static function esHoraValida(string $valor): bool
    {
        return (bool) preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $valor);
    }

    public static function esDiaSemanaValido(string $valor): bool
    {
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        return in_array($valor, $dias, true);
    }

    public static function esClaveValida(string $valor, int $min = 6, int $max = 100): bool
    {
        $len = self::longitud($valor);
        return $len >= $min && $len <= $max;
    }

    
    public static function esCorreoValido(string $valor, int $max = 30): bool
    {
        $valor = self::normalizarTexto($valor);
        $len = self::longitud($valor);
        if ($len === 0) {
            return true; 
        }
        if ($len > $max) {
            return false;
        }
        return filter_var($valor, FILTER_VALIDATE_EMAIL) !== false;
    }

   
    public static function esContactoValido(string $valor, int $min = 0, int $max = 40): bool
    {
        $valor = self::normalizarTexto($valor);
        $len = self::longitud($valor);
        if ($len < $min || $len > $max) {
            return false;
        }
        if ($len === 0) {
            return true; 
        }
        return (bool) preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü0-9 @\.\/:\-_,()]+$/u', $valor);
    }

    public static function esMetodoContactarValido(string $valor, int $min = 0, int $max = 100): bool
    {
        $valor = self::normalizarTexto($valor);
        $len = self::longitud($valor);
        if ($len < $min || $len > $max) {
            return false;
        }
        if ($len === 0) {
            return true; 
        }

        
        
        if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü0-9 @\.\/:\-_,\+()]+$/u', $valor)) {
            return false;
        }

        
        
        $esTelefono = (bool) preg_match('/^\+?[0-9][0-9\-\s]{6,14}$/', $valor);

        
        $esCorreo = filter_var($valor, FILTER_VALIDATE_EMAIL) !== false;

        
        $esRedSocial = (bool) preg_match('/^@[A-Za-z0-9_\.]{2,30}$/', $valor);

        
        $esEnlace = (bool) preg_match('/^(https?:\/\/|www\.)[^\s]+$/i', $valor);

        return $esTelefono || $esCorreo || $esRedSocial || $esEnlace;
    }
}
