<?php
require_once __DIR__ . '/../modelos/municipio.php';
require_once __DIR__ . '/../modelos/comuna.php';
require_once __DIR__ . '/../modelos/parroquia.php';
require_once __DIR__ . '/../modelos/espacio.php';
require_once __DIR__ . '/../modelos/empleado.php';
require_once __DIR__ . '/../modelos/biblioteca.php';
require_once __DIR__ . '/../modelos/tipo_actividad.php';

$municipioModel = new Municipio();
$comunaModel = new Comuna();
$parroquiaModel = new Parroquia();
$espacioModel = new Espacio();
$empleadoModel = new Empleado();
$bibliotecaModel = new Biblioteca();
$tipoActividadModel = new TipoActividad();

$municipios = $municipioModel->mostrarMunicipios();
$comunas = $comunaModel->mostrarComunas();
$parroquias = $parroquiaModel->mostrarParroquias();
$espacios = $espacioModel->mostrarEspacios();
$empleados = $empleadoModel->mostrarEmpleados();
$bibliotecas = $bibliotecaModel->mostrarBibliotecas();
$tiposActividad = $tipoActividadModel->mostrarTipos();
$empleado=$empleadoModel->mostrarEmpleados();
?>

<section>

    
    <div id="modalPlanificacion" class="modal" style="display:none;">
        <div class="modal-content-wrapper">
            <section class="formulario-planificacion">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                    <h2 class="section-title" id="planModalTitulo" style="margin: 0;">Nueva Planificación de Actividad</h2>
                    <span class="close-button" style="cursor:pointer; font-size: 1.5rem;">&times;</span>
                </div>
                <div class="container__planificacion">
                    <form id="form-planificacion" action="../controladores/actividad_contr.php?action=crear" method="post" onsubmit="return validacionesformulario(this)" novalidate>
                        <input type="hidden" name="action" id="plan-action" value="crear">
                        <input type="hidden" name="id" id="actividad-id" value="">
                        <input type="hidden" id="plan-dia" name="dia_semana" value="">
                        <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'main2.php', ENT_QUOTES, 'UTF-8'); ?>">

                        <fieldset class="tipo-formulario-selector" style="border:none; padding:0; margin:0 0 16px; display:flex; gap:24px;">
                            <legend style="font-weight:600; margin-bottom:6px; padding:0;">Tipo de planificación</legend>
                            <label style="display:flex; align-items:center; gap:6px; font-weight:normal;">
                                <input type="radio" name="tipo_formulario" id="tipo-formulario-simple" value="simple" checked>
                                Actividad simple
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; font-weight:normal;">
                                <input type="radio" name="tipo_formulario" id="tipo-formulario-completa" value="completa">
                                Actividad completa
                            </label>
                        </fieldset>

                        <div id="form-planificacion-aviso" class="form-aviso" style="min-height: 2.75em; margin: 0 0 12px; box-sizing: border-box; visibility: hidden;"></div>

                        <div class="planificacion-grid">
                            <fieldset class="planificacion-group">
                                <fieldset>
                                    <label for="plan-tipo">Nombre de la actividad</label>
                                    <input type="text" id="plan-tipo" maxlength="30" name="nombre" placeholder="Nombre de la actividad">

                                    <div id="fields-hidden" class="hidden">
                                        <label for="plan-descripcion">Descripción de la actividad</label>
                                        <textarea id="plan-descripcion" maxlength="200" name="descripcion" placeholder="Descripción breve"></textarea>
                                    </div>
                                </fieldset>

                                <fieldset>
                                    <label for="plan-fecha">Fecha de la actividad</label>
                                    <input type="date" id="plan-fecha" name="fecha" placeholder="Ej. 20/04/2026">

                                    <label for="plan-hora">Hora de la actividad</label>
                                    <input type="time" id="plan-hora" name="horaActividad" placeholder="Ej. 09:00">
                                </fieldset>

                                <fieldset id="campos-completa" class="hidden">
                                    <legend>Detalles de actividad completa</legend>

                                    <label for="plan-objetivo">Objetivo / enfoque</label>
                                    <input type="text" id="plan-objetivo" maxlength="50" name="objetivo" placeholder="Objetivo de la actividad">

                                    <label for="plan-participantes">Cantidad de participantes</label>
                                    <input type="number" id="plan-participantes" name="participantes" min="0" max="99999" placeholder="Ej. 25">

                                    <label for="plan-nivel-impacto">Nivel de impacto</label>
                                    <input type="text" id="plan-nivel-impacto" maxlength="20" name="nivel_impacto" placeholder="Ej. Comunal, Regional, Nacional">
                                </fieldset>
                            </fieldset>

                            <fieldset class="planificacion-group">
                                <fieldset>
                                    <label for="plan-municipio">Municipio</label>
                                    <select name="municipio_id" id="planificacion-municipios">
                                        <option value="">Seleccione un municipio</option>
                                        <?php foreach ($municipios as $m): ?>
                                            <option value="<?= htmlspecialchars($m['id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($m['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <div id="municipio-hidden" class="hidden">
                                        <fieldset>
                                            <label for="plan-parroquia">Parroquia</label>
                                            <input list="planificacion-parroquias" id="plan-parroquia" name="parroquia" maxlength="30" placeholder="Seleccione una parroquia">
                                            <datalist size="5" name="parroquia" id="planificacion-parroquias">
                                                <option value="">Seleccione una parroquia</option>
                                                <?php foreach ($parroquias as $p): ?>
                                                    <option value="<?= htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                                                <?php endforeach; ?>
                                            </datalist>
                                        </fieldset>

                                        <fieldset>
                                            <label for="plan-comuna">Comuna</label>
                                            <select name="comuna" id="planificacion-comunas">
                                                <option value="">Seleccione una comuna</option>
                                                <?php foreach ($comunas as $c): ?>
                                                    <option value="<?= htmlspecialchars($c['nombre'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($c['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </fieldset>

                                        <fieldset>
                                            <label for="plan-biblioteca">Biblioteca</label>
                                            <select name="id_biblioteca" id="plan-biblioteca">
                                                <option value="">Seleccione una biblioteca</option>
                                                <?php foreach ($bibliotecas as $b): ?>
                                                    <option value="<?= htmlspecialchars($b['id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($b['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                            <label for="plan-espacio">Espacio cultural</label>
                                            <select name="id_espacio_cultural" id="plan-espacio">
                                                <option value="">Seleccione un espacio cultural</option>
                                                <?php foreach ($espacios as $e): ?>
                                                    <option value="<?= htmlspecialchars($e['id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($e['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </fieldset>
                                    </div>

                                    <fieldset>
                                        <label for="plan-tipo-actividad">Tipo de actividad</label>
                                        <select name="id_tipo_actividad" id="plan-tipo-actividad">
                                            <option value="">Seleccione un tipo de actividad</option>
                                            <?php foreach ($tiposActividad as $t): ?>
                                                <option value="<?= htmlspecialchars($t['id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($t['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </fieldset>

                                    <fieldset>
                                        <legend>Responsable</legend>
                                        <label for="plan-responsable">Nombre</label>
                                        <select name="responsable" id="plan-responsable">
                                            <option value="">Seleccione un responsable</option>
                                            <?php foreach ($empleados as $e): ?>
                                                <option value="<?= htmlspecialchars($e['id'], ENT_QUOTES, 'UTF-8') ?>" data-telefono="<?= htmlspecialchars($e['telefono'] ?? '', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($e['nombre']." ".$e['apellido'], ENT_QUOTES, 'UTF-8') ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label for="plan-telefono">Teléfono responsable</label>
                                        <input type="tel" id="plan-telefono" maxlength="11" name="telefono_responsable" inputmode="tel" placeholder="Ej. 04123456789" readonly>
                                    </fieldset>
                                </fieldset>
                            </fieldset>
                        </div>

                        <button type="submit" class="btn-ingresar-planificacion">Ingresar planificación</button>
                    </form>
                </div>
            </section>
        </div>
    </div>
</section>

<script>
    (function () {
        const selectResponsable = document.getElementById('plan-responsable');
        const inputTelefono = document.getElementById('plan-telefono');

        if (selectResponsable && inputTelefono) {
            selectResponsable.addEventListener('change', () => {
                const opcion = selectResponsable.options[selectResponsable.selectedIndex];
                if (opcion && opcion.value !== '') {
                    inputTelefono.value = opcion.dataset.telefono || '';
                    inputTelefono.readOnly = true;
                } else {
                    inputTelefono.value = '';
                    inputTelefono.readOnly = false;
                }
            });
        }
    })();
</script>
