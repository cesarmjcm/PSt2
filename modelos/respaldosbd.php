<?php
require_once __DIR__ . '/../config/conexion.php';

class RespaldoBD
{
    private $pdo;
    private $carpeta;

    public function __construct()
    {
        $this->pdo = Conexion::conectar();
        $this->carpeta = __DIR__ . '/../respaldos/';

        if (!is_dir($this->carpeta)) {
            mkdir($this->carpeta, 0755, true);
        }
    }

    public function listar()
    {
        if (!is_dir($this->carpeta)) {
            return [];
        }

        $archivos = [];
        $items = scandir($this->carpeta);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $ruta = $this->carpeta . $item;
            if (is_file($ruta) && preg_match('/\.sql$/i', $item)) {
                $archivos[] = [
                    'nombre' => $item,
                    'fecha' => filemtime($ruta),
                ];
            }
        }

        usort($archivos, function ($a, $b) {
            return $b['fecha'] <=> $a['fecha'];
        });

        return $archivos;
    }

    public function exportar($prefijo = '')
    {
        $nombre = $prefijo . 'red_bibliotecas-' . date('d-m-Y_H-i-s') . '.sql';
        $ruta = $this->carpeta . $nombre;

        $sql = "SET FOREIGN_KEY_CHECKS = 0;\n\n";

        $tablas = $this->pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

        foreach ($tablas as $tabla) {
            $crear = $this->pdo->query("SHOW CREATE TABLE `$tabla`")->fetch(PDO::FETCH_NUM);
            $sql .= "DROP TABLE IF EXISTS `$tabla`;\n" . $crear[1] . ";\n\n";

            $filas = $this->pdo->query("SELECT * FROM `$tabla`")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($filas as $fila) {
                $valores = [];
                foreach ($fila as $v) {
                    $valores[] = $v === null ? 'NULL' : $this->pdo->quote($v);
                }
                $sql .= "INSERT INTO `$tabla` VALUES (" . implode(', ', $valores) . ");\n";
            }
            $sql .= "\n";
        }

        $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

        if (file_put_contents($ruta, $sql) === false) {
            throw new Exception('No se pudo escribir el respaldo en la carpeta respaldos/');
        }

        return $nombre;
    }

    public function importar($nombreArchivo)
    {
        if (!is_string($nombreArchivo) || !preg_match('/^[A-Za-z0-9_\-\.]+\.sql$/', $nombreArchivo)) {
            throw new Exception('Nombre de archivo no válido');
        }

        $ruta = $this->carpeta . $nombreArchivo;
        if (!is_file($ruta) || !is_readable($ruta)) {
            throw new Exception('El archivo no existe o PHP no tiene permiso para leerlo');
        }

        $contenido = file_get_contents($ruta);
        if ($contenido === false || stripos($contenido, 'CREATE TABLE') === false) {
            throw new Exception('El archivo no parece un respaldo válido');
        }

        $sentencias = $this->separarSentenciasSql($contenido);
        if (!$sentencias) {
            throw new Exception('El respaldo no contiene sentencias SQL para importar');
        }

        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        try {
            foreach ($sentencias as $sentencia) {
                $this->pdo->exec($sentencia);
            }
        } finally {
            $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
        }
    }

    private function separarSentenciasSql(string $contenido): array
    {
        $sentencias = [];
        $sentencia = '';
        $tieneCodigo = false;
        $comilla = null;
        $longitud = strlen($contenido);

        for ($i = 0; $i < $longitud; $i++) {
            $caracter = $contenido[$i];
            $siguiente = $i + 1 < $longitud ? $contenido[$i + 1] : '';

            if ($comilla !== null) {
                $sentencia .= $caracter;
                if ($caracter === '\\' && $comilla !== '`' && $siguiente !== '') {
                    $sentencia .= $siguiente;
                    $i++;
                } elseif ($caracter === $comilla) {
                    if ($siguiente === $comilla) {
                        $sentencia .= $siguiente;
                        $i++;
                    } else {
                        $comilla = null;
                    }
                }
                continue;
            }

            if ($caracter === "'" || $caracter === '"' || $caracter === '`') {
                $comilla = $caracter;
                $tieneCodigo = true;
                $sentencia .= $caracter;
                continue;
            }

            if ($caracter === '#' || ($caracter === '-' && $siguiente === '-'
                && ($i + 2 >= $longitud || ord($contenido[$i + 2]) <= 32))) {
                while ($i < $longitud && $contenido[$i] !== "\n") {
                    $sentencia .= $contenido[$i];
                    $i++;
                }
                if ($i < $longitud) {
                    $sentencia .= "\n";
                }
                continue;
            }

            if ($caracter === '/' && $siguiente === '*') {
                $finComentario = strpos($contenido, '*/', $i + 2);
                if ($finComentario === false) {
                    throw new Exception('El respaldo contiene un comentario SQL incompleto');
                }
                $comentario = substr($contenido, $i, $finComentario + 2 - $i);
                $sentencia .= $comentario;
                if (substr($comentario, 0, 3) === '/*!') {
                    $tieneCodigo = true;
                }
                $i = $finComentario + 1;
                continue;
            }

            if ($caracter === ';') {
                if ($tieneCodigo) {
                    $sentencias[] = trim($sentencia);
                }
                $sentencia = '';
                $tieneCodigo = false;
                continue;
            }

            $sentencia .= $caracter;
            if (!ctype_space($caracter)) {
                $tieneCodigo = true;
            }
        }

        if ($comilla !== null) {
            throw new Exception('El respaldo contiene una cadena SQL sin cerrar');
        }
        if ($tieneCodigo) {
            $sentencias[] = trim($sentencia);
        }

        return $sentencias;
    }
}