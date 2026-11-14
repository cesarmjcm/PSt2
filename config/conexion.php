<?php
date_default_timezone_set('America/Caracas');
ini_set('date.timezone', 'America/Caracas');

class Conexion {
    private static $host = 'localhost';
    private static $db = 'red_bibliotecas';
    private static $usuario = 'root';
    private static $pswd = '';
    public static function conectar() {
        try {
            $pdo = new PDO('mysql:host=' . self::$host . ';dbname=' . self::$db . ';charset=utf8', self::$usuario, self::$pswd);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("SET NAMES 'utf8'");
            return $pdo;
        } catch (PDOException $e) {
            die('Error de conexión: ' . $e->getMessage());
        }
    }
}
?>