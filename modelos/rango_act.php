<?php
require_once __DIR__ . '/../config/conexion.php';

class RangoAct {

    public function mostrarRangosAct() {
        $sql = "SELECT * FROM rango_act ORDER BY nombre";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerRangoActPorId($id) {
        $sql = "SELECT * FROM rango_act WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearRangoAct($nombre, $descripcion = null) {
        $sql = "INSERT INTO rango_act (nombre, descripcion) VALUES (?, ?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $descripcion]);
    }

    public function actualizarRangoAct($id, $nombre, $descripcion = null) {
        $sql = "UPDATE rango_act SET nombre = ?, descripcion = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $descripcion, $id]);
    }

    public function eliminarRangoAct($id) {
        $sql = "DELETE FROM rango_act WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>