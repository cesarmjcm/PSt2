<?php
require_once __DIR__ . '/../include/guardian.php';
// Nota: aquí solo se exige sesión iniciada (guardian.php ya lo hace).
// El maestro "cargo" está restringido a administradores más abajo,
// una vez que se conoce $tabla.

$maestros = [
    'municipio' => 'Municipios',
    'parroquia' => 'Parroquias',
    'comuna' => 'Comunas',
    'biblioteca' => 'Bibliotecas',
    'institucion' => 'Instituciones',
    'cargo' => 'Cargos',
    'empleado' => 'Empleados',
    'nv_act' => 'Niveles de impacto',
    'espacio' => 'Espacios culturales',
    'tipo_actividad' => 'Tipos de actividad',
  
];


$camposPorMaestro = [
    'municipio'  => [
        'nombre' => ['label' => 'Nombre', 'type' => 'text', 'maxLength' => 30, 'noNumeros' => true],
    ],
    'parroquia'  => [
        'id_municipio' => [
            'label'       => 'Municipio',
            'type'        => 'select',
            'fuente'      => '../controladores/municipio_contr.php',
            'optionValue' => 'id',
            'optionLabel' => 'nombre',
        ],
        'nombre'       => ['label' => 'Nombre', 'type' => 'text', 'maxLength' => 30, 'noNumeros' => true],
    ],
    'comuna'     => [
        'id_municipio' => [
            'label'       => 'Municipio',
            'type'        => 'select',
            'fuente'      => '../controladores/municipio_contr.php',
            'optionValue' => 'id',
            'optionLabel' => 'nombre',
        
            'soloFiltro'  => true,
        ],
        'id_parroquia' => [
            'label'       => 'Parroquia',
            'type'        => 'select',
            'fuente'      => '../controladores/parroquia_contr.php',
            'optionValue' => 'id',
            'optionLabel' => 'nombre',
            'filtradoPor' => 'id_municipio',
        ],
        'nombre'       => ['label' => 'Nombre', 'type' => 'text', 'maxLength' => 30, 'noNumeros' => true],
    ],
    'biblioteca' => [
        'id_municipio' => [
            'label'       => 'Municipio',
            'type'        => 'select',
            'fuente'      => '../controladores/municipio_contr.php',
            'optionValue' => 'id',
            'optionLabel' => 'nombre',
            'soloFiltro'  => true,
        ],
        'id_parroquia' => [
            'label'       => 'Parroquia',
            'type'        => 'select',
            'fuente'      => '../controladores/parroquia_contr.php',
            'optionValue' => 'id',
            'optionLabel' => 'nombre',
            'filtradoPor' => 'id_municipio',
        ],
        'nombre' => [
            'label' => 'Nombre',
            'type' => 'text',
            'maxLength' => 50,
            'noNumeros' => true
        ],
        'correo' => [
            'label' => 'Correo',
            'type' => 'email',
            'maxLength' => 30,
            'opcional' => true,
            'esCorreo' => true
        ],
        'redes_sociales' => [
            'label' => 'Redes sociales',
            'type' => 'text',
            'maxLength' => 30,
            'opcional' => true
        ],
        'direccion' => [
            'label' => 'Dirección',
            'type' => 'text',
            'maxLength' => 30,
            'opcional' => true
        ],
    ],
    'institucion' => [
        'id_municipio' => [
            'label'       => 'Municipio',
            'type'        => 'select',
            'fuente'      => '../controladores/municipio_contr.php',
            'optionValue' => 'id',
            'optionLabel' => 'nombre',
        ],
        'nombre' => [
            'label' => 'Nombre',
            'type' => 'text',
            'maxLength' => 40,
            'noNumeros' => true,
        ],
    ],
    
    'cargo'      => [
        'nombre'      => ['label' => 'Nombre', 'type' => 'text', 'maxLength' => 36, 'noNumeros' => true],
        'descripcion' => ['label' => 'Descripción', 'type' => 'text', 'maxLength' => 40, 'opcional' => true],
    ],
    'empleado'   => [
        'nombre'   => ['label' => 'Nombre', 'type' => 'text', 'maxLength' => 40, 'noNumeros' => true],
        'apellido' => ['label' => 'Apellido', 'type' => 'text', 'maxLength' => 20, 'noNumeros' => true],
        'telefono' => ['label' => 'Teléfono', 'type' => 'text', 'maxLength' => 11, 'formatoVenezolano' => true],
        'id_cargo' => [ 'label'       => 'Cargo', 'type'        => 'select','fuente'      => '../controladores/cargo_contr.php','optionValue' => 'id','optionLabel' => 'nombre', ],
        'cedula'   => ['label' => 'Cédula', 'type' => 'number', 'minLength' => 6, 'maxLength' => 8],
        ],
    'nv_act'     => [
        'nombre_impacto' => ['label' => 'Nombre', 'type' => 'text', 'maxLength' => 20, 'noNumeros' => true],
    ],
    'espacio'    => [
        'nombre'    => ['label' => 'Nombre', 'type' => 'text', 'maxLength' => 30],
        'capacidad' => ['label' => 'Capacidad', 'type' => 'number'],
        'direccion' => ['label' => 'Dirección', 'type' => 'text', 'maxLength' => 30],
        'metodo_contactar' => ['label' => 'Método de contacto', 'type' => 'text', 'maxLength' => 40, 'opcional' => true],
    ],
    'tipo_actividad' => [
        'nombre'      => ['label' => 'Nombre', 'type' => 'text', 'maxLength' => 30, 'noNumeros' => true],
        'descripcion' => ['label' => 'Descripción', 'type' => 'text', 'maxLength' => 40, 'opcional' => true],
    ],
];
   

$controladorPorMaestro = [
    'municipio'  => '../controladores/municipio_contr.php',
    'parroquia'  => '../controladores/parroquia_contr.php',
    'comuna'     => '../controladores/comuna_contr.php',
    'biblioteca' => '../controladores/biblioteca_contr.php',
    'institucion' => '../controladores/institucion_contr.php',
    'cargo'      => '../controladores/cargo_contr.php',
    'empleado'   => '../controladores/empleado_contr.php',
    'nv_act'     => '../controladores/nv_act_contr.php',
    'espacio'    => '../controladores/espacio_contr.php',
    'tipo_actividad' => '../controladores/tipo_actividad_contr.php',
 
];

$tabla = $_GET['tabla'] ?? '';
$titulo = $maestros[$tabla] ?? 'Maestro no encontrado';
$tablaValida = !empty($tabla) && isset($maestros[$tabla]);

$esAdmin = esAdministrador();

// El maestro "cargo" es exclusivo del administrador; los demás son
// accesibles para cualquier usuario con sesión iniciada.
if ($tablaValida && $tabla === 'cargo' && !$esAdmin) {
    guardian_requerirAdmin('vista');
}

// El menú lateral tampoco muestra "Cargos" a los usuarios básicos.
$maestrosMenu = $esAdmin ? $maestros : array_diff_key($maestros, ['cargo' => true]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/maestro.css">
    <link rel="icon" type="image/png" href="./assets/icon__icey.png">
    <link rel="stylesheet" href="./css/fontawesome-all.min.css">
</head>
<body>
    <?php include '../include/header.php'; ?>

    <div class="page-layout">
        <aside class="sidebar-menu">
            <h2>Panel</h2>
            <nav class="sidebar-nav">
                <ul>
                    <?php foreach ($maestrosMenu as $key => $label): ?>
                        <li><a href="./maestro.php?tabla=<?php echo urlencode($key); ?>"<?php echo $key === $tabla ? ' class="active"' : ''; ?>><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </aside>

        <main class="page-content">
            <div class="tabla__container">
                <?php if (!$tablaValida): ?>
                    <h1 class="planificacion__title"><?php echo htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'); ?></h1>
                    <p>Selecciona un maestro válido desde el menú lateral para comenzar.</p>
                <?php else: ?>

                    <div class="maestro__header">
                        <h1 class="planificacion__title"><?php echo htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'); ?></h1>
                        <button type="button" class="btn-primary" id="btnNuevo">
                            <i class="fas fa-plus"></i> Nuevo
                        </button>
                    </div>

                    <div id="alertBox" class="maestro__alert maestro__alert--oculto" style="min-height: 2.75em; margin: 0 0 12px; box-sizing: border-box; visibility: hidden;"></div>

                    <table class="tabla-planificacion" id="tablaMaestro">
                        <thead>
                            <tr id="tablaHead">
                                <!-- Encabezados generados por JS -->
                            </tr>
                        </thead>
                        <tbody id="tablaBody">
                            <tr><td>Cargando...</td></tr>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <?php if ($tablaValida): ?>
    
    <div class="maestro-modal" id="maestroModal" hidden>
        <div class="maestro-modal__backdrop" id="modalBackdrop"></div>
        <div class="maestro-modal__dialog">
            <div class="maestro-modal__header">
                <h2 id="modalTitulo">Nuevo registro</h2>
                <button type="button" class="maestro-modal__close" id="modalClose" aria-label="Cerrar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="maestroForm" novalidate>
                <input type="hidden" id="formId" value="">
                <div id="modalError" class="maestro__alert maestro__alert--error" hidden></div>
                <div id="formCampos">
                
                </div>
                <div class="maestro-modal__actions">
                    <button type="button" class="btn-secondary" id="btnCancelar">Cancelar</button>
                    <button type="submit" class="btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="maestro-modal" id="confirmModal" hidden>
        <div class="maestro-modal__backdrop" id="confirmBackdrop"></div>
        <div class="maestro-modal__dialog maestro-modal__dialog--small">
            <div class="maestro-modal__header">
                <h2>Confirmar eliminación</h2>
            </div>
            <p class="maestro-modal__text">¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.</p>
            <div class="maestro-modal__actions">
                <button type="button" class="btn-secondary" id="btnCancelarEliminar">Cancelar</button>
                <button type="button" class="btn-danger" id="btnConfirmarEliminar">Eliminar</button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="./js/app.js"></script>
    <?php if ($tablaValida): ?>
    <script>
    (function () {
        const tablaActual = <?php echo json_encode($tabla); ?>;
        const endpoint = <?php echo json_encode($controladorPorMaestro[$tabla]); ?>;
        const campos = <?php echo json_encode($camposPorMaestro[$tabla], JSON_UNESCAPED_UNICODE); ?>;
        const camposKeys = Object.keys(campos);

        const tablaHead = document.getElementById('tablaHead');
        const tablaBody = document.getElementById('tablaBody');
        const alertBox = document.getElementById('alertBox');

        const modal = document.getElementById('maestroModal');
        const modalBackdrop = document.getElementById('modalBackdrop');
        const modalTitulo = document.getElementById('modalTitulo');
        const modalClose = document.getElementById('modalClose');
        const btnCancelar = document.getElementById('btnCancelar');
        const formCampos = document.getElementById('formCampos');
        const maestroForm = document.getElementById('maestroForm');
        const formId = document.getElementById('formId');
        const modalErrorBox = document.getElementById('modalError');
        const btnNuevo = document.getElementById('btnNuevo');

        const confirmModal = document.getElementById('confirmModal');
        const confirmBackdrop = document.getElementById('confirmBackdrop');
        const btnCancelarEliminar = document.getElementById('btnCancelarEliminar');
        const btnConfirmarEliminar = document.getElementById('btnConfirmarEliminar');
        let idAEliminar = null;

        let alertTimeoutId = null;

        function mostrarAlerta(mensaje, tipo) {
            if (alertTimeoutId) clearTimeout(alertTimeoutId);
            alertBox.textContent = mensaje;
            alertBox.className = 'maestro__alert maestro__alert--' + tipo;
            alertBox.style.visibility = 'visible';
            alertTimeoutId = setTimeout(() => {
                alertBox.style.visibility = 'hidden';
            }, 4000);
        }

        function mostrarErrorModal(mensaje) {
            modalErrorBox.textContent = mensaje;
            modalErrorBox.hidden = false;
        }

        function ocultarErrorModal() {
            modalErrorBox.textContent = '';
            modalErrorBox.hidden = true;
        }

        
        function esVacio(valor) {
            return valor === null || valor === undefined || valor.trim() === '';
        }

        
        function esRepetitivo(valor) {
            const v = valor.trim();
            if (v.length < 2) return false;

            
            if (/(.)\1{2,}/.test(v)) return true;

            
            if (/^(.)\1+$/.test(v)) return true;

            for (let len = 1; len <= Math.floor(v.length / 2); len++) {
                if (v.length % len !== 0) continue;
                const chunk = v.substring(0, len);
                if (chunk.repeat(v.length / len) === v) return true;
            }

            return false;
        }

        
        const CAMPOS_SIN_CHEQUEO_REPETICION = ['telefono', 'capacidad'];

       
        const REGEX_SOLO_LETRAS = /^[A-Za-zÁÉÍÓÚáéíóúÑñÜü '\-]+$/u;

     
        const REGEX_TELEFONO_VENEZUELA = /^0(4(12|14|16|22|24|26)|2\d{2})\d{7}$/;

        // Formato de correo electrónico (para campos con esCorreo: true)
        const REGEX_CORREO = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  
        function validarCampoMaestro(key, campo, valorCrudo, esOpcional) {
            const valor = (valorCrudo || '').trim();

            if (!esOpcional && esVacio(valor)) {
                return 'El campo "' + campo.label + '" no puede quedar vacío.';
            }

            if (esVacio(valor)) {
                return null; 
            }

            if (campo.maxLength && valor.length > campo.maxLength) {
                return 'El campo "' + campo.label + '" no puede tener más de ' + campo.maxLength + ' caracteres.';
            }

            if (campo.minLength && valor.length < campo.minLength) {
                return 'El campo "' + campo.label + '" debe tener al menos ' + campo.minLength + ' caracteres.';
            }

            if (campo.noNumeros && !REGEX_SOLO_LETRAS.test(valor)) {
                return 'El campo "' + campo.label + '" solo puede contener letras y espacios (sin números).';
            }

            if (campo.formatoVenezolano && !REGEX_TELEFONO_VENEZUELA.test(valor)) {
                return 'El campo "' + campo.label + '" debe tener el formato venezolano: empieza en 0, solo números, 11 dígitos en total (ej. 04141234567).';
            }

            if (campo.esCorreo && !REGEX_CORREO.test(valor)) {
                return 'El campo "' + campo.label + '" debe ser un correo electrónico válido (ej. nombre@dominio.com).';
            }

            const aplicaChequeoRepeticion = campo.type === 'text' && !campo.opcional && !CAMPOS_SIN_CHEQUEO_REPETICION.includes(key);
            if (aplicaChequeoRepeticion && esRepetitivo(valor)) {
                return 'El campo "' + campo.label + '" no puede ser un mismo carácter ni una cadena repetida (ej. "aaa", "abab").';
            }

            return null;
        }

        function renderHead() {
            let html = '<th>ID</th>';
            camposKeys.forEach(key => {
                if (campos[key].type === 'password') return; // nunca se lista la clave
                if (campos[key].soloFiltro) return; // campo virtual, no es columna real
                html += '<th>' + campos[key].label + '</th>';
            });
            html += '<th class="col-acciones">Acciones</th>';
            tablaHead.innerHTML = html;
        }

        function escapeHtml(str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        // Caché de opciones para los campos tipo "select" (FK), para no
        // volver a pedirlas al servidor cada vez que se abre el modal.
        const cacheOpciones = {};

        async function obtenerOpciones(fuente) {
            if (cacheOpciones[fuente]) return cacheOpciones[fuente];
            try {
                const resp = await fetch(fuente + '?action=listar');
                const json = await resp.json();
                cacheOpciones[fuente] = (json.success && Array.isArray(json.data)) ? json.data : [];
            } catch (e) {
                cacheOpciones[fuente] = [];
            }
            return cacheOpciones[fuente];
        }

        let fetchController = null;

        async function cargarDatos() {
            
            if (fetchController) fetchController.abort();
            fetchController = new AbortController();
            const miController = fetchController;

            tablaBody.innerHTML = '<tr><td>Cargando...</td></tr>';

            let json;
            try {
                const resp = await fetch(endpoint + '?action=listar', { signal: miController.signal });
                json = await resp.json();
            } catch (e) {
                if (e.name === 'AbortError') return; // Petición cancelada por una más nueva, ignorar
                tablaBody.innerHTML = '<tr><td>Error de conexión con el servidor.</td></tr>';
                return;
            }

            if (miController !== fetchController) return; // Llegó tarde, ya hay una petición más nueva

            if (!json.success) {
                tablaBody.innerHTML = '<tr><td>No se pudieron cargar los datos.</td></tr>';
                return;
            }

            try {
                renderBody(json.data || []);
            } catch (e) {
                console.error('Error al pintar la tabla:', e);
                tablaBody.innerHTML = '<tr><td>Ocurrió un error al mostrar los datos.</td></tr>';
            }
        }

        function renderBody(filas) {
            if (!filas.length) {
                const colspan = camposKeys.filter(k => campos[k].type !== 'password' && !campos[k].soloFiltro).length + 2;
                tablaBody.innerHTML = '<tr><td colspan="' + colspan + '">No hay registros todavía.</td></tr>';
                return;
            }

            let html = '';
            filas.forEach(fila => {
                html += '<tr>';
                html += '<td>' + escapeHtml(fila.id) + '</td>';
                camposKeys.forEach(key => {
                    if (campos[key].type === 'password') return;
                    if (campos[key].soloFiltro) return;
                    if (campos[key].type === 'select') {
                        const labelKey = key + '_nombre';
                        const displayValue = fila[labelKey] !== undefined ? fila[labelKey] : fila[key];
                        html += '<td>' + escapeHtml(displayValue) + '</td>';
                    } else {
                        html += '<td>' + escapeHtml(fila[key]) + '</td>';
                    }
                });
                html += '<td class="col-acciones">';
                html += '<button type="button" class="btn-icon btn-edit" data-id="' + fila.id + '" title="Editar"><i class="fas fa-pen"></i></button>';
                html += '<button type="button" class="btn-icon btn-delete" data-id="' + fila.id + '" title="Eliminar"><i class="fas fa-trash"></i></button>';
                html += '</td>';
                html += '</tr>';
            });
            tablaBody.innerHTML = html;

            tablaBody.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', () => abrirModalEditar(btn.dataset.id, filas));
            });
            tablaBody.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', () => abrirConfirmEliminar(btn.dataset.id));
            });
        }

        async function renderFormCampos(valores) {
            valores = valores || {};

            
            const opcionesPorCampo = {};
            await Promise.all(camposKeys.map(async key => {
                const campo = campos[key];
                if (campo.type === 'select') {
                    opcionesPorCampo[key] = await obtenerOpciones(campo.fuente);
                }
            }));

           
            const filtroInicialPorCampo = {};
            camposKeys.forEach(key => {
                const campo = campos[key];
                if (!campo.filtradoPor) return;
                const valorGuardado = valores[key] !== undefined ? String(valores[key]) : '';
                if (valorGuardado === '') return;
                const opciones = opcionesPorCampo[key] || [];
                const opcionGuardada = opciones.find(op => String(op[campo.optionValue]) === valorGuardado);
                if (opcionGuardada && opcionGuardada[campo.filtradoPor] !== undefined) {
                    filtroInicialPorCampo[key] = String(opcionGuardada[campo.filtradoPor]);
                }
            });

            let html = '';
            camposKeys.forEach(key => {
                const campo = campos[key];
                const esOpcionalPorEdicion = campo.optionalOnEdit && formId.value !== '';
                const esOpcional = esOpcionalPorEdicion || campo.opcional;
                const valor = valores[key] !== undefined ? valores[key] : '';
                html += '<div class="config-field">';
                html += '<label for="campo_' + key + '">' + campo.label + (esOpcionalPorEdicion ? ' (dejar vacío para no cambiar)' : (campo.opcional ? ' (opcional)' : '')) + '</label>';

                if (campo.type === 'select') {
                    let opciones = opcionesPorCampo[key] || [];
                    let valorInicialSelect = valor;

                    if (campo.filtradoPor) {
          
                        const filtroActual = filtroInicialPorCampo[key] || '';
                        opciones = filtroActual === '' ? [] : opciones.filter(op => String(op[campo.filtradoPor]) === filtroActual);
                    }

                    html += '<select id="campo_' + key + '" name="' + key + '"' + (esOpcional ? '' : ' required') + (campo.filtradoPor && opciones.length === 0 ? ' disabled' : '') + '>';
                    html += '<option value="">' + (campo.filtradoPor && opciones.length === 0 ? 'Seleccione ' + (campos[campo.filtradoPor] ? campos[campo.filtradoPor].label.toLowerCase() : 'una opción') + ' primero' : '-- Seleccione --') + '</option>';
                    opciones.forEach(op => {
                        const valOp = op[campo.optionValue];
                        const labelOp = op[campo.optionLabel];
                        const seleccionado = (valorInicialSelect !== '' && String(valOp) === String(valorInicialSelect)) ? ' selected' : '';
                        html += '<option value="' + escapeHtml(valOp) + '"' + seleccionado + '>' + escapeHtml(labelOp) + '</option>';
                    });
                    html += '</select>';
                } else {
                    const maxLengthAttr = campo.maxLength ? ' maxlength="' + campo.maxLength + '"' : '';
                    
                    const patternAttr = campo.noNumeros ? ' pattern="[A-Za-zÁÉÍÓÚáéíóúÑñÜü \'\\-]+" title="Solo letras y espacios, sin números"' : '';
                    html += '<input id="campo_' + key + '" name="' + key + '" type="' + campo.type + '" value="' + escapeHtml(valor) + '"' + maxLengthAttr + patternAttr + (esOpcional ? '' : (campo.optionalOnEdit ? '' : ' required')) + ' />';
                }

                html += '</div>';
            });
            formCampos.innerHTML = html;

           
            camposKeys.forEach(filtroKey => {
                const campoDependiente = Object.keys(campos).find(k => campos[k].filtradoPor === filtroKey);
                if (!campoDependiente) return;

                const selectFiltro = document.getElementById('campo_' + filtroKey);
                const selectDependiente = document.getElementById('campo_' + campoDependiente);
                if (!selectFiltro || !selectDependiente) return;

    
                const filtroInicial = filtroInicialPorCampo[campoDependiente];
                if (filtroInicial) {
                    selectFiltro.value = filtroInicial;
                }

                selectFiltro.addEventListener('change', () => {
                    const campoDep = campos[campoDependiente];
                    const campoFiltro = campos[filtroKey];
                    const opciones = (opcionesPorCampo[campoDependiente] || []).filter(op => String(op[campoDep.filtradoPor]) === selectFiltro.value);
                    let html = '<option value="">' + (opciones.length === 0 ? 'Seleccione ' + campoFiltro.label.toLowerCase() + ' primero' : '-- Seleccione --') + '</option>';
                    opciones.forEach(op => {
                        const valOp = op[campoDep.optionValue];
                        const labelOp = op[campoDep.optionLabel];
                        html += '<option value="' + escapeHtml(valOp) + '">' + escapeHtml(labelOp) + '</option>';
                    });
                    selectDependiente.innerHTML = html;
                    selectDependiente.disabled = opciones.length === 0;
                });
            });
        }

        async function abrirModalNuevo() {
            formId.value = '';
            modalTitulo.textContent = 'Nuevo registro';
            formCampos.innerHTML = '<p>Cargando formulario...</p>';
            ocultarErrorModal();
            modal.hidden = false;
            await renderFormCampos({});
        }

        async function abrirModalEditar(id, filas) {
            const fila = filas.find(f => String(f.id) === String(id));
            if (!fila) return;
            formId.value = id;
            modalTitulo.textContent = 'Editar registro';
            formCampos.innerHTML = '<p>Cargando formulario...</p>';
            ocultarErrorModal();
            modal.hidden = false;
            await renderFormCampos(fila);
        }

        function cerrarModal() {
            modal.hidden = true;
        }

        function abrirConfirmEliminar(id) {
            idAEliminar = id;
            confirmModal.hidden = false;
        }

        function cerrarConfirmEliminar() {
            idAEliminar = null;
            confirmModal.hidden = true;
        }

        async function eliminarRegistro(id) {
            try {
                const body = new URLSearchParams();
                body.append('action', 'eliminar');
                body.append('id', id);
                const resp = await fetch(endpoint, { method: 'POST', body });
                const json = await resp.json();
                mostrarAlerta(json.message, json.success ? 'success' : 'error');
                if (json.success) cargarDatos();
            } catch (e) {
                mostrarAlerta('Error de conexión con el servidor.', 'error');
            }
        }

        maestroForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            ocultarErrorModal();

            const esEdicion = formId.value !== '';
            const body = new URLSearchParams();
            body.append('action', esEdicion ? 'actualizar' : 'crear');
            if (esEdicion) body.append('id', formId.value);

            for (const key of camposKeys) {
                const campo = campos[key];
                if (campo.soloFiltro) continue; // campo virtual (ej. municipio para filtrar parroquia), no se envía

                const input = document.getElementById('campo_' + key);
                const valor = input ? input.value : '';
                const esOpcional = (campo.optionalOnEdit && esEdicion) || campo.opcional;

                const errorCampo = validarCampoMaestro(key, campo, valor, esOpcional);
                if (errorCampo) {
                    mostrarErrorModal(errorCampo);
                    if (input) input.focus();
                    return;
                }

                // Validación cruzada: ej. "confirmar_clave" debe coincidir
                // con el valor de "clave".
                if (campo.confirmaCampo) {
                    const otroInput = document.getElementById('campo_' + campo.confirmaCampo);
                    const otroValor = otroInput ? otroInput.value : '';
                    if (valor !== otroValor) {
                        mostrarErrorModal('El campo "' + campo.label + '" no coincide con "' + campos[campo.confirmaCampo].label + '".');
                        if (input) input.focus();
                        return;
                    }
                }

                // Campos virtuales que solo existen para validar en el
                // navegador (ej. confirmar_clave) no se envían al servidor.
                if (campo.noEnviar) continue;

                body.append(key, valor.trim());
            }

            try {
                const resp = await fetch(endpoint, { method: 'POST', body });
                const json = await resp.json();
                if (json.success) {
                    delete cacheOpciones[endpoint]; // por si este maestro es fuente de un select en otro maestro
                    cerrarModal();
                    cargarDatos();
                    mostrarAlerta(json.message, 'success');
                } else {
                    mostrarErrorModal(json.message);
                }
            } catch (e) {
                mostrarErrorModal('Error de conexión con el servidor.');
            }
        });

        btnNuevo.addEventListener('click', abrirModalNuevo);
        modalClose.addEventListener('click', cerrarModal);
        btnCancelar.addEventListener('click', cerrarModal);
        modalBackdrop.addEventListener('click', cerrarModal);

        btnCancelarEliminar.addEventListener('click', cerrarConfirmEliminar);
        confirmBackdrop.addEventListener('click', cerrarConfirmEliminar);
        btnConfirmarEliminar.addEventListener('click', () => {
            if (idAEliminar !== null) {
                eliminarRegistro(idAEliminar);
            }
            cerrarConfirmEliminar();
        });

        renderHead();
        cargarDatos();
    })();
    </script>
    <?php endif; ?>
</body>
</html>
