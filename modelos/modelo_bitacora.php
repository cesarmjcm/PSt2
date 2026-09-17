<?php











function registrar_bitacora($conex, $id_usu, $accion, $descripcion, $detalle) {

    if (!$conex) {
        error_log("Error: Conexión de BD no disponible para registrar bitácora.");
        return false;
    }

    $nom_dia = obtener_nombre_dia(date("D"));
    $hora = date("H:i:s");
    $fecha = date("Y-m-d");

    try {
        
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
