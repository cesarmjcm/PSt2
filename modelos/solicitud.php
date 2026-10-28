<?php
require_once __DIR__ . '/../config/conexion.php';

class Solicitud
{
    public function mostrarSolicitudes(int $idBiblioteca = null, bool $soloAprobadas = false)
    {
        $condiciones = [];
        $parametros = [];
        if ($idBiblioteca !== null) {
            $condiciones[] = 's.id_biblioteca = ?';
            $parametros[] = $idBiblioteca;
        }
        if ($soloAprobadas) {
            $condiciones[] = "s.estado = 'aprobada'";
        }

        $sql = "SELECT
                    s.id,
                    s.id_institucion,
                    s.id_biblioteca,
                    i.nombre AS institucion,
                    b.nombre AS biblioteca,
                    b.id_parroquia AS parroquia_id,
                    p.id_municipio AS municipio_id,
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
                LEFT JOIN parroquia p ON p.id = b.id_parroquia
                LEFT JOIN municipio m ON m.id = p.id_municipio
                " . ($condiciones ? 'WHERE ' . implode(' AND ', $condiciones) : '') . "
                ORDER BY s.fecha_solicitud DESC, s.hora_solicitud DESC";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function existeConflictoHorario(array $datos, int $idExcluir = null): bool
    {
        $fecha = trim($datos['fecha_solicitud'] ?? '');
        $hora = trim($datos['hora_solicitud'] ?? '');
        $idBiblioteca = (int) ($datos['id_biblioteca'] ?? 0);
        if ($fecha === '' || $hora === '' || $idBiblioteca <= 0 || ($datos['estado'] ?? '') === 'cancelada') {
            return false;
        }

        $sql = "SELECT id FROM solicitud
                WHERE fecha_solicitud = ? AND hora_solicitud = ?
                    AND id_biblioteca = ? AND estado != 'cancelada'";
        $params = [$fecha, $hora, $idBiblioteca];
        if ($idExcluir !== null) {
            $sql .= " AND id != ?";
            $params[] = $idExcluir;
        }
        $sql .= " LIMIT 1";

        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
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
