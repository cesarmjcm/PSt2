<?php
require_once __DIR__ . '/../config/conexion.php';

class Cargo {

    public function mostrarCargos() {
        $sql = "SELECT
        id,
        nombre,
        Descripcion AS descripcion
    FROM cargo
    ORDER BY nombre";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerCargoPorId(int $id) {
        $sql = "SELECT * FROM cargo WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existeNombre(string $nombre, ?int $idExcluir = null): bool {
        $sql = "SELECT id FROM cargo WHERE LOWER(nombre) = LOWER(?)";
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

    public function crearCargo(string $nombre, string $descripcion = '') {
        $sql = "INSERT INTO cargo (nombre, Descripcion) VALUES (?, ?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $descripcion]);
    }

    public function actualizarCargo(int $id, string $nombre, string $descripcion = '') {
        $sql = "UPDATE cargo SET nombre = ?, Descripcion = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $descripcion, $id]);
    }

    public function eliminarCargo(int $id) {
        $sql = "DELETE FROM cargo WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
