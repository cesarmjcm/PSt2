<?php
// MODELO_BITACORA.PHP - Lógica de acceso a datos de la tabla 'bitacora'
//
// CORRECCIÓN: registrar_bitacora() ya NO depende de `global $conex`.
// Antes, los controladores creaban $conex como variable LOCAL dentro de un
// método/función (ej. dentro de ActividadController::crear()), por lo que
// nunca existía una variable $conex en el ámbito global real. Como
// registrar_bitacora() hacía `global $conex;`, siempre encontraba null y
// fallaba en silencio (solo dejaba un error_log, nadie revisaba el
// return false). Ahora la conexión se recibe explícitamente como parámetro.

/**
 * Registra una acción en la tabla de bitácora.
 *
 * @param PDO    $conex       Conexión PDO activa.
 * @param int    $id_usu      ID del usuario que realiza la acción (de la sesión actual).
 * @param string $accion      Tipo de acción (e.g., 'Crear', 'Editar', 'Eliminar', 'Login').
 * @param string $descripcion Módulo o entidad afectada (e.g., 'Biblioteca', 'Actividad', 'Usuario').
 * @param string $detalle     Detalles específicos de la acción o los datos modificados.
 * @return bool True si la inserción fue exitosa, false en caso de error.
 */
function registrar_bitacora($conex, $id_usu, $accion, $descripcion, $detalle) {
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

        $ok = $sql_b->execute([$nom_dia, $fecha, $hora, $id_usu, $accion, $descripcion, $detalle]);
        if (!$ok) {
            error_log("Fallo al insertar en bitácora (execute devolvió false). Usuario: $id_usu | Acción: $accion");
        }
        return $ok;
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
 * @param PDO   $conex   Conexión PDO activa.
 * @param array $filtros ['id_usu' => int, 'accion' => string, 'fecha_desde' => 'Y-m-d', 'fecha_hasta' => 'Y-m-d']
 * @return array Lista de registros (arrays asociativos).
 */
function obtener_bitacora($conex, $filtros = []) {
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
 * @param PDO $conex Conexión PDO activa.
 * @return array Lista de usuarios (id, nombre).
 */
function obtener_usuarios_para_filtro($conex) {
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
