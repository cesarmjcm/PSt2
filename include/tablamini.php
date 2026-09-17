<?php
$actividades = [];
$errorMessage = '';
$totalActividades = 0;
$totalParticipantes = 0;
$municipiosActivos = [];
$busqueda = trim((string)($_GET['q'] ?? ''));

try {
    require_once __DIR__ . '/../modelos/actividad.php';
    $actividadModel = new Actividad();
    
    
    $actividades = $actividadModel->mostrarActividadesCompletas();
    if ($busqueda !== '') {
        $actividades = array_values(array_filter($actividades, static function (array $actividad) use ($busqueda): bool {
            $textoActividad = implode(' ', array_map('strval', $actividad));
            return stripos($textoActividad, $busqueda) !== false;
        }));
    }
    $totalActividades = count($actividades);
    $actividadesPorPagina = 10;
    $paginaActual = max(1, intval($_GET['page'] ?? 1));
    $totalPaginas = max(1, (int)ceil($totalActividades / $actividadesPorPagina));
    $paginaActual = min($paginaActual, $totalPaginas);
    $actividades = array_slice($actividades, ($paginaActual - 1) * $actividadesPorPagina, $actividadesPorPagina);

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

<style>
    .badge-estado {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: capitalize;
        white-space: nowrap;
    }
    .badge-estado--confirmada {
        background: #e0edff;
        color: #1d4ed8;
    }
    .badge-estado--ejecutada {
        background: #dcfce7;
        color: #15803d;
    }
    .badge-estado--cancelada {
        background: #fee2e2;
        color: #b91c1c;
    }
</style>
<div class="tabla__container">
                <div class="tabla__header">
                    <h2 class="section-title">Cronograma Semanal de Actividades</h2>
                    <form class="tabla-buscador" style="margin-top: 10px;" method="get" role="search">
                        <label class="sr-only" for="buscarActividad">Buscar actividad</label>
                        <i class="fas fa-search" aria-hidden="true"></i>
                        <input  type="search"
                               id="buscarActividad"
                               name="q"
                               value="<?php echo htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8'); ?>"
                               placeholder="Buscar actividad..."
                               autocomplete="off">
                        <?php if ($busqueda !== ''): ?>
                            <a href="index.php" class="tabla-buscador__limpiar" title="Limpiar búsqueda" aria-label="Limpiar búsqueda">
                                <i class="fas fa-times"></i>
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
                <table class="tabla-planificacion">
                    <thead>
                        <tr>
                            <th class="col-index">N°</th>
                            <th class="col-actividad">Actividad</th>
                            <th class="col-estado">Estado</th>
                            <th class="col-fecha">Fecha</th>
                            <th class="col-dia">Día Semana</th>
                            <th class="col-acciones">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($errorMessage)): ?>
                            <tr>
                                <td colspan="6">Error: <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                        <?php elseif (empty($actividades)): ?>
                            <tr>
                                <td colspan="6"><?php echo $busqueda !== '' ? 'No se encontraron actividades para esa búsqueda.' : 'No hay actividades registradas.'; ?></td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($actividades as $index => $actividad): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>

                                    <td><strong><?php echo htmlspecialchars($actividad['nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?></strong></td>

                                    <td>
                                        <?php
                                            $estadoActividad = strtolower(trim((string)($actividad['estado'] ?? 'confirmada')));
                                            $estadoLabels = [
                                                'confirmada' => 'Confirmada',
                                                'ejecutada'  => 'Ejecutada',
                                                'cancelada'  => 'Cancelada',
                                            ];
                                            $estadoLabel = $estadoLabels[$estadoActividad] ?? ucfirst($estadoActividad);
                                        ?>
                                        <span class="badge-estado badge-estado--<?php echo htmlspecialchars($estadoActividad, ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php echo htmlspecialchars($estadoLabel, ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </td>

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
                                        
                                        <div class="acciones-grupo">
                                            <button type="button"
                                                    class="btn-icon btn-ver-mas"
                                                    title="Ver más"
                                                    aria-expanded="false"
                                                    data-target="detalle-actividad-<?php echo htmlspecialchars($actividad['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                                <i class="fas fa-chevron-down"></i>
                                            </button>
                                            <?php if (($_SESSION['user_rol'] ?? '') === 'administrador'): ?>
                                        <button type="button"
                                                class="btn-icon btn-edit-actividad"
                                                title="Editar"
                                                data-id="<?php echo htmlspecialchars($actividad['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                                data-nombre="<?php echo htmlspecialchars($actividad['nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-descripcion="<?php echo htmlspecialchars($actividad['descripcion'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-objetivo="<?php echo htmlspecialchars($actividad['objetivo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-participantes="<?php echo htmlspecialchars($actividad['participantes'] ?? '0', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-fecha="<?php echo htmlspecialchars($actividad['fecha'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-hora="<?php echo htmlspecialchars(substr((string)($actividad['hora'] ?? ''), 0, 5), ENT_QUOTES, 'UTF-8'); ?>"
                                                data-dia="<?php echo htmlspecialchars($actividad['dia_semana'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-estado="<?php echo htmlspecialchars($actividad['estado'] ?? 'confirmada', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-nivel-impacto="<?php echo htmlspecialchars($actividad['nivel_impacto'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-municipio-id="<?php echo htmlspecialchars($actividad['municipio_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-parroquia="<?php echo htmlspecialchars($actividad['parroquia'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-comuna="<?php echo htmlspecialchars($actividad['comuna'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-espacio="<?php echo htmlspecialchars($actividad['espacio_cultural'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-id-biblioteca="<?php echo htmlspecialchars($actividad['id_biblioteca'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-id-tipo-actividad="<?php echo htmlspecialchars((string)($actividad['id_tipo_actividad'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                                                data-responsable="<?php echo htmlspecialchars($actividad['responsable'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-telefono="<?php echo htmlspecialchars($actividad['telefono_responsable'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <?php endif; ?>
                                                <?php if (($_SESSION['user_rol'] ?? '') === 'administrador'): ?>
                                        <form action="../controladores/actividad_contr.php" method="POST" class="form-eliminar-actividad" style="display:inline;">
                                            <input type="hidden" name="action" value="eliminar">
                                           
                                            
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($actividad['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <button type="submit" class="btn-icon btn-delete" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                    
                                        </form>
                                        <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                
                                <tr class="fila-detalle"
                                    id="detalle-actividad-<?php echo htmlspecialchars($actividad['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                    style="display:none;">
                                    <td colspan="6">
                                        <div class="detalle-actividad">
                                            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($actividad['descripcion'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                                            <p><strong>Estado:</strong> <?php echo htmlspecialchars($estadoLabel, ENT_QUOTES, 'UTF-8'); ?></p>
                                            <p><strong>Fecha:</strong> <?php echo htmlspecialchars($fechaFmt, ENT_QUOTES, 'UTF-8'); ?></p>
                                            <p><strong>Hora:</strong> <?php echo htmlspecialchars(substr((string)($actividad['hora'] ?? ''), 0, 5), ENT_QUOTES, 'UTF-8'); ?></p>
                                            <p><strong>Objetivo:</strong> <?php echo htmlspecialchars($actividad['objetivo'] ?? 'No definido', ENT_QUOTES, 'UTF-8'); ?></p>
                                            <p><strong>Tipo de actividad:</strong> <?php echo htmlspecialchars($actividad['tipo_actividad'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                                            <p><strong>Participantes:</strong> <?php echo htmlspecialchars($actividad['participantes'] ?? '0', ENT_QUOTES, 'UTF-8'); ?></p>
                                            <p><strong>Nivel de impacto:</strong> <?php echo htmlspecialchars($actividad['nivel_impacto'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                                            <p><strong>Municipio:</strong> <?php echo htmlspecialchars($actividad['municipio'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                                            <p><strong>Parroquia:</strong> <?php echo htmlspecialchars($actividad['parroquia'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                                            <p><strong>Comuna:</strong> <?php echo htmlspecialchars($actividad['comuna'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                                            <p><strong>Espacio cultural:</strong> <?php echo htmlspecialchars($actividad['espacio_cultural'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                                            <p><strong>Biblioteca:</strong> <?php echo htmlspecialchars($actividad['biblioteca'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                                            <p><strong>Responsable:</strong> <?php echo htmlspecialchars($actividad['responsable'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                                            <p><strong>Teléfono responsable:</strong> <?php echo htmlspecialchars($actividad['telefono_responsable'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php if ($totalPaginas > 1): ?>
                    <nav class="paginacion" aria-label="Páginas de actividades">
                        <?php for ($pagina = 1; $pagina <= $totalPaginas; $pagina++): ?>
                            <a href="?page=<?php echo $pagina; ?>&amp;q=<?php echo urlencode($busqueda); ?>"
                               class="paginacion__pagina<?php echo $pagina === $paginaActual ? ' activa' : ''; ?>"
                               <?php echo $pagina === $paginaActual ? 'aria-current="page"' : ''; ?>>
                                <?php echo $pagina; ?>
                            </a>
                        <?php endfor; ?>
                    </nav>
                <?php endif; ?>
                
            </div>