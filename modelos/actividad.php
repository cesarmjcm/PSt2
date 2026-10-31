<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../helpers/validador.php';

class Actividad {

    private function condicionBiblioteca($idBiblioteca, string $alias = 'a'): string
    {
        if ($idBiblioteca === null) {
            return '';
        }

        return "{$alias}.id_biblioteca = ?";
    }

    public function mostrarActividades(int $idBiblioteca = null) {
        $sql = "SELECT a.* FROM actividad a" .
            ($idBiblioteca !== null ? " WHERE " . $this->condicionBiblioteca($idBiblioteca) : "") .
            " ORDER BY a.fecha DESC";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($idBiblioteca !== null ? [$idBiblioteca] : []);
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
                
            }
        }
        return $actividad;
    }

    public function mostrarActividadesCompletas(int $idBiblioteca = null) {
        
        
        
        
        
        
        $sql = "
            SELECT
                a.id,
                a.nombre,
                a.descripcion,
                a.objetivo,
                a.participantes,
                a.fecha,
                a.hora,
                a.dia_semana,
                a.estado,
                a.id_biblioteca,
                a.id_espacio_cultural,
                a.id_tipo_actividad,
                CONCAT(er.nombre, ' ', er.apellido) AS responsable,
                a.responsable AS id_empleado,
                a.telefono_responsable,
                ta.nombre AS tipo_actividad,
                b.nombre AS biblioteca,
                m.id AS municipio_id,
                m.nombre AS municipio,
                p.nombre AS parroquia,
                ni.nombre_impacto AS nivel_impacto,
                NULL AS comuna
            FROM actividad a
            LEFT JOIN tipo_actividad ta ON ta.id = a.id_tipo_actividad
            LEFT JOIN biblioteca b ON b.id = a.id_biblioteca
            LEFT JOIN parroquia p ON p.id = b.id_parroquia
            LEFT JOIN municipio m ON m.id = p.id_municipio
            LEFT JOIN nivel_impacto ni ON ni.id = a.id_nivel_impacto
            LEFT JOIN empleado er ON er.id = a.responsable
            " . ($idBiblioteca !== null ? "WHERE " . $this->condicionBiblioteca($idBiblioteca) : "") . "
            GROUP BY a.id
            ORDER BY a.fecha DESC
        ";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($idBiblioteca !== null ? [$idBiblioteca] : []);
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

        
        
        
        
        $objetivo = trim($d['objetivo'] ?? '');
        if ($objetivo !== '' && $objetivo !== 'No definido' && !Validador::esTextoValido($objetivo, 2, 50)) {
            $errors[] = 'Objetivo inválido.';
        }

        if (!Validador::esEnteroNoNegativo($d['participantes'] ?? 0, 99999)) {
            $errors[] = 'Cantidad de participantes inválida.';
        }

        $nivelImpacto = trim($d['nivel_impacto'] ?? '');
        if ($nivelImpacto !== '' && !Validador::esTextoValido($nivelImpacto, 2, 20)) {
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

        $estadosValidos = ['pendiente', 'confirmada', 'ejecutada', 'cancelada'];
        $estado = trim($d['estado'] ?? '');
        if (!in_array($estado, $estadosValidos, true)) {
            $errors[] = 'Estado de la actividad inválido.';
        }

        
        
        
        
        
        
        
        $tipoUbicacion = trim($d['tipo_ubicacion'] ?? '');
        $esBiblioteca = $tipoUbicacion === 'biblioteca';
        $esEspacio = $tipoUbicacion === 'espacio';

        if (!$esBiblioteca && !$esEspacio) {
            $errors[] = 'Debe indicar si la actividad se realiza en una biblioteca o en un espacio cultural.';
        } elseif ($esBiblioteca) {
            if (!Validador::esEnteroPositivo($d['id_biblioteca'] ?? 0)) {
                $errors[] = 'Biblioteca inválida.';
            }
        } elseif ($esEspacio) {
            if (!Validador::esEnteroPositivo($d['id_espacio_cultural'] ?? 0)) {
                $errors[] = 'Espacio cultural inválido.';
            }
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

        if (!Validador::esEnteroPositivo($d['id_tipo_actividad'] ?? 0)) {
            $errors[] = 'Tipo de actividad inválido.';
        }

        $responsable = trim($d['responsable'] ?? '');
        if ($responsable !== '' && !ctype_digit($responsable)) {
            $errors[] = 'El responsable seleccionado no es válido.';
        }

        $telefonoResponsable = trim($d['telefono_responsable'] ?? '');
        if ($telefonoResponsable !== '' && !preg_match('/^[0-9\-\+ ]{7,15}$/', $telefonoResponsable)) {
            $errors[] = 'El teléfono del responsable debe tener entre 7 y 15 caracteres y solo puede contener números, espacios, guiones o el signo +.';
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

    public function obtenerActividadPorId(int $id, int $idBiblioteca = null) {
        $sql = "SELECT a.* FROM actividad a WHERE a.id = ?" .
            ($idBiblioteca !== null ? " AND " . $this->condicionBiblioteca($idBiblioteca) : "");
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($idBiblioteca !== null ? [$id, $idBiblioteca] : [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verifica si ya existe otra actividad registrada el mismo día, a la
     * misma hora, en la misma biblioteca o en el mismo espacio cultural
     * (según cuál de los dos tenga la actividad que se está validando).
     * Las actividades canceladas no cuentan como choque, ya que ese cupo
     * quedó liberado.
     *
     * @param array    $d         Datos de la actividad (fecha, hora, id_biblioteca, id_espacio_cultural).
     * @param int|null $idExcluir ID de la actividad actual, para no compararla consigo misma al editar.
     * @return bool True si hay conflicto de horario/lugar.
     */
    public function existeConflictoHorario(array $d, int $idExcluir = null): bool
    {
        $fecha = trim($d['fecha'] ?? '');
        $hora = trim($d['hora'] ?? '');
        if ($fecha === '' || $hora === '') {
            // Sin hora no hay forma de comparar un choque de horario.
            return false;
        }

        $idBiblioteca = !empty($d['id_biblioteca']) ? (int)$d['id_biblioteca'] : 0;
        $idEspacio = !empty($d['id_espacio_cultural']) ? (int)$d['id_espacio_cultural'] : 0;
        if ($idBiblioteca === 0 && $idEspacio === 0) {
            return false;
        }

        $sql = "SELECT id FROM actividad
                WHERE fecha = ? AND hora = ? AND estado != 'cancelada'";
        $params = [$fecha, $hora];

        if ($idBiblioteca > 0) {
            $sql .= " AND id_biblioteca = ?";
            $params[] = $idBiblioteca;
        } else {
            $sql .= " AND id_espacio_cultural = ?";
            $params[] = $idEspacio;
        }

        if ($idExcluir !== null) {
            $sql .= " AND id != ?";
            $params[] = $idExcluir;
        }
        $sql .= " LIMIT 1";

        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    
    
    
    public function crearActividad($nombre, $descripcion, $objetivo, $participantes, $fecha, $dia_semana, $id_biblioteca, $estado = 'pendiente') {
        $sql = "INSERT INTO actividad (nombre, descripcion, objetivo, participantes, fecha, dia_semana, id_biblioteca, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $descripcion, $objetivo, $participantes, $fecha, $dia_semana, $id_biblioteca ?: null, $estado]);
    }

    
    public function actualizarActividad($id, $nombre, $descripcion, $objetivo, $participantes, $fecha, $dia_semana, $id_biblioteca, $estado = 'confirmada') {
        $sql = "UPDATE actividad SET nombre = ?, descripcion = ?, objetivo = ?, participantes = ?, fecha = ?, dia_semana = ?, id_biblioteca = ?, estado = ? WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$nombre, $descripcion, $objetivo, $participantes, $fecha, $dia_semana, $id_biblioteca ?: null, $estado, $id]);
    }

    public function eliminarActividad(int $id) {
        $sql = "DELETE FROM actividad WHERE id = ?";
        $stmt = Conexion::conectar()->prepare($sql);
        return $stmt->execute([$id]);
    }

    
    public function crearActividadCompleta(array $d) {
        $pdo = Conexion::conectar();
        $pdo->beginTransaction();
        try {
            
            
            
            $idBiblioteca = !empty($d['id_biblioteca']) ? (int)$d['id_biblioteca'] : null;
            $idEspacioCultural = !empty($d['id_espacio_cultural']) ? (int)$d['id_espacio_cultural'] : null;
            $idNivel = !empty($d['nivel_impacto']) ? $this->buscarOCrearId($pdo, 'nivel_impacto', 'nombre_impacto', $d['nivel_impacto']) : null;
            $sql = "INSERT INTO actividad (nombre, descripcion, objetivo, participantes, fecha, hora, dia_semana, id_biblioteca, id_espacio_cultural, id_tipo_actividad, estado, responsable, telefono_responsable, id_nivel_impacto)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $d['nombre'], $d['descripcion'], $d['objetivo'],
                $d['participantes'], $d['fecha'], $d['hora'] ?: null, $d['dia_semana'], $idBiblioteca,
                $idEspacioCultural, $d['id_tipo_actividad'], $d['estado'] ?? 'pendiente',
                $d['responsable'] ?: null, $d['telefono_responsable'] ?: null, $idNivel
            ]);
            $idActividad = (int)$pdo->lastInsertId();

            $pdo->commit();
            return $idActividad;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    
    public function actualizarActividadCompleta(int $id, array $d) {
        $pdo = Conexion::conectar();
        $pdo->beginTransaction();
        try {
            
            
            $idBiblioteca = !empty($d['id_biblioteca']) ? (int)$d['id_biblioteca'] : null;
            $idEspacioCultural = !empty($d['id_espacio_cultural']) ? (int)$d['id_espacio_cultural'] : null;
            $idNivel = !empty($d['nivel_impacto']) ? $this->buscarOCrearId($pdo, 'nivel_impacto', 'nombre_impacto', $d['nivel_impacto']) : null;
            $sql = "UPDATE actividad SET nombre=?, descripcion=?, objetivo=?, participantes=?, fecha=?, hora=?, dia_semana=?, id_biblioteca=?, id_espacio_cultural=?, id_tipo_actividad=?, estado=?, responsable=?, telefono_responsable=?, id_nivel_impacto=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $d['nombre'], $d['descripcion'], $d['objetivo'],
                $d['participantes'], $d['fecha'], $d['hora'] ?: null, $d['dia_semana'], $idBiblioteca,
                $idEspacioCultural, $d['id_tipo_actividad'], $d['estado'] ?? 'pendiente',
                $d['responsable'] ?: null, $d['telefono_responsable'] ?: null, $idNivel, $id
            ]);

            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    
    private function buscarOCrearId(PDO $pdo, string $tabla, string $columnaNombre, string $valor) {
        $valor = Validador::normalizarTexto($valor);
        if ($valor === '') return null;

        $stmt = $pdo->prepare("SELECT id FROM {$tabla} WHERE {$columnaNombre} = ? LIMIT 1");
        $stmt->execute([$valor]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) return (int)$row['id'];

        $stmt = $pdo->prepare("INSERT INTO {$tabla} ({$columnaNombre}) VALUES (?)");
        $stmt->execute([$valor]);
        return (int)$pdo->lastInsertId();
    }

}
?>
