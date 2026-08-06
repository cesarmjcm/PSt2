<?php
require_once __DIR__ . '/../config/conexion.php';

class NivelImpacto {

    public function mostrarNiveles() {
        $sql = "SELECT * FROM nivel_impacto ORDER BY nombre_impacto";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerNivelPorId(int $id) {
        $sql = "SELECT * FROM nivel_impacto WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existeNombre(string $nombre, ?int $idExcluir = null): bool {
        $sql = "SELECT id FROM nivel_impacto WHERE LOWER(nombre_impacto) = LOWER(?)";
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

    public function crearNivel(string $nombre) {
        $sql = "INSERT INTO nivel_impacto (nombre_impacto) VALUES (?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre]);
    }

    public function actualizarNivel(int $id, string $nombre) {
        $sql = "UPDATE nivel_impacto SET nombre_impacto = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $id]);
    }

    public function eliminarNivel(int $id) {
        $sql = "DELETE FROM nivel_impacto WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id]);
    }
}
