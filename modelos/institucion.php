<?php
require_once __DIR__ . '/../config/conexion.php';

class Institucion
{
    public function mostrarInstituciones()
    {
        $sql = "SELECT i.id, i.nombre, i.id_municipio, m.nombre AS id_municipio_nombre
                FROM institucion i
                JOIN municipio m ON i.id_municipio = m.id
                ORDER BY i.nombre";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerInstitucionPorId(int $id)
    {
        $sql = "SELECT * FROM institucion WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existeNombre(string $nombre, int $id_municipio, ?int $idExcluir = null): bool
    {
        $sql = "SELECT id FROM institucion WHERE LOWER(nombre) = LOWER(?) AND id_municipio = ?";
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

    public function crearInstitucion(string $nombre, int $id_municipio)
    {
        $sql = "INSERT INTO institucion (nombre, id_municipio) VALUES (?, ?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $id_municipio]);
    }

    public function actualizarInstitucion(int $id, string $nombre, int $id_municipio)
    {
        $sql = "UPDATE institucion SET nombre = ?, id_municipio = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $id_municipio, $id]);
    }

    public function tieneSolicitudesAsociadas(int $id): bool
    {
        $sql = "SELECT id FROM solicitud WHERE id_institucion = ? LIMIT 1";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$id]);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function eliminarInstitucion(int $id)
    {
        $sql = "DELETE FROM institucion WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id]);
    }
}
