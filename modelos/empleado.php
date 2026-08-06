<?php
require_once __DIR__ . '/../config/conexion.php';

class Empleado {

    public function mostrarEmpleados() {
        $sql = "SELECT e.*, c.nombre AS id_cargo_nombre
                FROM empleado e
                JOIN cargo c ON e.id_cargo = c.id
                ORDER BY e.nombre, e.apellido";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEmpleadoPorId(int $id) {
        $sql = "SELECT * FROM empleado WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verdadero si ya existe un empleado con esa cédula
     * (la tabla tiene una UNIQUE KEY sobre `cedula`).
     */
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

    public function crearEmpleado(string $nombre, string $apellido, string $telefono, int $id_cargo, int $cedula) {
        $sql = "INSERT INTO empleado (nombre, apellido, telefono, id_cargo, cedula) VALUES (?, ?, ?, ?, ?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $apellido, $telefono, $id_cargo, $cedula]);
    }

    public function actualizarEmpleado(int $id, string $nombre, string $apellido, string $telefono, int $id_cargo, int $cedula) {
        $sql = "UPDATE empleado SET nombre = ?, apellido = ?, telefono = ?, id_cargo = ?, cedula = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $apellido, $telefono, $id_cargo, $cedula, $id]);
    }

    public function eliminarEmpleado(int $id) {
        // `usuario.id_empleado` referencia esta tabla con ON DELETE SET NULL,
        // así que borrar un empleado no falla por esa relación.
        $sql = "DELETE FROM empleado WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id]);
    }
}
