<?php
require_once __DIR__ . '/../config/conexion.php';

class Espacio {

    public function mostrarEspacios() {
        $sql = "SELECT
        id,
        nombre,
        capacidad,
        direccion,
        Metodo_contactar AS metodo_contactar
    FROM espacio_cultural
    ORDER BY nombre";
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

    public function existeNombre(string $nombre, ?int $idExcluir = null): bool {
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

    public function crearEspacio(string $nombre, int $capacidad, string $direccion, string $metodo_contactar = '') {
        $sql = "INSERT INTO espacio_cultural (nombre, capacidad, direccion, Metodo_contactar) VALUES (?, ?, ?, ?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $capacidad, $direccion, $metodo_contactar]);
    }

    public function actualizarEspacio(int $id, string $nombre, int $capacidad, string $direccion, string $metodo_contactar = '') {
        $sql = "UPDATE espacio_cultural SET nombre = ?, capacidad = ?, direccion = ?, Metodo_contactar = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $capacidad, $direccion, $metodo_contactar, $id]);
    }

    public function eliminarEspacio(int $id) {
        $sql = "DELETE FROM espacio_cultural WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
