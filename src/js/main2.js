// Vista de inicio (main2.php): botón "Nueva actividad" y modal de planificación.
// Requiere utilidades.js y validaciones.js.

document.addEventListener("DOMContentLoaded", () => {
    const selectMunicipios = document.getElementById("planificacion-municipios");
    if (!selectMunicipios) return;

    fetch("../controladores/municipio_contr.php?action=listar")
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                selectMunicipios.innerHTML = '<option value="">Seleccione un municipio</option>';
                
                res.data.forEach(mun => {
                    const option = document.createElement("option");
                    option.value = mun.id;
                    option.textContent = mun.nombre;
                    selectMunicipios.appendChild(option);
                });
            }
        })
        .catch(error => console.error("Error cargando municipios:", error));
});

document.addEventListener('DOMContentLoaded', () => {
    const modals = document.querySelectorAll('#modalPlanificacion');
    const btnNuevaActividad = document.getElementById('btnNuevaActividad');
    const btnCerrar = document.querySelectorAll('.close-button');

    const openModals = () => {
        if (modals.length === 0) return;
        modals.forEach(m => {
            m.style.display = 'flex';
        });
        document.documentElement.style.overflow = 'hidden';
        document.body.style.overflow = 'hidden';
        document.body.style.height = '100vh';
    };

    const closeModals = () => {
        if (modals.length === 0) return;
        modals.forEach(m => {
            m.style.display = 'none';
        });
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
        document.body.style.height = '';
    };


    if (btnNuevaActividad && modals.length > 0) {
        btnNuevaActividad.addEventListener('click', (e) => {
            e.preventDefault();
            resetFormularioPlanificacion();
            openModals();
        });
    }

    if (btnCerrar.length > 0 && modals.length > 0) {
        btnCerrar.forEach((btn) => {
            btn.addEventListener('click', () => {
                closeModals();
            });
        });
    }

    if (modals.length > 0) {
        modals.forEach(m => {
            m.addEventListener('click', (ev) => {
                if (ev.target === m) closeModals();
            });
        });
    }

    document.addEventListener('keydown', (ev) => {
        if (ev.key === 'Escape') closeModals();
    });

    const planMunicipio = document.getElementById('planificacion-municipios');
    const municipioHidden = document.getElementById('municipio-hidden');
    const planFechaInput = document.getElementById('plan-fecha');
    const planDiaInput = document.getElementById('plan-dia');

    const toggleMunicipio = () => {
        if (!municipioHidden || !planMunicipio) return;
        const show = planMunicipio.value.trim().length > 0;
        municipioHidden.classList.toggle('hidden', !show);
    };

    if (planMunicipio) {
        planMunicipio.addEventListener('change', toggleMunicipio);
        toggleMunicipio();
    }

    const tipoFormularioRadios = document.querySelectorAll('input[name="tipo_formulario"]');
    const camposCompleta = document.getElementById('campos-completa');
    const tipoUbicacionRadios = document.querySelectorAll('input[name="tipo_ubicacion"]');
    const ubicacionBiblioteca = document.getElementById('ubicacion-biblioteca');
    const ubicacionEspacio = document.getElementById('ubicacion-espacio');

    const toggleTipoFormulario = () => {
        if (!camposCompleta) return;
        const completaRadio = document.getElementById('tipo-formulario-completa');
        const show = completaRadio ? completaRadio.checked : false;
        camposCompleta.classList.toggle('hidden', !show);
    };

    const toggleTipoUbicacion = () => {
        const radioSeleccionado = document.querySelector('input[name="tipo_ubicacion"]:checked');
        const tipo = radioSeleccionado ? radioSeleccionado.value : 'biblioteca';

        if (ubicacionBiblioteca) {
            ubicacionBiblioteca.classList.toggle('hidden', tipo !== 'biblioteca');
        }
        if (ubicacionEspacio) {
            ubicacionEspacio.classList.toggle('hidden', tipo !== 'espacio');
        }
    };

    if (tipoFormularioRadios.length > 0) {
        tipoFormularioRadios.forEach((radio) => radio.addEventListener('change', toggleTipoFormulario));
        toggleTipoFormulario();
    }

    if (tipoUbicacionRadios.length > 0) {
        tipoUbicacionRadios.forEach((radio) => radio.addEventListener('change', toggleTipoUbicacion));
        toggleTipoUbicacion();
    }

    if (planFechaInput && planDiaInput) {
        if (planFechaInput.value) {
            planDiaInput.value = calcularDiaSemana(planFechaInput.value);
        }
        planFechaInput.addEventListener('change', () => {
            planDiaInput.value = calcularDiaSemana(planFechaInput.value);
        });
    }

    function resetFormularioPlanificacion() {
        const form = document.getElementById('form-planificacion');
        const titulo = document.getElementById('planModalTitulo');
        const planAction = document.getElementById('plan-action');
        const actividadId = document.getElementById('actividad-id');
        const solicitudesSelector = document.getElementById('solicitudesSelector');

        if (form) form.reset();
        (function () { var o = document.querySelector('#plan-responsable option[value="__actual__"]'); if (o) o.remove(); })();
        if (titulo) titulo.textContent = 'Nueva Planificación de Actividad';
        if (planAction) planAction.value = 'crear';
        if (actividadId) actividadId.value = '';
        if (solicitudesSelector) solicitudesSelector.hidden = true;

        limpiarCamposInvalidos(form);
        ocultarAvisoFormulario();

        toggleMunicipio();
        toggleTipoFormulario();
        toggleTipoUbicacion();
    }

    const btnCargarSolicitud = document.getElementById('btnCargarSolicitud');
    const solicitudesSelector = document.getElementById('solicitudesSelector');
    const solicitudSeleccion = document.getElementById('solicitudSeleccion');
    let solicitudesCache = [];
    let solicitudesCargadas = false;
    let errorCargaSolicitudes = null;

    async function cargarSolicitudesDeServidor() {
        try {
            const response = await fetch('../controladores/solicitud_contr.php?action=listar&solo_aprobadas=1');
            if (!response.ok) throw new Error('Error cargando solicitudes');
            const data = await response.json();
            if (!data.success) throw new Error(data.message || 'Error cargando solicitudes');
            solicitudesCache = Array.isArray(data.data) ? data.data : [];
            solicitudesCargadas = true;
            errorCargaSolicitudes = null;
        } catch (error) {
            console.error(error);
            errorCargaSolicitudes = error;
        }
    }

    function renderSolicitudesSelector() {
        if (!solicitudSeleccion) return;
        const textoInicial = errorCargaSolicitudes
            ? 'No se pudieron cargar las solicitudes.'
            : (solicitudesCache.length ? 'Seleccione una solicitud...' : 'No hay solicitudes disponibles.');
        solicitudSeleccion.replaceChildren(new Option(textoInicial, ''));
        solicitudSeleccion.disabled = Boolean(errorCargaSolicitudes) || !solicitudesCache.length;

        solicitudesCache.forEach(solicitud => {
            const nombre = solicitud.nombre_actividad || solicitud.descripcion || solicitud.lugar;
            const subtitle = solicitud.responsable ? `${solicitud.responsable} · ${solicitud.fecha_solicitud} ${solicitud.hora_solicitud}` : `${solicitud.fecha_solicitud} ${solicitud.hora_solicitud}`;
            const etiqueta = [nombre, subtitle].filter(Boolean).join(' — ');
            solicitudSeleccion.add(new Option(etiqueta, String(solicitud.id)));
        });
    }

    function aplicarSolicitudAlFormulario(solicitud) {
        const planTipo = document.getElementById('plan-tipo');
        const planDescripcion = document.getElementById('plan-descripcion');
        const planFecha = document.getElementById('plan-fecha');
        const planHora = document.getElementById('plan-hora');
        const planParticipantes = document.getElementById('plan-participantes');
        const planBiblioteca = document.getElementById('plan-biblioteca');
        const selectMunicipio = document.getElementById('planificacion-municipios');
        const selectParroquia = document.getElementById('plan-parroquia');
        const planResponsable = document.getElementById('plan-responsable');
        const planTelefono = document.getElementById('plan-telefono');

        if (planTipo) {
            planTipo.value = solicitud.nombre_actividad || solicitud.lugar || solicitud.descripcion || '';
            planTipo.dispatchEvent(new Event('input', { bubbles: true }));
        }
        if (planDescripcion) {
            planDescripcion.value = solicitud.descripcion || '';
        }
        if (planFecha) {
            planFecha.value = solicitud.fecha_solicitud || '';
            planFecha.dispatchEvent(new Event('change', { bubbles: true }));
        }
        if (planHora) {
            planHora.value = solicitud.hora_solicitud || '';
        }
        if (planParticipantes) {
            planParticipantes.value = solicitud.participantes || '';
        }
        if (planBiblioteca) {
            const bibliotecaNombre = (solicitud.lugar || '').trim();
            const opcionesBiblioteca = Array.from(planBiblioteca.options);
            const bibliotecaEncontrada = opcionesBiblioteca.find(opt =>
                opt.textContent.trim().toLowerCase() === bibliotecaNombre.toLowerCase()
            ) || opcionesBiblioteca.find(opt => String(opt.value) === String(solicitud.id_biblioteca || ''));

            if (bibliotecaEncontrada) {
                planBiblioteca.value = bibliotecaEncontrada.value;
                planBiblioteca.dispatchEvent(new Event('change'));
            }
        }

        if (selectMunicipio && selectParroquia && solicitud.municipio_id && solicitud.parroquia_id) {
            selectMunicipio.value = String(solicitud.municipio_id);
            selectMunicipio.dispatchEvent(new Event('change', { bubbles: true }));

            const opcionParroquia = Array.from(selectParroquia.options)
                .find(opcion => opcion.dataset.id === String(solicitud.parroquia_id));
            if (opcionParroquia) {
                selectParroquia.value = opcionParroquia.value;
                selectParroquia.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }
        if (planResponsable) {
            (function () { var o = planResponsable.querySelector('option[value="__actual__"]'); if (o) o.remove(); })();
            const nombreResponsable = String(solicitud.responsable || '').trim().toLocaleLowerCase();
            const opcion = Array.from(planResponsable.options).find(item =>
                item.textContent.trim().toLocaleLowerCase() === nombreResponsable
            );
            if (opcion) {
                planResponsable.value = opcion.value;
                planResponsable.dispatchEvent(new Event('change'));
            } else {
                planResponsable.value = '';
            }
        }
        if (planTelefono && (planResponsable && planResponsable.value === '')) planTelefono.value = '';

        if (solicitudesSelector) solicitudesSelector.hidden = true;
        if (solicitudSeleccion) solicitudSeleccion.value = '';
    }

    function toggleSolicitudesSelector() {
        if (!solicitudesSelector || !btnCargarSolicitud) return;
        if (solicitudesSelector.hidden) {
            solicitudesSelector.hidden = false;
            if (!solicitudesCargadas) {
                if (solicitudSeleccion) {
                    solicitudSeleccion.replaceChildren(new Option('Cargando solicitudes...', ''));
                    solicitudSeleccion.disabled = true;
                }
                cargarSolicitudesDeServidor().then(renderSolicitudesSelector);
            } else {
                renderSolicitudesSelector();
            }
        } else {
            solicitudesSelector.hidden = true;
        }
    }

    function ocultarSolicitudesSelector() {
        if (solicitudesSelector) solicitudesSelector.hidden = true;
    }

    if (solicitudSeleccion) {
        solicitudSeleccion.addEventListener('change', () => {
            const solicitud = solicitudesCache.find(item => String(item.id) === solicitudSeleccion.value);
            if (solicitud) aplicarSolicitudAlFormulario(solicitud);
        });
    }

    if (btnCargarSolicitud) {
        btnCargarSolicitud.addEventListener('click', (e) => {
            e.preventDefault();
            toggleSolicitudesSelector();
        });
    }
});
