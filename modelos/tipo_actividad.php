<?php
require_once __DIR__ . '/../config/conexion.php';

class TipoActividad {

    public function mostrarTipos() {
        $sql = "SELECT
        id,
        nombre,
        Descripcion AS descripcion
        FROM tipo_actividad
        ORDER BY nombre";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTipoPorId($id) {
        $sql = "SELECT * FROM tipo_actividad WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existeNombre($nombre, $idExcluir = null) {
        $sql = "SELECT id FROM tipo_actividad WHERE LOWER(nombre) = LOWER(?)";
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

    public function crearTipo($nombre, $descripcion = '') {
        $sql = "INSERT INTO tipo_actividad (nombre, Descripcion) VALUES (?, ?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $descripcion]);
    }

    public function actualizarTipo($id, $nombre, $descripcion = '') {
        $sql = "UPDATE tipo_actividad SET nombre = ?, Descripcion = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $descripcion, $id]);
    }

    public function eliminarTipo($id) {
        $sql = "DELETE FROM tipo_actividad WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id]);
    }
}
