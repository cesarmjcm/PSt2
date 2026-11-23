<?php
require_once __DIR__ . '/../config/conexion.php';

class Solicitud
{
    public function mostrarSolicitudes($idBiblioteca = null, $soloAprobadas = false)
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
                    CONCAT(e.nombre, ' ', e.apellido) AS responsable,
                    s.responsable AS id_empleado,
                    s.participantes,
                    s.descripcion,
                    s.estado
                FROM solicitud s
                JOIN institucion i ON i.id = s.id_institucion
                LEFT JOIN empleado e ON e.id = s.responsable
                LEFT JOIN biblioteca b ON b.id = s.id_biblioteca
                LEFT JOIN parroquia p ON p.id = b.id_parroquia
                LEFT JOIN municipio m ON m.id = p.id_municipio
                " . ($condiciones ? 'WHERE ' . implode(' AND ', $condiciones) : '') . "
                ORDER BY s.fecha_solicitud DESC, s.hora_solicitud DESC";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function existeConflictoHorario(array $datos, $idExcluir = null)
    {
        $fecha = trim((isset($datos['fecha_solicitud']) ? $datos['fecha_solicitud'] : ('')));
        $hora = trim((isset($datos['hora_solicitud']) ? $datos['hora_solicitud'] : ('')));
        $idBiblioteca = (int) ((isset($datos['id_biblioteca']) ? $datos['id_biblioteca'] : (0)));
        if ($fecha === '' || $hora === '' || $idBiblioteca <= 0 || ((isset($datos['estado']) ? $datos['estado'] : (''))) === 'cancelada') {
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

    public function obtenerSolicitudPorId($id, $idBiblioteca = null)
    {
        $sql = "SELECT s.*, CONCAT(e.nombre, ' ', e.apellido) AS responsable, s.responsable AS id_empleado, i.nombre AS nombre_institucion
                FROM solicitud s
                JOIN institucion i ON i.id = s.id_institucion
                LEFT JOIN empleado e ON e.id = s.responsable
                WHERE s.id = ?" . ($idBiblioteca !== null ? " AND s.id_biblioteca = ?" : "");
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($idBiblioteca !== null ? [$id, $idBiblioteca] : [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearSolicitud($id_institucion, $id_biblioteca, $fecha_solicitud, $hora_solicitud, $lugar, $responsable, $participantes, $descripcion, $estado = 'Pendiente', $nombre_actividad = '')
    {
        $sql = "INSERT INTO solicitud (id_institucion, id_biblioteca, fecha_solicitud, hora_solicitud, lugar, responsable, participantes, descripcion, estado, nombre_actividad)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id_institucion, $id_biblioteca, $fecha_solicitud, $hora_solicitud, $lugar, $responsable, $participantes, $descripcion, $estado, $nombre_actividad]);
    }

    public function actualizarSolicitud($id, $id_institucion, $id_biblioteca, $fecha_solicitud, $hora_solicitud, $lugar, $responsable, $participantes, $descripcion, $estado = 'Pendiente', $nombre_actividad = '')
    {
        $sql = "UPDATE solicitud SET id_institucion = ?, id_biblioteca = ?, fecha_solicitud = ?, hora_solicitud = ?, lugar = ?, responsable = ?, participantes = ?, descripcion = ?, estado = ?, nombre_actividad = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id_institucion, $id_biblioteca, $fecha_solicitud, $hora_solicitud, $lugar, $responsable, $participantes, $descripcion, $estado, $nombre_actividad, $id]);
    }

    public function eliminarSolicitud($id, $idBiblioteca = null)
    {
        $sql = "DELETE FROM solicitud WHERE id = ?" . ($idBiblioteca !== null ? " AND id_biblioteca = ?" : "");
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute($idBiblioteca !== null ? [$id, $idBiblioteca] : [$id]);
    }
}
