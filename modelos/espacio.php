<?php
require_once __DIR__ . '/../config/conexion.php';

class Espacio {

    public function mostrarEspacios() {
        $sql = "SELECT
        ec.id,
        ec.nombre,
        ec.capacidad,
        ec.direccion,
        ec.Metodo_contactar AS metodo_contactar,
        ec.id_parroquia,
        p.id_municipio,
        p.nombre AS id_parroquia_nombre,
        ec.id_comuna,
        c.nombre AS id_comuna_nombre
    FROM espacio_cultural ec
    LEFT JOIN parroquia p ON p.id = ec.id_parroquia
    LEFT JOIN comuna c ON c.id = ec.id_comuna
    ORDER BY ec.nombre";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEspacioPorId(int $id) {
        $sql = "SELECT * FROM espacio_cultural WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existeNombre(string $nombre, int $idExcluir = null): bool {
        $sql = "SELECT id FROM espacio_cultural WHERE LOWER(nombre) = LOWER(?)";
        $params = [$nombre];
        if ($idExcluir !== null) {
            $sql .= " AND id != ?";
            $params[] = $idExcluir;
        }
        $sql .= " LIMIT 1";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function ubicacionValida(int $idParroquia, int $idComuna): bool {
        $sql = "SELECT id FROM comuna WHERE id = ? AND id_parroquia = ? LIMIT 1";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$idComuna, $idParroquia]);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearEspacio(string $nombre, int $capacidad, string $direccion, string $metodo_contactar, int $idParroquia, int $idComuna) {
        $sql = "INSERT INTO espacio_cultural (nombre, capacidad, direccion, Metodo_contactar, id_parroquia, id_comuna) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $capacidad, $direccion, $metodo_contactar, $idParroquia, $idComuna]);
    }

    public function actualizarEspacio(int $id, string $nombre, int $capacidad, string $direccion, string $metodo_contactar, int $idParroquia, int $idComuna) {
        $sql = "UPDATE espacio_cultural SET nombre = ?, capacidad = ?, direccion = ?, Metodo_contactar = ?, id_parroquia = ?, id_comuna = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $capacidad, $direccion, $metodo_contactar, $idParroquia, $idComuna, $id]);
    }

    public function eliminarEspacio(int $id) {
        $sql = "DELETE FROM espacio_cultural WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
