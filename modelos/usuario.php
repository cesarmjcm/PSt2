<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../helpers/validador.php';

class Usuario {

public function mostrarUsuarios() {

    $sql = "SELECT
    u.id,
    u.nombre,
    u.telefono,
    u.id_empleado,
    u.rol,
    CONCAT(e.nombre, ' ', e.apellido) AS id_empleado_nombre
FROM usuario u
LEFT JOIN empleado e ON e.id = u.id_empleado
ORDER BY u.nombre";

    $stmt = Conexion::conectar()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

 
    public function validarUsuario(array $data, bool $isUpdate = false): array
    {
        $errors = [];
        $nombre = trim($data['nombre'] ?? '');
        $clave = trim($data['clave'] ?? '');
        $telefono = trim($data['telefono'] ?? '');
        $id_empleado = $data['id_empleado'] ?? null;

        if (!Validador::esTextoValido($nombre, 2, 100)) {
            $errors[] = 'Nombre de usuario inválido.';
        }

        if (!$isUpdate || $clave !== '') {
            if (!Validador::esClaveValida($clave, 6, 100)) {
                $errors[] = 'Clave inválida. Debe tener al menos 6 caracteres.';
            }
        }

        if (!Validador::esTelefonoValido($telefono)) {
            $errors[] = 'Teléfono inválido. Debe contener solo números, espacios, + o - (entre 7 y 15 caracteres).';
        }

        if (!Validador::esEnteroPositivo($id_empleado)) {
            $errors[] = 'Debe seleccionar un empleado válido.';
        }

        if (array_key_exists('rol', $data)) {
            $rol = $data['rol'];
            if (!in_array($rol, ['administrador', 'usuario'], true)) {
                $errors[] = 'Rol inválido.';
            }
        }

        return $errors;
    }

    public function obtenerUsuarioPorId(int $id) {
        $sql = "SELECT id, nombre, telefono, id_empleado, rol FROM usuario WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerUsuarioPorNombre(string $nombre) {
        $sql = "SELECT id, nombre, clave, rol FROM usuario WHERE nombre = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$nombre]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existeUsuario(string $nombre, ?int $idExcluir = null): bool
    {
        $sql = "SELECT id FROM usuario WHERE nombre = ?";
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
     * Verdadero si el empleado indicado ya tiene un usuario creado.
     * $idExcluir permite ignorar el propio usuario cuando se está editando.
     */
    public function existeUsuarioPorEmpleado(int $id_empleado, ?int $idExcluir = null): bool
    {
        $sql = "SELECT id FROM usuario WHERE id_empleado = ?";
        $params = [$id_empleado];
        if ($idExcluir !== null) {
            $sql .= " AND id != ?";
            $params[] = $idExcluir;
        }
        $sql .= " LIMIT 1";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearUsuario(string $nombre, string $clave, string $telefono, int $id_empleado, string $rol = 'usuario')
    {
        try {
            $hash = password_hash($clave, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuario (nombre, clave, telefono, id_empleado, rol) VALUES (?, ?, ?, ?, ?)";
            $stmt = Conexion::conectar()->prepare($sql);
            return $stmt->execute([$nombre, $hash, $telefono, $id_empleado, $rol]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                // Nombre duplicado o empleado ya con usuario (constraint UNIQUE).
                return false;
            }
            throw $e;
        }
    }

    /**
     * Actualiza nombre, teléfono y empleado. La clave solo se cambia si se
     * envía un valor no vacío; si no, se conserva la clave actual.
     */
    /**
     * $rol solo se actualiza cuando se pasa explícitamente (lo hace el
     * controlador solo si quien edita es administrador). Si es null, el
     * rol actual del usuario no se toca.
     */
    public function actualizarUsuario(int $id, string $nombre, string $telefono, int $id_empleado, ?string $clave = null, ?string $rol = null)
    {
        try {
            $campos = ['nombre = ?', 'telefono = ?', 'id_empleado = ?'];
            $params = [$nombre, $telefono, $id_empleado];

            if ($clave !== null && $clave !== '') {
                $campos[] = 'clave = ?';
                $params[] = password_hash($clave, PASSWORD_DEFAULT);
            }

            if ($rol !== null) {
                $campos[] = 'rol = ?';
                $params[] = $rol;
            }

            $params[] = $id;
            $sql = "UPDATE usuario SET " . implode(', ', $campos) . " WHERE id = ?";
            $stmt = Conexion::conectar()->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return false;
            }
            throw $e;
        }
    }

    public function eliminarUsuario(int $id) {
        $sql = "DELETE FROM usuario WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
