<?php
// MODELO_BITACORA.PHP - Lógica de acceso a datos de la tabla 'bitacora'
// Depende de la variable global $conex definida en tu conexion.php (PDO)

/**
 * Registra una acción en la tabla de bitácora.
 *
 * @param int    $id_usu      ID del usuario que realiza la acción (de la sesión actual).
 * @param string $accion      Tipo de acción (e.g., 'Crear', 'Editar', 'Eliminar', 'Login').
 * @param string $descripcion Módulo o entidad afectada (e.g., 'Biblioteca', 'Actividad', 'Usuario').
 * @param string $detalle     Detalles específicos de la acción o los datos modificados.
 * @return bool True si la inserción fue exitosa, false en caso de error.
 */
function registrar_bitacora($id_usu, $accion, $descripcion, $detalle) {
    global $conex; // Usa la conexión global

    if (!$conex) {
        error_log("Error: Conexión de BD no disponible para registrar bitácora.");
        return false;
    }

    $nom_dia = obtener_nombre_dia(date("D"));
    $hora = date("H:i:s");
    $fecha = date("Y-m-d");

    try {
        // No insertamos 'id': dejamos que AUTO_INCREMENT lo genere.
        $sql_b = $conex->prepare(
            "INSERT INTO bitacora (nom_dia, fecha, hora, id_usu, accion, descripcion, detalle)
             VALUES (?, ?, ?, ?, ?, ?, ?);"
        );

        return $sql_b->execute([$nom_dia, $fecha, $hora, $id_usu, $accion, $descripcion, $detalle]);
    } catch (PDOException $e) {
        error_log("Fallo al insertar en bitácora: " . $e->getMessage());
        return false;
    }
}

/**
 * Traduce el nombre del día (inglés) a español.
 *
 * @param string $nom_dia_en Nombre corto del día en inglés (Mon, Tue, etc.).
 * @return string Nombre del día en español.
 */
function obtener_nombre_dia($nom_dia_en) {
    switch ($nom_dia_en) {
        case 'Mon': return "Lunes";
        case 'Tue': return "Martes";
        case 'Wed': return "Miercoles";
        case 'Thu': return "Jueves";
        case 'Fri': return "Viernes";
        case 'Sat': return "Sabado";
        case 'Sun': return "Domingo";
        default: return "Error";
    }
}

/**
 * Obtiene registros de la bitácora con filtros opcionales, incluyendo
 * el nombre del usuario que ejecutó cada acción.
 *
 * @param array $filtros ['id_usu' => int, 'accion' => string, 'fecha_desde' => 'Y-m-d', 'fecha_hasta' => 'Y-m-d']
 * @return array Lista de registros (arrays asociativos).
 */
function obtener_bitacora($filtros = []) {
    global $conex;

    $where = [];
    $params = [];

    if (!empty($filtros['id_usu'])) {
        $where[] = "b.id_usu = ?";
        $params[] = $filtros['id_usu'];
    }
    if (!empty($filtros['accion'])) {
        $where[] = "b.accion = ?";
        $params[] = $filtros['accion'];
    }
    if (!empty($filtros['fecha_desde'])) {
        $where[] = "b.fecha >= ?";
        $params[] = $filtros['fecha_desde'];
    }
    if (!empty($filtros['fecha_hasta'])) {
        $where[] = "b.fecha <= ?";
        $params[] = $filtros['fecha_hasta'];
    }

    $sql = "SELECT b.id, b.nom_dia, b.fecha, b.hora, b.id_usu, b.accion, b.descripcion, b.detalle,
                   u.nombre AS nombre_usuario
            FROM bitacora b
            INNER JOIN usuario u ON u.id = b.id_usu";

    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $sql .= " ORDER BY b.fecha DESC, b.hora DESC, b.id DESC";

    try {
        $stmt = $conex->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Fallo al consultar bitácora: " . $e->getMessage());
        return [];
    }
}

/**
 * Devuelve la lista de usuarios para poblar el filtro del select en la vista.
 *
 * @return array Lista de usuarios (id, nombre).
 */
function obtener_usuarios_para_filtro() {
    global $conex;

    try {
        $stmt = $conex->prepare("SELECT id, nombre FROM usuario ORDER BY nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Fallo al consultar usuarios para filtro de bitácora: " . $e->getMessage());
        return [];
    }
}
?>
