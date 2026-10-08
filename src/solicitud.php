<?php
require_once __DIR__ . '/../include/guardian.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$esAdmin = (($_SESSION['user_rol'] ?? '') === 'administrador');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes de Escuelas</title>
    <link rel="stylesheet" href="./css/main.css?v=2">
    <link rel="stylesheet" href="./css/maestro.css">
    <link rel="stylesheet" href="./css/fontawesome-all.min.css">
    <link rel="icon" type="image/png" href="./assets/icon__icey.png">
</head>
<body>
    <?php include '../include/header.php'; ?>

    <div class="page-layout">
        <main class="page-content">
            <div class="tabla__container tabla__container--solicitudes">
                <div class="maestro__header">
                    <h1 class="planificacion__title">Solicitudes de Escuelas</h1>
                    <button type="button" class="btn-primary" id="btnNuevaSolicitud">
                        <i class="fas fa-plus"></i> Nueva Solicitud
                    </button>
                </div>

                <div id="alertBox" class="maestro__alert maestro__alert--oculto" style="min-height: 2.75em; margin: 0 0 12px; box-sizing: border-box; visibility: hidden;"></div>

                <form class="tabla-buscador tabla-buscador--solicitudes" id="buscarSolicitudesForm" role="search">
                    <label class="sr-only" for="buscarSolicitudes">Buscar solicitud</label>
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <input type="search"
                           id="buscarSolicitudes"
                           placeholder="Buscar solicitud..."
                           autocomplete="off">
                    <button type="button" class="tabla-buscador__limpiar" id="limpiarBusquedaSolicitud" title="Limpiar búsqueda" aria-label="Limpiar búsqueda" hidden>
                        <i class="fas fa-times"></i>
                    </button>
                </form>

                <table class="tabla-planificacion solicitudes-tabla<?= $esAdmin ? ' solicitudes-tabla--admin' : '' ?>" id="tablaSolicitudes">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Institución</th>
                            <th>Fecha</th>
                            <th>Fecha de registro</th>
                            <th>Hora</th>
                            <th>Lugar</th>
                            <th>Responsable</th>
                            <th>Participantes</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <?php if ($esAdmin): ?><th class="col-acciones">Acciones</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody id="tablaBody">
                        <tr><td>Cargando...</td></tr>
                    </tbody>
                </table>
                <nav class="paginacion" id="solicitudPagination" aria-label="Páginas de solicitudes"></nav>
            </div>
        </main>
    </div>

    <div class="maestro-modal" id="solicitudModal" hidden>
        <div class="maestro-modal__backdrop" id="modalBackdrop"></div>
        <div class="maestro-modal__dialog solicitud-modal__dialog">
            <div class="maestro-modal__header">
                <h2 id="modalTitulo">Nueva solicitud</h2>
                <button type="button" class="maestro-modal__close" id="modalClose" aria-label="Cerrar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="solicitudForm" class="solicitud-form" novalidate>
                <input type="hidden" id="formId" value="">
                <div id="modalError" class="maestro__alert maestro__alert--error" hidden></div>
                <div class="config-field">
                    <label for="campo_id_institucion">Institución</label>
                    <select id="campo_id_institucion" name="id_institucion" required>
                        <option value="">-- Seleccione --</option>
                    </select>
                </div>
                <div class="config-field">
                    <label for="campo_fecha_solicitud">Fecha de solicitud</label>
                    <input type="date" id="campo_fecha_solicitud" name="fecha_solicitud" required>
                </div>
                <div class="config-field">
                    <label for="campo_hora_solicitud">Hora de solicitud</label>
                    <input type="time" id="campo_hora_solicitud" name="hora_solicitud" required>
                </div>
                <div class="config-field">
                    <label for="campo_biblioteca">Lugar (biblioteca)</label>
                    <select id="campo_biblioteca" name="biblioteca" required>
                        <option value="">-- Seleccione --</option>
                    </select>
                </div>
                <div class="config-field">
                    <label for="campo_empleado">Responsable (empleado)</label>
                    <select id="campo_empleado" name="empleado" required>
                        <option value="">-- Seleccione --</option>
                    </select>
                </div>
                <div class="config-field">
                    <label for="campo_participantes">Participantes</label>
                    <input type="number" id="campo_participantes" name="participantes" min="0" max="99999" required>
                </div>
                <div class="config-field config-field--descripcion">
                    <label for="campo_descripcion">Descripción</label>
                    <textarea id="campo_descripcion" name="descripcion" maxlength="250" rows="3"></textarea>
                    <span class="solicitud-form__nota">Importante: La descripción aparece al cargar una solicitud.</span>
                </div>
                <div class="config-field">
                    <label for="campo_estado">Estado</label>
                    <select id="campo_estado" name="estado" required>
                        <option value="pendiente">Pendiente</option>
                        <option value="aprobada">Aprobada</option>
                        <option value="rechazada">Rechazada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
                <div class="maestro-modal__actions">
                    <button type="button" class="btn-secondary" id="btnCancelar">Cancelar</button>
                    <button type="submit" class="btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
    <script src="./js/app.js"></script>
    <script>
    (function () {
        const endpoint = '../controladores/solicitud_contr.php';
        const esAdmin = <?= json_encode($esAdmin) ?>;
        // Debe coincidir con TRANSICIONES_ESTADO de solicitud_contr.php (el servidor es quien manda).
        const TRANSICIONES_ESTADO = {
            pendiente: ['pendiente', 'aprobada', 'rechazada', 'cancelada'],
            aprobada:  ['aprobada', 'cancelada'],
            rechazada: ['rechazada', 'cancelada'],
            cancelada: ['cancelada'],
        };
        const columnasTabla = esAdmin ? 11 : 10;
        const tablaBody = document.getElementById('tablaBody');
        const paginacion = document.getElementById('solicitudPagination');
        const alertBox = document.getElementById('alertBox');
        const modal = document.getElementById('solicitudModal');
        const modalBackdrop = document.getElementById('modalBackdrop');
        const modalTitulo = document.getElementById('modalTitulo');
        const modalClose = document.getElementById('modalClose');
        const btnCancelar = document.getElementById('btnCancelar');
        const solicitudForm = document.getElementById('solicitudForm');
        const formId = document.getElementById('formId');
        const modalErrorBox = document.getElementById('modalError');
        const btnNuevaSolicitud = document.getElementById('btnNuevaSolicitud');
        const buscarSolicitudesForm = document.getElementById('buscarSolicitudesForm');
        const buscarSolicitudes = document.getElementById('buscarSolicitudes');
        const limpiarBusquedaSolicitud = document.getElementById('limpiarBusquedaSolicitud');
        const campoBiblioteca = document.getElementById('campo_biblioteca');
        const campoEmpleado = document.getElementById('campo_empleado');
        let instituciones = [];
        let bibliotecas = [];
        let empleados = [];
        const filasPorPagina = 10;
        let paginaActual = 1;
        let solicitudesCargadas = [];

        function normalizarTexto(valor) {
            return String(valor != null ? valor : '')
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLowerCase();
        }

        function formatearEstado(estado) {
            const texto = String(estado || 'pendiente');
            return texto.charAt(0).toUpperCase() + texto.slice(1);
        }

        function solicitudesFiltradas() {
            const termino = normalizarTexto(buscarSolicitudes.value.trim());
            if (!termino) return solicitudesCargadas;
            return solicitudesCargadas.filter(solicitud =>
                normalizarTexto(Object.values(solicitud).join(' ')).includes(termino)
            );
        }

        function mostrarAlerta(mensaje, tipo) {
            alertBox.textContent = mensaje;
            alertBox.className = 'maestro__alert maestro__alert--' + tipo;
            alertBox.style.visibility = 'visible';
            setTimeout(() => { alertBox.style.visibility = 'hidden'; }, 4000);
        }

        function mostrarErrorModal(mensaje) {
            modalErrorBox.textContent = mensaje;
            modalErrorBox.hidden = false;
        }

        function ocultarErrorModal() {
            modalErrorBox.textContent = '';
            modalErrorBox.hidden = true;
        }

        function abrirModalNuevo() {
            formId.value = '';
            modalTitulo.textContent = 'Nueva solicitud';
            solicitudForm.reset();
            establecerFechaMinimaSolicitud();
            ocultarErrorModal();
            llenarInstituciones();
            llenarBibliotecas();
            llenarEmpleados();
            restringirEstados('');
            modal.hidden = false;
        }

        function establecerFechaMinimaSolicitud() {
            const ahora = new Date();
            const hoy = [
                ahora.getFullYear(),
                String(ahora.getMonth() + 1).padStart(2, '0'),
                String(ahora.getDate()).padStart(2, '0'),
            ].join('-');
            document.getElementById('campo_fecha_solicitud').min = hoy;
        }

        function abrirModalEditar(solicitud) {
            formId.value = solicitud.id;
            modalTitulo.textContent = 'Editar solicitud';
            solicitudForm.reset();
            establecerFechaMinimaSolicitud();
            ocultarErrorModal();
            const estadoActual = String(solicitud.estado || 'pendiente').toLowerCase();
            llenarInstituciones(solicitud.id_institucion);
            llenarBibliotecas(solicitud.id_biblioteca);
            llenarEmpleados(solicitud.responsable);
            document.getElementById('campo_fecha_solicitud').value = solicitud.fecha_solicitud;
            document.getElementById('campo_hora_solicitud').value = String(solicitud.hora_solicitud || '').slice(0, 5);
            document.getElementById('campo_participantes').value = solicitud.participantes;
            document.getElementById('campo_descripcion').value = solicitud.descripcion;
            document.getElementById('campo_estado').value = estadoActual;
            restringirEstados(estadoActual);
            modal.hidden = false;
        }

        function cerrarModal() {
            modal.hidden = true;
        }

        function llenarInstituciones(seleccionado = '') {
            const select = document.getElementById('campo_id_institucion');
            select.innerHTML = '<option value="">-- Seleccione --</option>';
            instituciones.forEach(inst => {
                const option = document.createElement('option');
                option.value = inst.id;
                option.textContent = inst.nombre;
                if (String(inst.id) === String(seleccionado)) {
                    option.selected = true;
                }
                select.appendChild(option);
            });
        }

        // El responsable se conserva como nombre; si no está en el maestro,
        // se muestra temporalmente como opción actual al editar.
        function agregarOpcionActual(select, texto) {
            const option = document.createElement('option');
            option.value = '__actual__';
            option.textContent = texto;
            option.selected = true;
            select.appendChild(option);
        }

        function llenarBibliotecas(idActual = '') {
            const select = document.getElementById('campo_biblioteca');
            select.innerHTML = '<option value="">-- Seleccione --</option>';
            bibliotecas.forEach(bib => {
                const option = document.createElement('option');
                option.value = bib.id;
                option.textContent = bib.nombre;
                option.selected = idActual !== '' && String(bib.id) === String(idActual);
                select.appendChild(option);
            });
        }

        function llenarEmpleados(nombreActual = '') {
            const select = document.getElementById('campo_empleado');
            select.innerHTML = '<option value="">-- Seleccione --</option>';
            let encontrado = false;
            empleados.forEach(emp => {
                const nombreCompleto = `${emp.nombre} ${emp.apellido}`.trim();
                const option = document.createElement('option');
                option.value = emp.id;
                option.textContent = nombreCompleto;
                if (nombreActual && normalizarTexto(nombreCompleto) === normalizarTexto(nombreActual)) {
                    option.selected = true;
                    encontrado = true;
                }
                select.appendChild(option);
            });
            if (nombreActual && !encontrado) agregarOpcionActual(select, nombreActual);
        }

        function textoSeleccionado(select) {
            const option = select.selectedOptions[0];
            return select.value && option ? option.textContent.trim() : '';
        }

        // Deshabilita los estados a los que no se puede pasar desde el estado actual.
        function restringirEstados(estadoActual) {
            const select = document.getElementById('campo_estado');
            const permitidos = estadoActual ? (TRANSICIONES_ESTADO[estadoActual] || [estadoActual]) : null;
            Array.from(select.options).forEach(opt => {
                opt.disabled = permitidos ? !permitidos.includes(opt.value) : false;
            });
        }

        async function cargarInstituciones() {
            try {
                const resp = await fetch('../controladores/institucion_contr.php?action=listar');
                const json = await resp.json();
                if (json.success) {
                    instituciones = json.data;
                    llenarInstituciones();
                }
            } catch (e) {
                console.error('No se pudieron cargar las instituciones', e);
            }
        }

        async function cargarBibliotecas() {
            try {
                const resp = await fetch('../controladores/biblioteca_contr.php?action=listar');
                const json = await resp.json();
                if (json.success) {
                    bibliotecas = json.data;
                    llenarBibliotecas();
                }
            } catch (e) {
                console.error('No se pudieron cargar las bibliotecas', e);
            }
        }

        async function cargarEmpleados() {
            try {
                const resp = await fetch('../controladores/empleado_contr.php?action=listar');
                const json = await resp.json();
                if (json.success) {
                    empleados = json.data;
                    llenarEmpleados();
                }
            } catch (e) {
                console.error('No se pudieron cargar los empleados', e);
            }
        }

        async function cargarSolicitudes() {
            tablaBody.innerHTML = '<tr><td colspan="' + columnasTabla + '">Cargando...</td></tr>';
            try {
                const resp = await fetch(endpoint + '?action=listar');
                const json = await resp.json();
                if (!json.success) {
                    tablaBody.innerHTML = '<tr><td colspan="' + columnasTabla + '">No se pudieron cargar las solicitudes.</td></tr>';
                    return;
                }
                solicitudesCargadas = json.data || [];
                const totalPaginas = Math.max(1, Math.ceil(solicitudesCargadas.length / filasPorPagina));
                paginaActual = Math.min(paginaActual, totalPaginas);
                renderTabla();
            } catch (e) {
                tablaBody.innerHTML = '<tr><td colspan="' + columnasTabla + '">Error al conectar con el servidor.</td></tr>';
            }
        }

        function renderTabla() {
            const solicitudes = solicitudesFiltradas();
            const totalPaginas = Math.max(1, Math.ceil(solicitudes.length / filasPorPagina));
            const inicio = (paginaActual - 1) * filasPorPagina;
            const rows = solicitudes.slice(inicio, inicio + filasPorPagina);
            if (!rows.length) {
                tablaBody.innerHTML = '<tr><td colspan="' + columnasTabla + '">' + (buscarSolicitudes.value.trim() ? 'No se encontraron solicitudes para esa búsqueda.' : 'No hay solicitudes registradas.') + '</td></tr>';
                renderPaginacion(totalPaginas);
                return;
            }
            const html = rows.map(row => `
                <tr>
                    <td>${row.id}</td>
                    <td>${escapeHtml(row.institucion)}</td>
                    <td>${escapeHtml(row.fecha_solicitud)}</td>
                    <td>${escapeHtml(String(row.fecha_registro || '').replace('T', ' ').slice(0, 16))}</td>
                    <td>${escapeHtml(row.hora_solicitud)}</td>
                    <td>${escapeHtml(row.lugar)}</td>
                    <td>${escapeHtml(row.responsable)}</td>
                    <td>${escapeHtml(row.participantes)}</td>
                    <td>${escapeHtml(row.descripcion)}</td>
                    <td>${escapeHtml(formatearEstado(row.estado))}</td>
                    ${esAdmin ? `<td class="col-acciones">
                        <button type="button" class="btn-icon btn-edit" data-id="${row.id}"><i class="fas fa-pen"></i></button>
                        <button type="button" class="btn-icon btn-delete" data-id="${row.id}"><i class="fas fa-trash"></i></button>
                    </td>` : ''}
                </tr>
            `).join('');
            tablaBody.innerHTML = html;
            tablaBody.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const id = btn.dataset.id;
                    const solicitud = rows.find(r => String(r.id) === String(id));
                    if (solicitud) abrirModalEditar(solicitud);
                });
            });
            tablaBody.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', () => abrirConfirmarEliminar(btn.dataset.id));
            });
            renderPaginacion(totalPaginas);
        }

        buscarSolicitudesForm.addEventListener('submit', (event) => event.preventDefault());
        buscarSolicitudes.addEventListener('input', () => {
            paginaActual = 1;
            limpiarBusquedaSolicitud.hidden = !buscarSolicitudes.value.trim();
            renderTabla();
        });
        limpiarBusquedaSolicitud.addEventListener('click', () => {
            buscarSolicitudes.value = '';
            limpiarBusquedaSolicitud.hidden = true;
            paginaActual = 1;
            buscarSolicitudes.focus();
            renderTabla();
        });

        function renderPaginacion(totalPaginas) {
            if (!paginacion || totalPaginas <= 1) {
                if (paginacion) paginacion.innerHTML = '';
                return;
            }
            let html = '';
            for (let pagina = 1; pagina <= totalPaginas; pagina++) {
                html += '<button type="button" class="paginacion__pagina' + (pagina === paginaActual ? ' activa' : '') + '"' +
                    (pagina === paginaActual ? ' aria-current="page"' : '') + ' data-page="' + pagina + '">' + pagina + '</button>';
            }
            paginacion.innerHTML = html;
            paginacion.querySelectorAll('[data-page]').forEach((boton) => {
                boton.addEventListener('click', () => {
                    paginaActual = Number(boton.dataset.page);
                    renderTabla();
                });
            });
        }

        function escapeHtml(text) {
            if (text === null || text === undefined) return '';
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function abrirConfirmarEliminar(id) {
            if (!confirm('¿Eliminar esta solicitud? Esta acción no se puede deshacer.')) {
                return;
            }
            eliminarSolicitud(id);
        }

        async function eliminarSolicitud(id) {
            try {
                const body = new URLSearchParams();
                body.append('action', 'eliminar');
                body.append('id', id);
                const resp = await fetch(endpoint, { method: 'POST', body });
                const json = await resp.json();
                mostrarAlerta(json.message, json.success ? 'success' : 'error');
                if (json.success) cargarSolicitudes();
            } catch (e) {
                mostrarAlerta('Error de conexión con el servidor.', 'error');
            }
        }

        solicitudForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            ocultarErrorModal();

            const data = {
                id_institucion: document.getElementById('campo_id_institucion').value,
                id_biblioteca: campoBiblioteca.value,
                id_empleado: campoEmpleado.value,
                fecha_solicitud: document.getElementById('campo_fecha_solicitud').value,
                hora_solicitud: document.getElementById('campo_hora_solicitud').value,
                lugar: textoSeleccionado(campoBiblioteca),
                responsable: textoSeleccionado(campoEmpleado),
                participantes: document.getElementById('campo_participantes').value,
                descripcion: document.getElementById('campo_descripcion').value.trim(),
                estado: document.getElementById('campo_estado').value,
            };

            if (!data.id_institucion) { mostrarErrorModal('Seleccione una institución.'); return; }
            if (!data.id_biblioteca) { mostrarErrorModal('Seleccione la biblioteca de la solicitud.'); return; }
            if (!data.id_empleado) { mostrarErrorModal('Seleccione el empleado responsable.'); return; }
            if (!data.fecha_solicitud) { mostrarErrorModal('Seleccione la fecha de la solicitud.'); return; }
            establecerFechaMinimaSolicitud();
            if (data.fecha_solicitud < document.getElementById('campo_fecha_solicitud').min) {
                mostrarErrorModal('La fecha de solicitud no puede ser anterior a la fecha actual.');
                return;
            }
            if (!data.hora_solicitud) { mostrarErrorModal('Indique la hora de la solicitud.'); return; }
            if (!data.lugar) { mostrarErrorModal('Seleccione la biblioteca (lugar) de la solicitud.'); return; }
            if (!data.responsable) { mostrarErrorModal('Seleccione el empleado responsable de la solicitud.'); return; }
            if (data.participantes === '') { mostrarErrorModal('Indique la cantidad de participantes.'); return; }

            const body = new URLSearchParams();
            const esEdicion = formId.value !== '';
            body.append('action', esEdicion ? 'actualizar' : 'crear');
            if (esEdicion) body.append('id', formId.value);
            Object.entries(data).forEach(([key, value]) => body.append(key, value));

            try {
                const resp = await fetch(endpoint, { method: 'POST', body });
                const json = await resp.json();
                if (!json.success) {
                    mostrarErrorModal(json.message);
                    return;
                }
                cerrarModal();
                cargarSolicitudes();
                mostrarAlerta(json.message, 'success');
            } catch (e) {
                mostrarErrorModal('Error de conexión con el servidor.');
            }
        });

        btnNuevaSolicitud.addEventListener('click', abrirModalNuevo);
        modalClose.addEventListener('click', cerrarModal);
        btnCancelar.addEventListener('click', cerrarModal);
        modalBackdrop.addEventListener('click', cerrarModal);

        window.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.hidden) {
                cerrarModal();
            }
        });

        cargarInstituciones();
        cargarBibliotecas();
        cargarEmpleados();
        cargarSolicitudes();
    })();
    </script>
</body>
</html>
