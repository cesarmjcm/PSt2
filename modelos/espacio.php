<?php
require_once __DIR__ . '/../config/conexion.php';

class Espacio {

    /**
     * Lista los espacios culturales con su ubicación.
     * espacio_cultural no tiene parroquia propia, así que se obtiene con JOINs
     * usando a la actividad como "puente":
     *   espacio_cultural -> actividad -> comuna -> parroquia -> municipio
     * Se toma la ubicación de la actividad más reciente del espacio que tenga comuna.
     */
    public function mostrarEspacios() {
        $sql = "SELECT
        ec.id,
        ec.nombre,
        ec.capacidad,
        ec.direccion,
        ec.Metodo_contactar AS metodo_contactar,
        p.id AS id_parroquia,
        p.nombre AS id_parroquia_nombre,
        p.id_municipio,
        m.nombre AS id_municipio_nombre,
        c.id AS id_comuna,
        c.nombre AS id_comuna_nombre
    FROM espacio_cultural ec
    LEFT JOIN actividad a ON a.id = (
        SELECT a2.id FROM actividad a2
        WHERE a2.id_espacio_cultural = ec.id AND a2.id_comuna IS NOT NULL
        ORDER BY a2.fecha DESC, a2.id DESC
        LIMIT 1
    )
    LEFT JOIN comuna c ON c.id = a.id_comuna
    LEFT JOIN parroquia p ON p.id = c.id_parroquia
    LEFT JOIN municipio m ON m.id = p.id_municipio
    ORDER BY ec.nombre";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEspacioPorId(int $id) {
        $sql = "SELECT * FROM espacio_cultural WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existeNombre(string $nombre, int $idExcluir = null): bool {
        $sql = "SELECT id FROM espacio_cultural WHERE LOWER(nombre) = LOWER(?)";
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

    public function crearEspacio(string $nombre, int $capacidad, string $direccion, string $metodo_contactar) {
        $sql = "INSERT INTO espacio_cultural (nombre, capacidad, direccion, Metodo_contactar) VALUES (?, ?, ?, ?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $capacidad, $direccion, $metodo_contactar]);
    }

    public function actualizarEspacio(int $id, string $nombre, int $capacidad, string $direccion, string $metodo_contactar) {
        $sql = "UPDATE espacio_cultural SET nombre = ?, capacidad = ?, direccion = ?, Metodo_contactar = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $capacidad, $direccion, $metodo_contactar, $id]);
    }

    public function eliminarEspacio(int $id) {
        $sql = "DELETE FROM espacio_cultural WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
