<?php
$actividades = [];
$errorMessage = '';
$totalActividades = 0;
$totalParticipantes = 0;
$municipiosActivos = [];

try {
    require_once __DIR__ . '/../modelos/actividad.php';
    $actividadModel = new Actividad();
    // mostrarActividadesCompletas() trae además municipio, parroquia, comuna,
    // espacio cultural, nivel de impacto y responsable vía JOIN.
    $actividades = $actividadModel->mostrarActividadesCompletas();
    $totalActividades = count($actividades);

    foreach ($actividades as $actividad) {
        $totalParticipantes += intval($actividad['participantes'] ?? 0);
        if (!empty($actividad['municipio'])) {
            $municipiosActivos[$actividad['municipio']] = true;
        }
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
}
?>

<div class="tabla__container">
                <h2 class="section-title">Cronograma Semanal de Actividades</h2>
                <table class="tabla-planificacion">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Actividad</th>
                            <th>descripcion</th>
                            <th>fecha</th>
                            <th>dia semana</th>
                            <th class="col-acciones">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($errorMessage)): ?>
                            <tr>
                                <td colspan="8">Error: <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                        <?php elseif (empty($actividades)): ?>
                            <tr>
                                <td colspan="8">No hay actividades registradas.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($actividades as $index => $actividad): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>

                                    <td><strong><?php echo htmlspecialchars($actividad['nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?></strong></td>

                                    <td><?php echo htmlspecialchars($actividad['descripcion'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>

                                    <td>
                                        <?php
                                            $fecha = $actividad['fecha'] ?? '';
                                            $fechaFmt = '';
                                            try {
                                                $fechaFmt = $fecha ? (new DateTime($fecha))->format('d/m/Y') : '';
                                            } catch (Exception $ex) {
                                                $fechaFmt = $fecha;
                                            }
                                            echo htmlspecialchars($fechaFmt, ENT_QUOTES, 'UTF-8');
                                        ?>
                                    </td>

                                    <td><?php echo htmlspecialchars($actividad['dia_semana'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>

                                    <td class="col-acciones">
                                        <button type="button"
                                                class="btn-icon btn-edit-actividad"
                                                title="Editar"
                                                data-id="<?php echo htmlspecialchars($actividad['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                                data-nombre="<?php echo htmlspecialchars($actividad['nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-descripcion="<?php echo htmlspecialchars($actividad['descripcion'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-participantes="<?php echo htmlspecialchars($actividad['participantes'] ?? '0', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-fecha="<?php echo htmlspecialchars($actividad['fecha'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-dia="<?php echo htmlspecialchars($actividad['dia_semana'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-nivel-impacto="<?php echo htmlspecialchars($actividad['nivel_impacto'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-municipio-id="<?php echo htmlspecialchars($actividad['municipio_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-parroquia="<?php echo htmlspecialchars($actividad['parroquia'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-comuna="<?php echo htmlspecialchars($actividad['comuna'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-espacio="<?php echo htmlspecialchars($actividad['espacio_cultural'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-id-biblioteca="<?php echo htmlspecialchars($actividad['id_biblioteca'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-responsable="<?php echo htmlspecialchars($actividad['responsable'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-telefono="<?php echo htmlspecialchars($actividad['telefono_responsable'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="fas fa-pen"></i>
                                        </button>

                                        <form action="../controladores/actividad_contr.php" method="POST" class="form-eliminar-actividad" style="display:inline;">
                                            <input type="hidden" name="action" value="eliminar">
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($actividad['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <button type="submit" class="btn-icon btn-delete" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>