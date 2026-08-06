<?php
require_once __DIR__ . '/../config/conexion.php';

class Comuna {

    public function mostrarComunas() {
        $sql = "SELECT c.*, pa.nombre AS id_parroquia_nombre
                FROM comuna c
                JOIN parroquia pa ON c.id_parroquia = pa.id
                ORDER BY c.nombre";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerComunaPorId(int $id) {
        $sql = "SELECT * FROM comuna WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verdadero si ya existe una comuna con ese nombre DENTRO DE LA
     * MISMA parroquia.
     */
    public function existeNombre(string $nombre, int $id_parroquia, ?int $idExcluir = null): bool {
        $sql = "SELECT id FROM comuna WHERE LOWER(nombre) = LOWER(?) AND id_parroquia = ?";
        $params = [$nombre, $id_parroquia];
        if ($idExcluir !== null) {
            $sql .= " AND id != ?";
            $params[] = $idExcluir;
        }
        $sql .= " LIMIT 1";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearComuna(string $nombre, int $id_parroquia) {
        $sql = "INSERT INTO comuna (nombre, id_parroquia) VALUES (?, ?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $id_parroquia]);
    }

    public function actualizarComuna(int $id, string $nombre, int $id_parroquia) {
        $sql = "UPDATE comuna SET nombre = ?, id_parroquia = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $id_parroquia, $id]);
    }

    public function eliminarComuna(int $id) {
        // `actividad_comuna` referencia esta tabla con ON DELETE CASCADE,
        // por eso no hace falta validar dependencias antes de borrar.
        $sql = "DELETE FROM comuna WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id]);
    }
}
