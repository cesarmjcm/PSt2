<?php
require_once __DIR__ . '/../config/conexion.php';

class Solicitud
{
    public function mostrarSolicitudes()
    {
        $sql = "SELECT
                    s.id,
                    s.id_institucion,
                    i.nombre AS institucion,
                    s.fecha_solicitud,
                    s.hora_solicitud,
                    s.lugar,
                    s.responsable,
                    s.participantes,
                    s.descripcion
                FROM solicitud s
                JOIN institucion i ON i.id = s.id_institucion
                ORDER BY s.fecha_solicitud DESC, s.hora_solicitud DESC";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerSolicitudPorId(int $id)
    {
        $sql = "SELECT s.*, i.nombre AS nombre_institucion FROM solicitud s JOIN institucion i ON i.id = s.id_institucion WHERE s.id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearSolicitud(int $id_institucion, string $fecha_solicitud, string $hora_solicitud, string $lugar, string $responsable, int $participantes, string $descripcion)
    {
        $sql = "INSERT INTO solicitud (id_institucion, fecha_solicitud, hora_solicitud, lugar, responsable, participantes, descripcion)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id_institucion, $fecha_solicitud, $hora_solicitud, $lugar, $responsable, $participantes, $descripcion]);
    }

    public function actualizarSolicitud(int $id, int $id_institucion, string $fecha_solicitud, string $hora_solicitud, string $lugar, string $responsable, int $participantes, string $descripcion)
    {
        $sql = "UPDATE solicitud SET id_institucion = ?, fecha_solicitud = ?, hora_solicitud = ?, lugar = ?, responsable = ?, participantes = ?, descripcion = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id_institucion, $fecha_solicitud, $hora_solicitud, $lugar, $responsable, $participantes, $descripcion, $id]);
    }

    public function eliminarSolicitud(int $id)
    {
        $sql = "DELETE FROM solicitud WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id]);
    }
}
