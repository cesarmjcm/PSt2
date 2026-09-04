<?php
$actividades = [];
$errorMessage = '';
$totalActividades = 0;
$totalParticipantes = 0;
$municipiosActivos = [];

$actividadesMesActual = 0;
$actividadesMesAnterior = 0;
$mesActual = (int) date('n');
$anioActual = (int) date('Y');
$mesAnteriorNum = $mesActual === 1 ? 12 : $mesActual - 1;
$anioMesAnterior = $mesActual === 1 ? $anioActual - 1 : $anioActual;

$metaParticipantes = 2000;
try {
    require_once __DIR__ . '/../modelos/actividad.php';
    $actividadModel = new Actividad();
   
    $actividades = $actividadModel->mostrarActividadesCompletas();
    $totalActividades = count($actividades);

    foreach ($actividades as $actividad) {
        $totalParticipantes += intval($actividad['participantes'] ?? 0);
        if (!empty($actividad['municipio'])) {
            $municipiosActivos[$actividad['municipio']] = true;
        }
        if (!empty($actividad['fecha'])) {
            $timestamp = strtotime($actividad['fecha']);
            $mesActividad = (int) date('n', $timestamp);
            $anioActividad = (int) date('Y', $timestamp);
    
            if ($mesActividad === $mesActual && $anioActividad === $anioActual) {
                $actividadesMesActual++;
            } elseif ($mesActividad === $mesAnteriorNum && $anioActividad === $anioMesAnterior) {
                $actividadesMesAnterior++;
            }
        }
    }
    }
 catch (Exception $e) {
    $errorMessage = $e->getMessage();
}
if($actividadesMesAnterior > 0){
    $porcentajeactividades = (($actividadesMesActual - $actividadesMesAnterior) / $actividadesMesAnterior) * 100;
}
else{
    $porcentajeactividades = $actividadesMesActual > 0 ? 100 : 0;
}
$signoActividades = $porcentajeactividades >= 0 ? '+' : '';


$porcentajeParticipantes = $metaParticipantes > 0
    ? ($totalParticipantes / $metaParticipantes) * 100
    : 0;
 ?>

<main class="dashboard-container">
        <h1 class="dashboard__title">Panel de Control: Red de Bibliotecas</h1>


        <section class="metrics-grid">
            <div class="metric-card">
                <div class="metric-info">
                    <h3>Total Actividades</h3>
                    <p class="metric-value"><?php echo $totalActividades > 0 ? $totalActividades : '-'; ?></p>
                    <span class="metric-delta"><?php echo $signoActividades . number_format($porcentajeactividades, 2) . '%'; ?> mas que el mes anterior</span>
                </div>
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="metric-card">
                <div class="metric-info">
                    <h3>Participantes</h3>
                    <p class="metric-value"><?php echo $totalParticipantes > 0 ? $totalParticipantes : '-'; ?></p>
                    <span class="metric-delta">Meta: <?php echo number_format($metaParticipantes, 0, ',', '.'); ?> (<?php echo number_format($porcentajeParticipantes, 1); ?>%)</span>
                </div>
                <i class="fas fa-users"></i>
            </div>
            <div class="metric-card">
                <div class="metric-info">
                    <h3>Municipios Activos</h3>
                    <p class="metric-value"><?php echo count($municipiosActivos) > 0 ? count($municipiosActivos) : '-'; ?></p>
                    <span class="metric-delta">Cobertura Total</span>
                </div>
                <i class="fas fa-map-marker-alt"></i>
            </div>
        </section>


        <div class="content-wrapper">
            <div class="actions-bar">
                <button type="button" class="btn-primary" id="btnNuevaActividad"><i class="fas fa-plus"></i> Nueva Actividad</button>
                <div class="search-box">

            </div>

            
        </div>
    </main>

<script>
(function () {
    document.querySelectorAll('.form-eliminar-actividad').forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!confirm('¿Estás seguro de que deseas eliminar esta actividad? Esta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    });
})();
</script>
