<?php
require_once __DIR__ . '/../config/conexion.php';

class Empleado {

    public function mostrarEmpleados(int $idBiblioteca = null) {
        $sql = "SELECT e.*, e.genero AS genero, e.edad AS edad, IF(e.fecha_inicio_cargo IS NULL, e.`anios_de_servicio`, TIMESTAMPDIFF(YEAR, e.fecha_inicio_cargo, CURDATE())) AS anios_servicio, c.nombre AS id_cargo_nombre,
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

    public function obtenerEmpleadoPorId(int $id, int $idBiblioteca = null) {
        $sql = "SELECT * FROM empleado WHERE id = ?" . ($idBiblioteca !== null ? " AND id_biblioteca = ?" : "");
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($idBiblioteca !== null ? [$id, $idBiblioteca] : [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function existeCedula(int $cedula, int $idExcluir = null): bool {
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
        $conexion = Conexion::conectar();
        $sql = "INSERT INTO empleado (nombre, apellido, telefono, id_cargo, cedula, genero, edad, `anios_de_servicio`, id_biblioteca, fecha_inicio_cargo, fecha_fin_cargo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $fechaInicio = $fecha_inicio_cargo !== '' ? $fecha_inicio_cargo : null;
        $fechaFin = $fecha_fin_cargo !== '' ? $fecha_fin_cargo : null;

        try {
            $conexion->beginTransaction();
            $stmt = $conexion->prepare($sql);
            $creado = $stmt->execute([$nombre, $apellido, $telefono, $id_cargo, $cedula, $genero, $edad, $anios_servicio, $id_biblioteca, $fechaInicio, $fechaFin]);
            if (!$creado) {
                $conexion->rollBack();
                return false;
            }

            $this->insertarAsignacion($conexion, (int) $conexion->lastInsertId(), $id_cargo, $id_biblioteca, $fechaInicio, $fechaFin);
            $conexion->commit();
            return true;
        } catch (Throwable $e) {
            if ($conexion->inTransaction()) {
                $conexion->rollBack();
            }
            throw $e;
        }
    }

    public function actualizarEmpleado(int $id, string $nombre, string $apellido, string $telefono, int $id_cargo, int $cedula, string $genero, int $edad, int $anios_servicio, int $id_biblioteca, string $fecha_inicio_cargo = '', string $fecha_fin_cargo = '') {
        $conexion = Conexion::conectar();
        $sql = "UPDATE empleado SET nombre = ?, apellido = ?, telefono = ?, id_cargo = ?, cedula = ?, genero = ?, edad = ?, `anios_de_servicio` = ?, id_biblioteca = ?, fecha_inicio_cargo = ?, fecha_fin_cargo = ? WHERE id = ?";
        $fechaInicio = $fecha_inicio_cargo !== '' ? $fecha_inicio_cargo : null;
        $fechaFin = $fecha_fin_cargo !== '' ? $fecha_fin_cargo : null;

        try {
            $conexion->beginTransaction();
            $actual = $conexion->prepare('SELECT id_cargo, id_biblioteca, fecha_inicio_cargo, fecha_fin_cargo FROM empleado WHERE id = ? FOR UPDATE');
            $actual->execute([$id]);
            $empleadoActual = $actual->fetch(PDO::FETCH_ASSOC);
            if (!$empleadoActual) {
                $conexion->rollBack();
                return false;
            }

            $stmt = $conexion->prepare($sql);
            $actualizado = $stmt->execute([$nombre, $apellido, $telefono, $id_cargo, $cedula, $genero, $edad, $anios_servicio, $id_biblioteca, $fechaInicio, $fechaFin, $id]);
            if (!$actualizado) {
                $conexion->rollBack();
                return false;
            }

            $asignacionActual = $conexion->prepare(
                'SELECT id FROM asignacion
                 WHERE id_empleado = ? AND id_cargo = ? AND id_biblioteca = ?
                    AND fecha_desde <=> ? AND fecha_hasta <=> ?
                 ORDER BY id DESC LIMIT 1 FOR UPDATE'
            );
            $asignacionActual->execute([
                $id,
                (int) $empleadoActual['id_cargo'],
                (int) $empleadoActual['id_biblioteca'],
                $empleadoActual['fecha_inicio_cargo'],
                $empleadoActual['fecha_fin_cargo'],
            ]);
            $asignacion = $asignacionActual->fetch(PDO::FETCH_ASSOC);

            if ($asignacion) {
                $guardarAsignacion = $conexion->prepare(
                    'UPDATE asignacion
                     SET id_cargo = ?, id_biblioteca = ?, fecha_desde = ?, fecha_hasta = ?
                     WHERE id = ?'
                );
                $guardarAsignacion->execute([$id_cargo, $id_biblioteca, $fechaInicio, $fechaFin, (int) $asignacion['id']]);
            } else {
                $this->insertarAsignacion($conexion, $id, $id_cargo, $id_biblioteca, $fechaInicio, $fechaFin);
            }

            $conexion->commit();
            return true;
        } catch (Throwable $e) {
            if ($conexion->inTransaction()) {
                $conexion->rollBack();
            }
            throw $e;
        }
    }

    private function insertarAsignacion(PDO $conexion, int $idEmpleado, int $idCargo, int $idBiblioteca, ?string $fechaDesde, ?string $fechaHasta): void
    {
        $stmt = $conexion->prepare(
            'INSERT INTO asignacion (id_empleado, id_cargo, id_biblioteca, fecha_desde, fecha_hasta)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$idEmpleado, $idCargo, $idBiblioteca, $fechaDesde, $fechaHasta]);
    }

    public function eliminarEmpleado(int $id) {
        $conexion = Conexion::conectar();

        try {
            $conexion->beginTransaction();
            $asignaciones = $conexion->prepare('DELETE FROM asignacion WHERE id_empleado = ?');
            $asignaciones->execute([$id]);

            $stmt = $conexion->prepare('DELETE FROM empleado WHERE id = ?');
            $eliminado = $stmt->execute([$id]);
            if (!$eliminado) {
                $conexion->rollBack();
                return false;
            }

            $conexion->commit();
            return true;
        } catch (Throwable $e) {
            if ($conexion->inTransaction()) {
                $conexion->rollBack();
            }
            throw $e;
        }
    }
}
