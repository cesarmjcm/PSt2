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
        if (!preg_match('/^[A-Za-z0-9_\-\.]+\.sql$/', $nombreArchivo)) {
            throw new Exception('Nombre de archivo no válido');
        }

        $ruta = $this->carpeta . $nombreArchivo;
        if (!is_file($ruta)) {
            throw new Exception('El archivo no existe');
        }

        $contenido = file_get_contents($ruta);
        if ($contenido === false || stripos($contenido, 'CREATE TABLE') === false) {
            throw new Exception('El archivo no parece un respaldo válido');
        }

        $sentencias = preg_split('/;\s*(?:\r?\n|$)/', $contenido);
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 0');

        foreach ($sentencias as $sentencia) {
            $sql = trim($sentencia);
            if ($sql === '' || preg_match('/^(?:--|#)/', ltrim($sql))) {
                continue;
            }

            $this->pdo->exec($sql);
        }

        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    }
}