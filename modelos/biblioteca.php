<?php
require_once __DIR__ . '/../config/conexion.php';

class Biblioteca {

public function mostrarBibliotecas() {
    $sql = "SELECT
                b.id,
                b.nombre,
                b.id_parroquia,
                p.nombre AS id_parroquia_nombre,
                b.Correo AS correo,
                b.redes_sociales,
                b.Direccion AS direccion
            FROM biblioteca b
            JOIN parroquia p ON b.id_parroquia = p.id
            ORDER BY b.nombre";

    $stmt = Conexion::conectar()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function obtenerBibliotecaPorId(int $id) {
        $sql = "SELECT * FROM biblioteca WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verdadero si ya existe una biblioteca con ese nombre DENTRO DE LA
     * MISMA parroquia.
     */
    public function existeNombre(string $nombre, int $id_parroquia, ?int $idExcluir = null): bool {
        $sql = "SELECT id FROM biblioteca WHERE LOWER(nombre) = LOWER(?) AND id_parroquia = ?";
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

    public function crearBiblioteca(string $nombre, int $id_parroquia, string $correo = '', string $redes_sociales = '', string $direccion = '') {
        $sql = "INSERT INTO biblioteca (nombre, id_parroquia, Correo, redes_sociales, Direccion) VALUES (?, ?, ?, ?, ?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $id_parroquia, $correo, $redes_sociales, $direccion]);
    }

    public function actualizarBiblioteca(int $id, string $nombre, int $id_parroquia, string $correo = '', string $redes_sociales = '', string $direccion = '') {
        $sql = "UPDATE biblioteca SET nombre = ?, id_parroquia = ?, Correo = ?, redes_sociales = ?, Direccion = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $id_parroquia, $correo, $redes_sociales, $direccion, $id]);
    }

    public function eliminarBiblioteca(int $id) {
        $sql = "DELETE FROM biblioteca WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id]);
    }
}
