<?php

class Validador
{
 
    public static function normalizarTexto($valor)
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

    private static function longitud($valor)
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($valor, 'UTF-8');
        }
        
        return preg_match_all('/./us', $valor);
    }

  
    public static function tieneCaracterRepetido($valor, $maxSeguidos = 3)
    {
        $valor = self::normalizarTexto($valor);
        if ($valor === '') {
            return false;
        }
        return (bool) preg_match('/(.)\1{' . $maxSeguidos . ',}/u', $valor);
    }


    public static function tieneCadenaRepetida(
        $valor,
        $minLongitudPatron = 2,
        $maxLongitudPatron = 8,
        $minRepeticiones = 3
    ) {
        $valor = self::normalizarTexto($valor);
        if ($valor === '') {
            return false;
        }
        $repeticionesExtra = max(1, $minRepeticiones - 1);
        $patron = '/(.{' . $minLongitudPatron . ',' . $maxLongitudPatron . '}?)\1{' . $repeticionesExtra . ',}/u';
        return (bool) preg_match($patron, $valor);
    }

  
    public static function tieneRepeticionSospechosa(
        $valor,
        $maxCaracterSeguido = 3,
        $minRepeticionesCadena = 3
    ) {
        return self::tieneCaracterRepetido($valor, $maxCaracterSeguido)
            || self::tieneCadenaRepetida($valor, 2, 8, $minRepeticionesCadena);
    }

  
    public static function esTextoValido($valor, $min = 2, $max = 100)
    {
        $valor = self::normalizarTexto($valor);
        $len = self::longitud($valor);
        if ($len < $min || $len > $max) {
            return false;
        }
        return (bool) preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü0-9 \'\-\.,\(\)\?\!\:\/]+$/u', $valor);
    }

  
    public static function esNombrePropioValido($valor, $min = 2, $max = 50)
    {
        $valor = self::normalizarTexto($valor);
        $len = self::longitud($valor);
        if ($len < $min || $len > $max) {
            return false;
        }
        return (bool) preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü \'\-]+$/u', $valor);
    }


    public static function esDescripcionValida($valor, $min = 0, $max = 250)
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

 
    public static function esTelefonoVenezolano($valor)
    {
        return (bool) preg_match('/^0(4(12|14|16|22|24|26)|2\d{2})\d{7}$/', $valor);
    }

 
    public static function esTelefonoValido($valor, $max = 15)
    {
        return (bool) preg_match('/^[0-9\-\+ ]{7,' . $max . '}$/', $valor);
    }

    
    public static function esCedulaValida($valor, $min = 6, $max = 8)
    {
        $valor = trim($valor);
        $len = self::longitud($valor);
        if ($len < $min || $len > $max) {
            return false;
        }
        return (bool) preg_match('/^[0-9]+$/', $valor);
    }

    
    public static function esEnteroPositivo($valor, $max = 2147483647)
    {
        if (!is_numeric($valor)) {
            return false;
        }
        $n = (int) $valor;
        return $n > 0 && $n <= $max;
    }

    
    public static function esEnteroNoNegativo($valor, $max = 2147483647)
    {
        if (!is_numeric($valor)) {
            return false;
        }
        $n = (int) $valor;
        return $n >= 0 && $n <= $max;
    }


    public static function esFechaValida($valor)
    {
        $d = DateTime::createFromFormat('Y-m-d', $valor);
        return $d !== false && $d->format('Y-m-d') === $valor;
    }

    /**
     * Edad mínima para ser empleado (17 años por defecto).
     */
    public static function esEdadMinimaValida($edad, $minimo = 17)
    {
        return $edad >= $minimo;
    }

    /**
     * Máximo de años de servicio que puede tener un empleado según su edad:
     * edad - edad mínima para trabajar (17). Con 17 años -> 0, con 18 -> 1, con 30 -> 13...
     */
    public static function aniosServicioMaximos($edad, $edadMinima = 17)
    {
        return max(0, $edad - $edadMinima);
    }

    /**
     * Fecha de ingreso: formato Y-m-d, fecha real, no futura y no más antigua que $maxAnios años.
     */
    public static function esFechaIngresoValida($valor, $maxAnios = 70, $fechaFin = null)
    {
        if (!self::esFechaValida($valor)) {
            return false;
        }
        $anios = self::calcularAniosServicio($valor, $fechaFin);
        return $anios >= 0 && $anios <= $maxAnios;
    }

    /**
     * Años COMPLETOS entre la fecha de ingreso y la fecha final, o hoy si sigue vigente.
     * Devuelve -1 si alguna fecha no es válida o la fecha de inicio es futura.
     */
    public static function calcularAniosServicio($fechaIngreso, $fechaFin = null)
    {
        $ingreso = DateTime::createFromFormat('!Y-m-d', $fechaIngreso);
        if ($ingreso === false || $ingreso->format('Y-m-d') !== $fechaIngreso) {
            return -1;
        }
        $hoy = new DateTime('today');
        if ($ingreso > $hoy) {
            return -1;
        }
        $fin = $fechaFin === null || $fechaFin === ''
            ? $hoy
            : DateTime::createFromFormat('!Y-m-d', $fechaFin);
        if ($fin === false || $fin < $ingreso) {
            return -1;
        }
        return (int) $ingreso->diff($fin)->y;
    }

    public static function esHoraValida($valor)
    {
        return (bool) preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $valor);
    }

    public static function esDiaSemanaValido($valor)
    {
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        return in_array($valor, $dias, true);
    }

    /**
     * Normaliza un RIF venezolano al formato J-12345678-9 (mayúscula y guiones).
     * Si el valor no tiene la forma de un RIF, lo devuelve solo en mayúsculas y sin espacios.
     */
    public static function normalizarRif($valor)
    {
        $valor = strtoupper(preg_replace('/\s+/', '', self::normalizarTexto($valor)));
        if (preg_match('/^([JGVEPC])-?(\d{8})-?(\d)$/', $valor, $m)) {
            return $m[1] . '-' . $m[2] . '-' . $m[3];
        }
        return $valor;
    }

    /**
     * RIF venezolano: una letra (J, G, V, E, P o C), 8 dígitos y un dígito verificador.
     * Acepta con o sin guiones (J-12345678-9 / J123456789).
     */
    public static function esRifValido($valor)
    {
        return (bool) preg_match('/^[JGVEPC]-\d{8}-\d$/', self::normalizarRif($valor));
    }

    public static function esClaveValida($valor, $min = 6, $max = 100)
    {
        $len = self::longitud($valor);
        return $len >= $min && $len <= $max;
    }

    
    public static function esCorreoValido($valor, $max = 30)
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

   
    public static function esContactoValido($valor, $min = 0, $max = 40)
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

    public static function esMetodoContactarValido($valor, $min = 0, $max = 100)
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
