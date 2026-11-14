// Vista de planificación (index.php): tabla de actividades y modal "Editar actividad".
// Requiere utilidades.js y validaciones.js.

document.addEventListener('DOMContentLoaded', () => {
    const editarFecha = document.getElementById('editar-fecha');
    const editarDia = document.getElementById('editar-dia');

    if (editarFecha && editarDia) {
        if (editarFecha.value) {
            editarDia.value = calcularDiaSemana(editarFecha.value);
        }
        editarFecha.addEventListener('change', () => {
            editarDia.value = calcularDiaSemana(editarFecha.value);
        });
    }

    const modalEditar = document.getElementById('modalEditarActividad');
    const btnCerrarEditar = document.querySelectorAll('.close-button-editar');

    function openModalEditar() {
        if (!modalEditar) return;
        modalEditar.style.display = 'flex';
        document.documentElement.style.overflow = 'hidden';
        document.body.style.overflow = 'hidden';
        document.body.style.height = '100vh';
    }

    function closeModalEditar() {
        if (!modalEditar) return;
        modalEditar.style.display = 'none';
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
        document.body.style.height = '';
    }

    btnCerrarEditar.forEach((btn) => {
        btn.addEventListener('click', () => closeModalEditar());
    });

    if (modalEditar) {
        modalEditar.addEventListener('click', (ev) => {
            if (ev.target === modalEditar) closeModalEditar();
        });
    }

    document.addEventListener('keydown', (ev) => {
        if (ev.key === 'Escape') closeModalEditar();
    });

    function setValue(id, value) {
        const el = document.getElementById(id);
        if (el) el.value = value || '';
    }

    function abrirEditarActividad(btn) {
        setValue('editar-id', btn.dataset.id);
        setValue('editar-nombre', btn.dataset.nombre);
        setValue('editar-descripcion', btn.dataset.descripcion);
        setValue('editar-objetivo', btn.dataset.objetivo);
        setValue('editar-participantes', btn.dataset.participantes);
        setValue('editar-fecha', btn.dataset.fecha);
        setValue('editar-hora', btn.dataset.hora);
        setValue('editar-dia', btn.dataset.dia);

        setValue('editar-nivel-impacto', btn.dataset.nivelImpacto);
        // Cada select filtra al siguiente: municipio -> parroquia -> comuna.
        const selMunicipio = document.getElementById('editar-municipio');
        const selParroquia = document.getElementById('editar-parroquia');
        setValue('editar-municipio', btn.dataset.municipioId);
        if (selMunicipio) selMunicipio.dispatchEvent(new Event('change'));
        setValue('editar-parroquia', btn.dataset.parroquia);
        if (selParroquia) selParroquia.dispatchEvent(new Event('change'));
        setValue('editar-comuna', btn.dataset.comuna);
        const selComuna = document.getElementById('editar-comuna');
        if (selComuna) selComuna.dispatchEvent(new Event('change'));
        setValue('editar-estado', btn.dataset.estado);
        if (typeof window.restringirEstadoActividad === 'function') {
            window.restringirEstadoActividad(btn.dataset.estado);
        }

        setValue('editar-tipo-actividad', (btn.dataset.tipoActividad != null ? btn.dataset.tipoActividad : btn.dataset.idTipoActividad));
        if (typeof window.seleccionarResponsableActividad === 'function') {
            window.seleccionarResponsableActividad(btn.dataset.responsable, btn.dataset.telefono);
        }

        
        
        
        
        
        
        const tieneEspacio = !!btn.dataset.espacio && btn.dataset.espacio !== '0';
        const radioBiblioteca = document.getElementById('editar-tipo-ubicacion-biblioteca');
        const radioEspacio = document.getElementById('editar-tipo-ubicacion-espacio');
        if (tieneEspacio && radioEspacio) {
            radioEspacio.checked = true;
        } else if (radioBiblioteca) {
            radioBiblioteca.checked = true;
        }
        if (typeof window.actualizarUbicacionEditar === 'function') {
            window.actualizarUbicacionEditar();
        }
        if (typeof window.seleccionarEspacioGuardadoEditar === 'function') {
            window.seleccionarEspacioGuardadoEditar(btn.dataset.espacio);
        } else {
            setValue('editar-espacio', btn.dataset.espacio);
        }
        setValue('editar-biblioteca', btn.dataset.idBiblioteca);

        openModalEditar();
    }

    document.querySelectorAll('.btn-edit-actividad').forEach((btn) => {
        btn.addEventListener('click', () => abrirEditarActividad(btn));
    });

    
    document.querySelectorAll('.btn-ver-mas').forEach((btn) => {
        btn.addEventListener('click', () => {
            const fila = document.getElementById(btn.dataset.target);
            if (!fila) return;

            const expandido = btn.getAttribute('aria-expanded') === 'true';
            fila.style.display = expandido ? 'none' : 'table-row';
            btn.setAttribute('aria-expanded', String(!expandido));
            btn.title = expandido ? 'Ver más' : 'Ver menos';
            btn.innerHTML = expandido
                ? '<i class="fas fa-chevron-down"></i>'
                : '<i class="fas fa-chevron-up"></i>';
        });
    });
});
