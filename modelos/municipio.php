<?php
require_once __DIR__ . '/../config/conexion.php';

class Municipio {

    public function mostrarMunicipios() {
        $sql = "SELECT * FROM municipio ORDER BY nombre";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMunicipioPorId(int $id) {
        $sql = "SELECT * FROM municipio WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existeNombre(string $nombre, ?int $idExcluir = null): bool {
        $sql = "SELECT id FROM municipio WHERE LOWER(nombre) = LOWER(?)";
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

    /**
     * La tabla `parroquia` no tiene FK declarada hacia `municipio`, así que
     * el motor no bloquea el DELETE por sí solo. Se verifica a mano para
     * no dejar parroquias huérfanas.
     */
    public function tieneParroquiasAsociadas(int $id): bool {
        $sql = "SELECT id FROM parroquia WHERE id_municipio = ? LIMIT 1";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$id]);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearMunicipio(string $nombre) {
        $sql = "INSERT INTO municipio (nombre) VALUES (?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre]);
    }

    public function actualizarMunicipio(int $id, string $nombre) {
        $sql = "UPDATE municipio SET nombre = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $id]);
    }

    public function eliminarMunicipio(int $id) {
        $sql = "DELETE FROM municipio WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id]);
    }
}
