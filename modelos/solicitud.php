<?php
require_once __DIR__ . '/../config/conexion.php';

class Solicitud
{
    public function mostrarSolicitudes(int $idBiblioteca = null)
    {
        $sql = "SELECT
                    s.id,
                    s.id_institucion,
                    s.id_biblioteca,
                    i.nombre AS institucion,
                    b.nombre AS biblioteca,
                    s.fecha_solicitud,
                    s.fecha_registro,
                    s.hora_solicitud,
                    s.lugar,
                    s.nombre_actividad,
                    s.responsable,
                    s.participantes,
                    s.descripcion,
                    s.estado
                FROM solicitud s
                JOIN institucion i ON i.id = s.id_institucion
                LEFT JOIN biblioteca b ON b.id = s.id_biblioteca
                " . ($idBiblioteca !== null ? "WHERE s.id_biblioteca = ? " : "") . "
                ORDER BY s.fecha_solicitud DESC, s.hora_solicitud DESC";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($idBiblioteca !== null ? [$idBiblioteca] : []);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerSolicitudPorId(int $id, int $idBiblioteca = null)
    {
        $sql = "SELECT s.*, i.nombre AS nombre_institucion
                FROM solicitud s
                JOIN institucion i ON i.id = s.id_institucion
                WHERE s.id = ?" . ($idBiblioteca !== null ? " AND s.id_biblioteca = ?" : "");
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($idBiblioteca !== null ? [$id, $idBiblioteca] : [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearSolicitud(int $id_institucion, int $id_biblioteca, string $fecha_solicitud, string $hora_solicitud, string $lugar, string $responsable, int $participantes, string $descripcion, string $estado = 'Pendiente', string $nombre_actividad = '')
    {
        $sql = "INSERT INTO solicitud (id_institucion, id_biblioteca, fecha_solicitud, hora_solicitud, lugar, responsable, participantes, descripcion, estado, nombre_actividad)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id_institucion, $id_biblioteca, $fecha_solicitud, $hora_solicitud, $lugar, $responsable, $participantes, $descripcion, $estado, $nombre_actividad]);
    }

    public function actualizarSolicitud(int $id, int $id_institucion, int $id_biblioteca, string $fecha_solicitud, string $hora_solicitud, string $lugar, string $responsable, int $participantes, string $descripcion, string $estado = 'Pendiente', string $nombre_actividad = '')
    {
        $sql = "UPDATE solicitud SET id_institucion = ?, id_biblioteca = ?, fecha_solicitud = ?, hora_solicitud = ?, lugar = ?, responsable = ?, participantes = ?, descripcion = ?, estado = ?, nombre_actividad = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id_institucion, $id_biblioteca, $fecha_solicitud, $hora_solicitud, $lugar, $responsable, $participantes, $descripcion, $estado, $nombre_actividad, $id]);
    }

    public function eliminarSolicitud(int $id, int $idBiblioteca = null)
    {
        $sql = "DELETE FROM solicitud WHERE id = ?" . ($idBiblioteca !== null ? " AND id_biblioteca = ?" : "");
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute($idBiblioteca !== null ? [$id, $idBiblioteca] : [$id]);
    }
}
