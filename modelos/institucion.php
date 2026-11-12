<?php
require_once __DIR__ . '/../config/conexion.php';

class Institucion
{
    public function mostrarInstituciones(?int $idBiblioteca = null)
    {
        $sql = "SELECT i.id, i.nombre, i.rif, i.correo, i.direccion, i.id_municipio, m.nombre AS id_municipio_nombre
                FROM institucion i
                JOIN municipio m ON i.id_municipio = m.id
                " . ($idBiblioteca !== null ? "WHERE (
                    i.id_municipio = (
                        SELECT p_usuario.id_municipio
                        FROM biblioteca b_usuario
                        JOIN parroquia p_usuario ON p_usuario.id = b_usuario.id_parroquia
                        WHERE b_usuario.id = ?
                    )
                    OR EXISTS (
                        SELECT 1
                        FROM solicitud s
                        WHERE s.id_institucion = i.id
                          AND s.id_biblioteca = ?
                    )
                ) " : "") . "
                ORDER BY i.nombre";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($idBiblioteca !== null ? [$idBiblioteca, $idBiblioteca] : []);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerInstitucionPorId(int $id)
    {
        $sql = "SELECT * FROM institucion WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existeNombre(string $nombre, int $id_municipio, int $idExcluir = null): bool
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

    public function existeRif(string $rif, int $idExcluir = null): bool
    {
        $sql = "SELECT id FROM institucion WHERE rif = ?";
        $params = [$rif];
        if ($idExcluir !== null) {
            $sql .= " AND id != ?";
            $params[] = $idExcluir;
        }
        $sql .= " LIMIT 1";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearInstitucion(string $nombre, int $id_municipio, string $rif, string $correo = '', string $direccion = '')
    {
        $sql = "INSERT INTO institucion (nombre, id_municipio, rif, correo, direccion) VALUES (?, ?, ?, ?, ?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $id_municipio, $rif !== '' ? $rif : null, $correo !== '' ? $correo : null, $direccion !== '' ? $direccion : null]);
    }

    public function actualizarInstitucion(int $id, string $nombre, int $id_municipio, string $rif, string $correo = '', string $direccion = '')
    {
        $sql = "UPDATE institucion SET nombre = ?, id_municipio = ?, rif = ?, correo = ?, direccion = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $id_municipio, $rif !== '' ? $rif : null, $correo !== '' ? $correo : null, $direccion !== '' ? $direccion : null, $id]);
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
