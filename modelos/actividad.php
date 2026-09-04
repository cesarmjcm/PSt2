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
                a.hora,
                a.dia_semana,
                a.estado,
                a.id_biblioteca,
                a.id_espacio_cultural,
                a.id_tipo_actividad,
                ta.nombre AS tipo_actividad,
                b.nombre AS biblioteca,
                m.id AS municipio_id,
                m.nombre AS municipio,
                p.nombre AS parroquia,
                GROUP_CONCAT(DISTINCT ni.nombre_impacto SEPARATOR ', ') AS nivel_impacto,
                GROUP_CONCAT(DISTINCT co.nombre SEPARATOR ', ') AS comuna,
                GROUP_CONCAT(DISTINCT r.nombre SEPARATOR ', ') AS responsable,
                GROUP_CONCAT(DISTINCT r.telefono SEPARATOR ', ') AS telefono_responsable
            FROM actividad a
            LEFT JOIN tipo_actividad ta ON ta.id = a.id_tipo_actividad
            LEFT JOIN biblioteca b ON b.id = a.id_biblioteca
            LEFT JOIN parroquia p ON p.id = b.id_parroquia
            LEFT JOIN municipio m ON m.id = p.id_municipio
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

        $estadosValidos = ['confirmada', 'ejecutada', 'cancelada'];
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

    
    
    
    
    public function crearActividad($nombre, $descripcion, $objetivo, $participantes, $fecha, $dia_semana, $id_biblioteca, $estado = 'confirmada') {
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

            $sql = "INSERT INTO actividad (nombre, descripcion, objetivo, participantes, fecha, hora, dia_semana, id_biblioteca, id_espacio_cultural, id_tipo_actividad, estado)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $d['nombre'], $d['descripcion'], $d['objetivo'],
                $d['participantes'], $d['fecha'], $d['hora'] ?: null, $d['dia_semana'], $idBiblioteca,
                $idEspacioCultural, $d['id_tipo_actividad'], $d['estado'] ?? 'confirmada'
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

    
    public function actualizarActividadCompleta(int $id, array $d) {
        $pdo = Conexion::conectar();
        $pdo->beginTransaction();
        try {
            
            
            $idBiblioteca = !empty($d['id_biblioteca']) ? (int)$d['id_biblioteca'] : null;
            $idEspacioCultural = !empty($d['id_espacio_cultural']) ? (int)$d['id_espacio_cultural'] : null;

            $sql = "UPDATE actividad SET nombre=?, descripcion=?, objetivo=?, participantes=?, fecha=?, hora=?, dia_semana=?, id_biblioteca=?, id_espacio_cultural=?, id_tipo_actividad=?, estado=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $d['nombre'], $d['descripcion'], $d['objetivo'],
                $d['participantes'], $d['fecha'], $d['hora'] ?: null, $d['dia_semana'], $idBiblioteca,
                $idEspacioCultural, $d['id_tipo_actividad'], $d['estado'] ?? 'confirmada', $id
            ]);

            
            
            
            
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

    
    private function buscarOCrearId(PDO $pdo, string $tabla, string $columnaNombre, string $valor): ?int {
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

    private function guardarRelaciones(PDO $pdo, int $idActividad, array $d): void {
        
        if (!empty($d['nivel_impacto'])) {
            $idImpacto = $this->buscarOCrearId($pdo, 'nivel_impacto', 'nombre_impacto', $d['nivel_impacto']);
            if ($idImpacto) {
                $pdo->prepare("INSERT INTO impacto_actividad (id_actividad, id_impacto) VALUES (?, ?)")
                    ->execute([$idActividad, $idImpacto]);
            }
        }

        
        if (!empty($d['comuna'])) {
            $idComuna = $this->buscarOCrearId($pdo, 'comuna', 'nombre', $d['comuna']);
            if ($idComuna) {
                $pdo->prepare("INSERT INTO actividad_comuna (id_actividad, id_comuna) VALUES (?, ?)")
                    ->execute([$idActividad, $idComuna]);
            }
        }

        
        
        

        
        if (!empty($d['responsable'])) {
            $pdo->prepare("INSERT INTO responsable (id_actividad, nombre, telefono) VALUES (?, ?, ?)")
                ->execute([$idActividad, $d['responsable'], $d['telefono_responsable'] ?? null]);
        }
    }
}
?>
