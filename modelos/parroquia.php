<?php
require_once __DIR__ . '/../config/conexion.php';

class Parroquia {

    public function mostrarParroquias() {
        $sql = "SELECT pa.*, m.nombre AS id_municipio_nombre
                FROM parroquia pa
                JOIN municipio m ON pa.id_municipio = m.id
                ORDER BY pa.nombre";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerParroquiaPorId(int $id) {
        $sql = "SELECT * FROM parroquia WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verdadero si ya existe una parroquia con ese nombre DENTRO DEL
     * MISMO municipio.
     */
    public function existeNombre(string $nombre, int $id_municipio, ?int $idExcluir = null): bool {
        $sql = "SELECT id FROM parroquia WHERE LOWER(nombre) = LOWER(?) AND id_municipio = ?";
        $params = [$nombre, $id_municipio];
        if ($idExcluir !== null) {
            $sql .= " AND id != ?";
            $params[] = $idExcluir;
        }
        $sql .= " LIMIT 1";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearParroquia(string $nombre, int $id_municipio) {
        $sql = "INSERT INTO parroquia (nombre, id_municipio) VALUES (?, ?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $id_municipio]);
    }

    public function actualizarParroquia(int $id, string $nombre, int $id_municipio) {
        $sql = "UPDATE parroquia SET nombre = ?, id_municipio = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $id_municipio, $id]);
    }

    public function eliminarParroquia(int $id) {
        $sql = "DELETE FROM parroquia WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id]);
    }
}
