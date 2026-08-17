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
?>

<section>

    <div id="modalEditarActividad" class="modal" style="display:none;">
        <div class="modal-content-wrapper">
            <section class="formulario-planificacion">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                    <h2 class="section-title" style="margin: 0;">Editar Actividad</h2>
                    <span class="close-button-editar" style="cursor:pointer; font-size: 1.5rem;">&times;</span>
                </div>
                <div class="container__planificacion">
                    <form id="form-editar-actividad" action="../controladores/actividad_contr.php" method="post" onsubmit="return validacionesformulario(this)" novalidate>
                        <input type="hidden" name="action" value="actualizar">
                        <input type="hidden" name="id" id="editar-id" value="">
                        <input type="hidden" id="editar-dia" name="dia_semana" value="">
                        <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? '../src/index.php', ENT_QUOTES, 'UTF-8'); ?>">

                        <fieldset class="tipo-formulario-selector" style="border:none; padding:0; margin:0 0 16px; display:flex; gap:24px; align-items:center; flex-wrap:wrap;">
                            <legend style="font-weight:600; margin-bottom:6px; padding:0; width:100%;">Tipo de planificación</legend>
                            <label style="display:flex; align-items:center; gap:6px; font-weight:normal;">
                                <input type="radio" name="tipo_formulario" id="editar-tipo-formulario-simple" value="simple">
                                Actividad simple
                            </label>
                            <label style="display:flex; align-items:center; gap:6px; font-weight:normal;">
                                <input type="radio" name="tipo_formulario" id="editar-tipo-formulario-completa" value="completa">
                                Actividad completa
                            </label>
                        </fieldset>

                        <div id="form-editar-aviso" class="form-aviso" style="min-height: 2.75em; margin: 0 0 12px; box-sizing: border-box; visibility: hidden;"></div>

                        <div class="planificacion-grid">
                            <fieldset class="planificacion-group">
                                <fieldset>
                                    <label for="editar-nombre">Nombre de la actividad</label>
                                    <input type="text" id="editar-nombre" maxlength="30" name="nombre" placeholder="Nombre de la actividad">

                                    <div id="editar-fields-hidden" class="hidden">
                                        <label for="editar-descripcion">Descripción de la actividad</label>
                                        <textarea id="editar-descripcion" maxlength="200" name="descripcion" placeholder="Descripción breve"></textarea>
                                    </div>
                                </fieldset>

                                <fieldset>
                                    <label for="editar-fecha">Fecha de la actividad</label>
                                    <input type="date" id="editar-fecha" name="fecha">

                                    <label for="editar-hora">Hora de la actividad</label>
                                    <input type="time" id="editar-hora" name="horaActividad">
                                </fieldset>

                                <fieldset id="editar-campos-completa" class="hidden">
                                    <legend>Detalles de actividad completa</legend>

                                    <label for="editar-objetivo">Objetivo / enfoque</label>
                                    <input type="text" id="editar-objetivo" maxlength="50" name="objetivo" placeholder="Objetivo de la actividad">

                                    <label for="editar-participantes">Cantidad de participantes</label>
                                    <input type="number" id="editar-participantes" name="participantes" min="0" max="99999" placeholder="Ej. 25">

                                    <label for="editar-nivel-impacto">Nivel de impacto</label>
                                    <input type="text" id="editar-nivel-impacto" maxlength="20" name="nivel_impacto" placeholder="Ej. Comunal, Regional, Nacional">
                                </fieldset>
                            </fieldset>

                            <fieldset class="planificacion-group">
                                <fieldset>
                                    <label for="editar-municipio">Municipio</label>
                                    <select name="municipio_id" id="editar-municipio">
                                        <option value="">Seleccione un municipio</option>
                                        <?php foreach ($municipios as $m): ?>
                                            <option value="<?= htmlspecialchars($m['id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($m['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <div id="editar-municipio-hidden" class="hidden">
                                        <fieldset>
                                            <label for="editar-parroquia">Parroquia</label>
                                            <input list="editar-parroquias" id="editar-parroquia" name="parroquia" maxlength="30" placeholder="Seleccione una parroquia">
                                            <datalist id="editar-parroquias">
                                                <option value="">Seleccione una parroquia</option>
                                                <?php foreach ($parroquias as $p): ?>
                                                    <option value="<?= htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                                                <?php endforeach; ?>
                                            </datalist>
                                        </fieldset>

                                        <fieldset>
                                            <label for="editar-comuna">Comuna</label>
                                            <select name="comuna" id="editar-comuna">
                                                <option value="">Seleccione una comuna</option>
                                                <?php foreach ($comunas as $c): ?>
                                                    <option value="<?= htmlspecialchars($c['nombre'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($c['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </fieldset>

                                        <fieldset>
                                            <legend>¿Dónde se realiza?</legend>
                                            <div style="display:flex; gap:18px; margin-bottom:10px; flex-wrap:wrap;">
                                                <label style="display:flex; align-items:center; gap:6px; margin:0;">
                                                    <input type="radio" name="tipo_ubicacion" id="editar-tipo-ubicacion-biblioteca" value="biblioteca">
                                                    Biblioteca
                                                </label>
                                                <label style="display:flex; align-items:center; gap:6px; margin:0;">
                                                    <input type="radio" name="tipo_ubicacion" id="editar-tipo-ubicacion-espacio" value="espacio">
                                                    Espacio
                                                </label>
                                            </div>

                                            <div id="editar-ubicacion-biblioteca">
                                                <label for="editar-biblioteca">Biblioteca</label>
                                                <select name="id_biblioteca" id="editar-biblioteca">
                                                    <option value="">Seleccione una biblioteca</option>
                                                    <?php foreach ($bibliotecas as $b): ?>
                                                        <option value="<?= htmlspecialchars($b['id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($b['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div id="editar-ubicacion-espacio" class="hidden">
                                                <label for="editar-espacio">Espacio cultural</label>
                                                <select name="id_espacio_cultural" id="editar-espacio">
                                                    <option value="">Seleccione un espacio cultural</option>
                                                    <?php foreach ($espacios as $e): ?>
                                                        <option value="<?= htmlspecialchars($e['id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($e['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <fieldset>
                                        <label for="editar-tipo-actividad">Tipo de actividad</label>
                                        <select name="id_tipo_actividad" id="editar-tipo-actividad">
                                            <option value="">Seleccione un tipo de actividad</option>
                                            <?php foreach ($tiposActividad as $t): ?>
                                                <option value="<?= htmlspecialchars($t['id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($t['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </fieldset>

                                    <fieldset>
                                        <legend>Responsable</legend>
                                        <label for="editar-responsable">Nombre</label>
                                        <select name="responsable" id="editar-responsable">
                                            <option value="">Seleccione un responsable</option>
                                            <?php foreach ($empleados as $e): ?>
                                                <?php $nombreCompleto = trim($e['nombre'].' '.$e['apellido']); ?>
                                                <option value="<?= htmlspecialchars($nombreCompleto, ENT_QUOTES, 'UTF-8') ?>" data-telefono="<?= htmlspecialchars($e['telefono'] ?? '', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($nombreCompleto, ENT_QUOTES, 'UTF-8') ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label for="editar-telefono">Teléfono responsable</label>
                                        <input type="tel" id="editar-telefono" maxlength="11" name="telefono_responsable" inputmode="tel" placeholder="Ej. 04123456789" readonly>
                                    </fieldset>
                                </fieldset>
                            </fieldset>
                        </div>

                        <button type="submit" class="btn-ingresar-planificacion">Guardar cambios</button>
                    </form>
                </div>
            </section>
        </div>
    </div>
</section>

<script>
    (function () {
        // Autocompletar teléfono al elegir responsable
        const selectResponsable = document.getElementById('editar-responsable');
        const inputTelefono = document.getElementById('editar-telefono');

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

        // Alternar simple / completa
        const radiosFormulario = document.querySelectorAll('#form-editar-actividad input[name="tipo_formulario"]');
        const fieldsHidden = document.getElementById('editar-fields-hidden');
        const camposCompleta = document.getElementById('editar-campos-completa');
        const municipioHidden = document.getElementById('editar-municipio-hidden');

        function actualizarTipoFormulario() {
            const esCompleta = document.getElementById('editar-tipo-formulario-completa').checked;
            fieldsHidden.classList.toggle('hidden', !esCompleta);
            camposCompleta.classList.toggle('hidden', !esCompleta);
            municipioHidden.classList.toggle('hidden', !esCompleta);
        }

        radiosFormulario.forEach(radio => radio.addEventListener('change', actualizarTipoFormulario));

        // Alternar biblioteca / espacio
        const radiosUbicacion = document.querySelectorAll('#form-editar-actividad input[name="tipo_ubicacion"]');
        const ubicacionBiblioteca = document.getElementById('editar-ubicacion-biblioteca');
        const ubicacionEspacio = document.getElementById('editar-ubicacion-espacio');

        function actualizarUbicacion() {
            const esEspacio = document.getElementById('editar-tipo-ubicacion-espacio').checked;
            ubicacionBiblioteca.classList.toggle('hidden', esEspacio);
            ubicacionEspacio.classList.toggle('hidden', !esEspacio);
        }

        radiosUbicacion.forEach(radio => radio.addEventListener('change', actualizarUbicacion));

        // Exponer función global para precargar el formulario al abrir el modal
        window.precargarFormularioEditar = function (actividad) {
            document.getElementById('editar-id').value = actividad.id ?? '';
            document.getElementById('editar-dia').value = actividad.dia_semana ?? '';
            document.getElementById('editar-nombre').value = actividad.nombre ?? '';
            document.getElementById('editar-descripcion').value = actividad.descripcion ?? '';
            document.getElementById('editar-fecha').value = actividad.fecha ?? '';
            document.getElementById('editar-hora').value = actividad.horaActividad ?? '';
            document.getElementById('editar-objetivo').value = actividad.objetivo ?? '';
            document.getElementById('editar-participantes').value = actividad.participantes ?? '';
            document.getElementById('editar-nivel-impacto').value = actividad.nivel_impacto ?? '';
            document.getElementById('editar-municipio').value = actividad.municipio_id ?? '';
            document.getElementById('editar-parroquia').value = actividad.parroquia ?? '';
            document.getElementById('editar-comuna').value = actividad.comuna ?? '';
            document.getElementById('editar-biblioteca').value = actividad.id_biblioteca ?? '';
            document.getElementById('editar-espacio').value = actividad.id_espacio_cultural ?? '';
            document.getElementById('editar-tipo-actividad').value = actividad.id_tipo_actividad ?? '';
            document.getElementById('editar-responsable').value = actividad.responsable ?? '';
            document.getElementById('editar-telefono').value = actividad.telefono_responsable ?? '';

            const esCompleta = !!(actividad.objetivo || actividad.participantes || actividad.nivel_impacto || actividad.municipio_id);
            document.getElementById('editar-tipo-formulario-' + (esCompleta ? 'completa' : 'simple')).checked = true;

            const esEspacio = !!actividad.id_espacio_cultural;
            document.getElementById('editar-tipo-ubicacion-' + (esEspacio ? 'espacio' : 'biblioteca')).checked = true;

            actualizarTipoFormulario();
            actualizarUbicacion();
        };

        // Estado inicial oculto por defecto (se ajusta al precargar)
        actualizarTipoFormulario();
        actualizarUbicacion();
    })();
</script>
