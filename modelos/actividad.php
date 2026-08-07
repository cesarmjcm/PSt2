<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../helpers/validador.php';

class Actividad {

    public function mostrarActividades() {
        $sql = "SELECT * FROM actividad ORDER BY fecha DESC";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'normalizarDiaSemana'], $actividades);
    }

    private function normalizarDiaSemana(array $actividad): array {
        if (!empty($actividad['fecha'])) {
            try {
                $fecha = new DateTime($actividad['fecha']);
                $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                $actividad['dia_semana'] = $dias[(int)$fecha->format('w')];
            } catch (Exception $e) {
                // Si la fecha no es válida, dejamos el valor sin modificar.
            }
        }
        return $actividad;
    }

    public function mostrarActividadesCompletas() {
        $sql = "
            SELECT
                a.id,
                a.nombre,
                a.descripcion,
                a.objetivo,
                a.participantes,
                a.fecha,
                a.dia_semana,
                a.id_biblioteca,
                b.nombre AS biblioteca,
                GROUP_CONCAT(DISTINCT ni.nombre_impacto SEPARATOR ', ') AS nivel_impacto,
                GROUP_CONCAT(DISTINCT co.nombre SEPARATOR ', ') AS comuna,
                GROUP_CONCAT(DISTINCT r.nombre SEPARATOR ', ') AS responsable,
                GROUP_CONCAT(DISTINCT r.telefono SEPARATOR ', ') AS telefono_responsable
            FROM actividad a
            LEFT JOIN biblioteca b ON b.id = a.id_biblioteca
            LEFT JOIN impacto_actividad ia ON ia.id_actividad = a.id
            LEFT JOIN nivel_impacto ni ON ni.id = ia.id_impacto
            LEFT JOIN actividad_comuna ac ON ac.id_actividad = a.id
            LEFT JOIN comuna co ON co.id = ac.id_comuna
            LEFT JOIN responsable r ON r.id_actividad = a.id
            GROUP BY a.id
            ORDER BY a.fecha DESC
        ";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute();
        $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'normalizarDiaSemana'], $actividades);
    }

    public function validarActividad(array $d): array
    {
        $errors = [];

        if (!Validador::esTextoValido(trim($d['nombre'] ?? ''), 2, 30)) {
            $errors[] = 'Nombre de la actividad inválido.';
        }

        if (!Validador::esDescripcionValida(trim($d['descripcion'] ?? ''), 0, 200)) {
            $errors[] = 'Descripción inválida.';
        }

        // 'objetivo' llega con el valor por defecto 'No definido' cuando el
        // usuario elige "Actividad simple" y deja el campo vacío (ver
        // ActividadController::collectInput). Solo se valida el formato
        // cuando trae texto real escrito por el usuario.
        $objetivo = trim($d['objetivo'] ?? '');
        if ($objetivo !== '' && $objetivo !== 'No definido' && !Validador::esTextoValido($objetivo, 2, 50)) {
            $errors[] = 'Objetivo inválido.';
        }

        if (!Validador::esEnteroNoNegativo($d['participantes'] ?? 0, 99999)) {
            $errors[] = 'Cantidad de participantes inválida.';
        }

        $nivelImpacto = trim($d['nivel_impacto'] ?? '');
        if ($nivelImpacto !== '' && !Validador::esNombrePropioValido($nivelImpacto, 2, 20)) {
            $errors[] = 'Nivel de impacto inválido.';
        }

        if (!Validador::esFechaValida(trim($d['fecha'] ?? ''))) {
            $errors[] = 'Fecha inválida.';
        }

        $diaSemana = trim($d['dia_semana'] ?? '');
        if ($diaSemana === '') {
            $diaSemana = $this->calcularDiaSemanaDesdeFecha(trim($d['fecha'] ?? ''));
        }
        if ($diaSemana === '' || !Validador::esDiaSemanaValido($diaSemana)) {
            $errors[] = 'Día de la semana inválido.';
        }

        if (!Validador::esEnteroPositivo($d['id_biblioteca'] ?? 0)) {
            $errors[] = 'Biblioteca inválida.';
        }

        if (!empty($d['municipio_id']) && !Validador::esEnteroPositivo($d['municipio_id'])) {
            $errors[] = 'Municipio inválido.';
        }

        if (!empty(trim($d['parroquia'] ?? '')) && !Validador::esTextoValido(trim($d['parroquia']), 2, 100)) {
            $errors[] = 'Parroquia inválida.';
        }

        if (!empty(trim($d['comuna'] ?? '')) && !Validador::esTextoValido(trim($d['comuna']), 2, 100)) {
            $errors[] = 'Comuna inválida.';
        }

        if (!Validador::esEnteroPositivo($d['id_espacio_cultural'] ?? 0)) {
            $errors[] = 'Espacio cultural inválido.';
        }

        if (!Validador::esEnteroPositivo($d['id_tipo_actividad'] ?? 0)) {
            $errors[] = 'Tipo de actividad inválido.';
        }

        if (!empty(trim($d['responsable'] ?? '')) && !Validador::esNombrePropioValido(trim($d['responsable']), 2, 30)) {
            $errors[] = 'Responsable inválido.';
        }

        if (!empty(trim($d['telefono_responsable'] ?? '')) && !Validador::esTelefonoValido(trim($d['telefono_responsable']))) {
            $errors[] = 'Teléfono del responsable inválido.';
        }

        return $errors;
    }

    private function calcularDiaSemanaDesdeFecha(string $fecha): string
    {
        if (!Validador::esFechaValida($fecha)) {
            return '';
        }

        try {
            $fechaObj = new DateTime($fecha);
            $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            return $dias[(int)$fechaObj->format('w')];
        } catch (Exception $e) {
            return '';
        }
    }

    public function obtenerActividadPorId(int $id) {
        $sql = "SELECT * FROM actividad WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // CORRECCIÓN: se agregó id_biblioteca porque la columna es NOT NULL
    // en la tabla `actividad`; sin este parámetro el INSERT fallaba.
    public function crearActividad($nombre, $descripcion, $objetivo, $participantes, $fecha, $dia_semana, $id_biblioteca) {
        $sql = "INSERT INTO actividad (nombre, descripcion, objetivo, participantes, fecha, dia_semana, id_biblioteca) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $descripcion, $objetivo, $participantes, $fecha, $dia_semana, $id_biblioteca]);
    }

    
    public function actualizarActividad($id, $nombre, $descripcion, $objetivo, $participantes, $fecha, $dia_semana, $id_biblioteca) {
        $sql = "UPDATE actividad SET nombre = ?, descripcion = ?, objetivo = ?, participantes = ?, fecha = ?, dia_semana = ?, id_biblioteca = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $descripcion, $objetivo, $participantes, $fecha, $dia_semana, $id_biblioteca, $id]);
    }

    public function eliminarActividad(int $id) {
        $sql = "DELETE FROM actividad WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Crea una actividad junto con todas sus relaciones
     * (nivel de impacto, comuna, responsable).
     * Devuelve el id de la actividad creada.
     */
    public function crearActividadCompleta(array $d) {
        $pdo = Conexion::conectar();
        $pdo->beginTransaction();
        try {
            $sql = "INSERT INTO actividad (nombre, descripcion, objetivo, participantes, fecha, dia_semana, id_biblioteca, id_espacio_cultural, id_tipo_actividad)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $d['nombre'], $d['descripcion'], $d['objetivo'],
                $d['participantes'], $d['fecha'], $d['dia_semana'], $d['id_biblioteca'],
                $d['id_espacio_cultural'], $d['id_tipo_actividad']
            ]);
            $idActividad = (int)$pdo->lastInsertId();

            $this->guardarRelaciones($pdo, $idActividad, $d);

            $pdo->commit();
            return $idActividad;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Actualiza una actividad y reemplaza todas sus relaciones.
     */
    public function actualizarActividadCompleta(int $id, array $d) {
        $pdo = Conexion::conectar();
        $pdo->beginTransaction();
        try {
            $sql = "UPDATE actividad SET nombre=?, descripcion=?, objetivo=?, participantes=?, fecha=?, dia_semana=?, id_biblioteca=?, id_espacio_cultural=?, id_tipo_actividad=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $d['nombre'], $d['descripcion'], $d['objetivo'],
                $d['participantes'], $d['fecha'], $d['dia_semana'], $d['id_biblioteca'],
                $d['id_espacio_cultural'], $d['id_tipo_actividad'], $id
            ]);

            // Limpiamos relaciones previas para volver a insertarlas (simplifica updates)
            // NOTA: actividad_espaciocultural se excluye porque su relación con
            // espacio_cultural está rota en el diseño actual (ver comentario en
            // mostrarActividadesCompletas). Se reactivará cuando se corrija esa tabla.
            foreach (['impacto_actividad', 'actividad_comuna'] as $tabla) {
                $pdo->prepare("DELETE FROM {$tabla} WHERE id_actividad = ?")->execute([$id]);
            }
            $pdo->prepare("DELETE FROM responsable WHERE id_actividad = ?")->execute([$id]);

            $this->guardarRelaciones($pdo, $id, $d);

            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Busca el id de un registro por nombre en una tabla; si no existe, lo crea.
     */
    private function buscarOCrearId(PDO $pdo, string $tabla, string $columnaNombre, string $valor): ?int {
        $valor = trim($valor);
        if ($valor === '') return null;

        $stmt = $pdo->prepare("SELECT id FROM {$tabla} WHERE {$columnaNombre} = ? LIMIT 1");
        $stmt->execute([$valor]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) return (int)$row['id'];

        $stmt = $pdo->prepare("INSERT INTO {$tabla} ({$columnaNombre}) VALUES (?)");
        $stmt->execute([$valor]);
        return (int)$pdo->lastInsertId();
    }

    private function guardarRelaciones(PDO $pdo, int $idActividad, array $d): void {
        // Nivel de impacto (texto -> id)
        if (!empty($d['nivel_impacto'])) {
            $idImpacto = $this->buscarOCrearId($pdo, 'nivel_impacto', 'nombre_impacto', $d['nivel_impacto']);
            if ($idImpacto) {
                $pdo->prepare("INSERT INTO impacto_actividad (id_actividad, id_impacto) VALUES (?, ?)")
                    ->execute([$idActividad, $idImpacto]);
            }
        }

        // Comuna (texto -> id)
        if (!empty($d['comuna'])) {
            $idComuna = $this->buscarOCrearId($pdo, 'comuna', 'nombre', $d['comuna']);
            if ($idComuna) {
                $pdo->prepare("INSERT INTO actividad_comuna (id_actividad, id_comuna) VALUES (?, ?)")
                    ->execute([$idActividad, $idComuna]);
            }
        }

        // NOTA: espacio_cultural queda pendiente hasta corregir su tabla
        // (no tiene columna `id` y actividad_espaciocultural usa id_biblioteca
        // en vez de id_espacio_cultural).

        // Responsable (relación directa, no requiere búsqueda)
        if (!empty($d['responsable'])) {
            $pdo->prepare("INSERT INTO responsable (id_actividad, nombre, telefono) VALUES (?, ?, ?)")
                ->execute([$idActividad, $d['responsable'], $d['telefono_responsable'] ?? null]);
        }
    }
}
?>
