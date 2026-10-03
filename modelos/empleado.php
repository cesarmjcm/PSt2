<?php
require_once __DIR__ . '/../config/conexion.php';

class Empleado {

    public function mostrarEmpleados(?int $idBiblioteca = null) {
        $sql = "SELECT e.*, e.genero AS genero, e.edad AS edad, e.`anios_de_servicio` AS anios_servicio, c.nombre AS id_cargo_nombre,
                       b.nombre AS id_biblioteca_nombre
                FROM empleado e
                JOIN cargo c ON e.id_cargo = c.id
                LEFT JOIN biblioteca b ON e.id_biblioteca = b.id
                " . ($idBiblioteca !== null ? "WHERE e.id_biblioteca = ? " : "") . "
                ORDER BY e.nombre, e.apellido";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($idBiblioteca !== null ? [$idBiblioteca] : []);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEmpleadoPorId(int $id, ?int $idBiblioteca = null) {
        $sql = "SELECT * FROM empleado WHERE id = ?" . ($idBiblioteca !== null ? " AND id_biblioteca = ?" : "");
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($idBiblioteca !== null ? [$id, $idBiblioteca] : [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function existeCedula(int $cedula, ?int $idExcluir = null): bool {
        $sql = "SELECT id FROM empleado WHERE cedula = ?";
        $params = [$cedula];
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
     * $id_biblioteca: biblioteca donde trabaja el empleado.
     * $fecha_inicio_cargo / $fecha_fin_cargo: formato Y-m-d o '' (se guarda NULL).
     * Una fecha fin vacía significa que el cargo sigue vigente.
     */
    public function crearEmpleado(string $nombre, string $apellido, string $telefono, int $id_cargo, int $cedula, string $genero, int $edad, int $anios_servicio, int $id_biblioteca, string $fecha_inicio_cargo = '', string $fecha_fin_cargo = '') {
        $sql = "INSERT INTO empleado (nombre, apellido, telefono, id_cargo, cedula, genero, edad, `anios_de_servicio`, id_biblioteca, fecha_inicio_cargo, fecha_fin_cargo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $apellido, $telefono, $id_cargo, $cedula, $genero, $edad, $anios_servicio, $id_biblioteca, $fecha_inicio_cargo !== '' ? $fecha_inicio_cargo : null, $fecha_fin_cargo !== '' ? $fecha_fin_cargo : null]);
    }

    public function actualizarEmpleado(int $id, string $nombre, string $apellido, string $telefono, int $id_cargo, int $cedula, string $genero, int $edad, int $anios_servicio, int $id_biblioteca, string $fecha_inicio_cargo = '', string $fecha_fin_cargo = '') {
        $sql = "UPDATE empleado SET nombre = ?, apellido = ?, telefono = ?, id_cargo = ?, cedula = ?, genero = ?, edad = ?, `anios_de_servicio` = ?, id_biblioteca = ?, fecha_inicio_cargo = ?, fecha_fin_cargo = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $apellido, $telefono, $id_cargo, $cedula, $genero, $edad, $anios_servicio, $id_biblioteca, $fecha_inicio_cargo !== '' ? $fecha_inicio_cargo : null, $fecha_fin_cargo !== '' ? $fecha_fin_cargo : null, $id]);
    }

    public function eliminarEmpleado(int $id) {
        
        
        $sql = "DELETE FROM empleado WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id]);
    }
}
