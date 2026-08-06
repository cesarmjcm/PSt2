document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modalMunicipio');
    if (!modal) return; // Esta página no es el maestro de municipios

    const modalTitulo = document.getElementById('modalMunicipioTitulo');
    const form = document.getElementById('formMunicipio');
    const inputId = document.getElementById('municipioId');
    const inputNombre = document.getElementById('municipioNombre');
    const errorBox = document.getElementById('modalMunicipioError');
    const tabla = document.getElementById('tablaMunicipios');
    const tbody = tabla ? tabla.querySelector('tbody') : null;

    const ENDPOINT = '../controladores/municipio_contr.php';

    function abrirModal(modo, datos = {}) {
        form.reset();
        errorBox.style.display = 'none';
        inputId.value = datos.id || '';
        inputNombre.value = datos.nombre || '';
        modalTitulo.textContent = modo === 'editar' ? 'Editar municipio' : 'Agregar municipio';
        modal.classList.add('is-open');
        inputNombre.focus();
    }

    function cerrarModal() {
        modal.classList.remove('is-open');
    }

    function mostrarError(mensaje) {
        errorBox.textContent = mensaje;
        errorBox.style.display = 'block';
    }

    // Abrir modal en modo "agregar"
    const btnAgregar = document.getElementById('btnAgregarMunicipio');
    if (btnAgregar) {
        btnAgregar.addEventListener('click', () => abrirModal('agregar'));
    }

    // Cerrar modal: botón X, botón cancelar, click fuera de la caja, tecla Escape
    document.getElementById('btnCerrarModalMunicipio').addEventListener('click', cerrarModal);
    document.getElementById('btnCancelarModalMunicipio').addEventListener('click', cerrarModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) cerrarModal();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('is-open')) cerrarModal();
    });

    // Abrir modal en modo "editar" tomando los datos de la fila
    if (tbody) {
        tbody.addEventListener('click', (e) => {
            const filaEditar = e.target.closest('.btn-editar-municipio');
            const filaEliminar = e.target.closest('.btn-eliminar-municipio');

            if (filaEditar) {
                const fila = filaEditar.closest('tr');
                abrirModal('editar', {
                    id: fila.dataset.id,
                    nombre: fila.querySelector('.celda-nombre').textContent.trim(),
                });
            }

            if (filaEliminar) {
                const fila = filaEliminar.closest('tr');
                eliminarMunicipio(fila.dataset.id, fila);
            }
        });
    }

    // Guardar (crear o actualizar) vía fetch
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        errorBox.style.display = 'none';

        const id = inputId.value;
        const accion = id ? 'actualizar' : 'crear';

        const datosForm = new FormData();
        datosForm.append('action', accion);
        if (id) datosForm.append('id', id);
        datosForm.append('nombre', inputNombre.value.trim());

        try {
            const resp = await fetch(ENDPOINT, { method: 'POST', body: datosForm });
            const data = await resp.json();

            if (!data.success) {
                mostrarError(data.message || 'No se pudo guardar el municipio.');
                return;
            }

            cerrarModal();
            await recargarTabla();
        } catch (err) {
            mostrarError('Error de conexión. Intenta nuevamente.');
        }
    });

    async function eliminarMunicipio(id, fila) {
        if (!confirm('¿Seguro que deseas eliminar este municipio?')) return;

        const datosForm = new FormData();
        datosForm.append('action', 'eliminar');
        datosForm.append('id', id);

        try {
            const resp = await fetch(ENDPOINT, { method: 'POST', body: datosForm });
            const data = await resp.json();

            if (!data.success) {
                alert(data.message || 'No se pudo eliminar el municipio.');
                return;
            }

            fila.remove();
        } catch (err) {
            alert('Error de conexión. Intenta nuevamente.');
        }
    }

    async function recargarTabla() {
        try {
            const datosForm = new FormData();
            datosForm.append('action', 'listar');
            const resp = await fetch(ENDPOINT, { method: 'POST', body: datosForm });
            const data = await resp.json();

            if (!data.success || !tbody) return;

            tbody.innerHTML = '';

            if (data.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3">No hay municipios registrados.</td></tr>';
                return;
            }

            data.data.forEach((m) => {
                const tr = document.createElement('tr');
                tr.dataset.id = m.id;
                tr.innerHTML = `
                    <td>${escapeHtml(m.id)}</td>
                    <td class="celda-nombre">${escapeHtml(m.nombre)}</td>
                    <td>
                        <button type="button" class="btn-edit btn-editar-municipio"><i class="fas fa-pen"></i> Editar</button>
                        <button type="button" class="btn-delete btn-eliminar-municipio"><i class="fas fa-trash"></i> Eliminar</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        } catch (err) {
            // Si falla la recarga silenciosa, no interrumpimos al usuario
        }
    }

    function escapeHtml(texto) {
        const div = document.createElement('div');
        div.textContent = texto ?? '';
        return div.innerHTML;
    }
});
