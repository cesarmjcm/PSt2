
function calcularDiaSemana(fechaString) {
    const dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    if (!fechaString) return '';

    const [year, month, day] = fechaString.split('-').map(Number);
    if (!year || !month || !day) return '';

    const fecha = new Date(year, month - 1, day);
    if (Number.isNaN(fecha.getTime())) return '';
    return dias[fecha.getDay()];
}

const REGEX_TEXTO_VALIDO = /^[A-Za-zÁÉÍÓÚáéíóúÑñÜü0-9 '\-.]+$/u;
const REGEX_NOMBRE_PROPIO = /^[A-Za-zÁÉÍÓÚáéíóúÑñÜü '\-]+$/u;
const REGEX_TELEFONO = /^[0-9\-\+ ]{11}$/;

const MAXLEN_ACTIVIDAD_NOMBRE = 30;
const MAXLEN_DESCRIPCION = 200;
const MAXLEN_PARROQUIA = 30;
const MAXLEN_ESPACIO = 30;
const MAXLEN_RESPONSABLE = 30;
const MAXLEN_OBJETIVO = 50;
const MAXLEN_NIVEL_IMPACTO = 20;
const MAX_PARTICIPANTES = 99999;

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

function esVacio(valor) {
    return valor === null || valor === undefined || valor.trim() === '';
}

function marcarCampoInvalido(campo) {
    if (campo) campo.style.borderColor = '#dc3545';
}

function limpiarCamposInvalidos(form) {
    if (!form) return;
    form.querySelectorAll('input, select, textarea').forEach(el => {
        el.style.borderColor = '';
    });
}

function escapeHtml(value) {
    if (value === null || value === undefined) return '';
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function mostrarAvisoFormulario(mensaje, campoAEnfocar, avisoId) {
    const aviso = document.getElementById(avisoId || 'form-planificacion-aviso');
    if (aviso) {
        aviso.textContent = mensaje;
        aviso.style.visibility = 'visible';
        aviso.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } else {
        alert(mensaje);
    }
    if (campoAEnfocar) {
        marcarCampoInvalido(campoAEnfocar);
        campoAEnfocar.focus();
    }
}

function ocultarAvisoFormulario(avisoId) {
    const aviso = document.getElementById(avisoId || 'form-planificacion-aviso');
    if (aviso) {
        aviso.style.visibility = 'hidden';
        aviso.textContent = '';
    }
}

function validacionesformulario(form) {
    // OJO: no usar `form.id` aquí. Como el formulario tiene un campo
    // <input name="id" id="editar-id">, el navegador crea automáticamente
    // una propiedad de acceso directo `form.id` que apunta a ESE INPUT,
    // sobrescribiendo la propiedad estándar que debería devolver el
    // atributo id del <form> como texto. form.getAttribute('id') no tiene
    // ese problema: siempre devuelve el atributo real como string.
    const esEditar = form && form.getAttribute('id') === 'form-editar-actividad';
    const avisoId = esEditar ? 'form-editar-aviso' : 'form-planificacion-aviso';

    // CORRECCIÓN: antes esta función siempre leía los IDs del formulario
    // "Nueva Planificación" (plan-tipo, plan-fecha, etc.) sin importar cuál
    // formulario se estaba enviando, porque ambos modales coexisten en el
    // DOM. Al enviar el modal de editar, planTipo (id="plan-tipo") estaba
    // vacío y la validación fallaba con "El nombre no puede quedar vacío"
    // aunque editar-nombre sí tuviera datos. Ahora se elige el set de IDs
    // según el formulario recibido.
    const ids = esEditar ? {
        nombre: 'editar-nombre',
        descripcion: 'editar-descripcion',
        fecha: 'editar-fecha',
        dia: 'editar-dia',
        hora: 'editar-hora',
        municipio: 'editar-municipio',
        parroquia: 'editar-parroquia',
        espacio: 'editar-espacio',
        biblioteca: 'editar-biblioteca',
        comuna: 'editar-comuna',
        responsable: 'editar-responsable',
        telefono: 'editar-telefono',
        objetivo: 'editar-objetivo',
        participantes: 'editar-participantes',
        nivelImpacto: 'editar-nivel-impacto',
    } : {
        nombre: 'plan-tipo',
        descripcion: 'plan-descripcion',
        fecha: 'plan-fecha',
        dia: 'plan-dia',
        hora: 'plan-hora',
        municipio: 'planificacion-municipios',
        parroquia: 'plan-parroquia',
        espacio: 'plan-espacio',
        biblioteca: 'plan-biblioteca',
        comuna: 'planificacion-comunas',
        responsable: 'plan-responsable',
        telefono: 'plan-telefono',
        objetivo: 'plan-objetivo',
        participantes: 'plan-participantes',
        nivelImpacto: 'plan-nivel-impacto',
    };

    ocultarAvisoFormulario(avisoId);
    limpiarCamposInvalidos(form);

    const planTipo = document.getElementById(ids.nombre);
    const nombre = planTipo ? planTipo.value.trim() : '';
    if (esVacio(nombre)) {
        mostrarAvisoFormulario('El nombre de la actividad no puede quedar vacío.', planTipo, avisoId);
        return false;
    }
    if (nombre.length < 2) {
        mostrarAvisoFormulario('El nombre de la actividad debe tener al menos 2 caracteres.', planTipo, avisoId);
        return false;
    }
    if (nombre.length > MAXLEN_ACTIVIDAD_NOMBRE) {
        mostrarAvisoFormulario('El nombre de la actividad no puede tener más de ' + MAXLEN_ACTIVIDAD_NOMBRE + ' caracteres.', planTipo, avisoId);
        return false;
    }
    if (!REGEX_TEXTO_VALIDO.test(nombre)) {
        mostrarAvisoFormulario('El nombre de la actividad solo puede contener letras, números, espacios y los signos \' - .', planTipo, avisoId);
        return false;
    }
    if (esRepetitivo(nombre)) {
        mostrarAvisoFormulario('El nombre de la actividad no puede ser un mismo carácter repetido ni una cadena repetida (ej. "aaa", "abab").', planTipo, avisoId);
        return false;
    }

    const planDescripcion = document.getElementById(ids.descripcion);
    const descripcion = planDescripcion ? planDescripcion.value.trim() : '';
    const tipoFormularioCompleta = document.getElementById('tipo-formulario-completa');
    const esCompleta = esEditar ? true : (tipoFormularioCompleta ? tipoFormularioCompleta.checked : false);

    if (esCompleta && esVacio(descripcion)) {
        mostrarAvisoFormulario('Escribe una descripción de la actividad.', planDescripcion, avisoId);
        return false;
    }
    if (!esVacio(descripcion) && descripcion.length > MAXLEN_DESCRIPCION) {
        mostrarAvisoFormulario('La descripción no puede tener más de ' + MAXLEN_DESCRIPCION + ' caracteres.', planDescripcion, avisoId);
        return false;
    }

    const planFecha = document.getElementById(ids.fecha);
    if (!planFecha || planFecha.value === '') {
        mostrarAvisoFormulario('Selecciona la fecha de la actividad.', planFecha, avisoId);
        return false;
    }

    const planDia = document.getElementById(ids.dia);
    if (planDia && planFecha) {
        planDia.value = calcularDiaSemana(planFecha.value);
    }

    const planHora = document.getElementById(ids.hora);
    if (!planHora || planHora.value === '') {
        mostrarAvisoFormulario('Selecciona la hora de la actividad.', planHora, avisoId);
        return false;
    }

    const planMunicipios = document.getElementById(ids.municipio);
    if (!planMunicipios || planMunicipios.value === '') {
        mostrarAvisoFormulario('Selecciona un municipio.', planMunicipios, avisoId);
        return false;
    }

    const planParroquia = document.getElementById(ids.parroquia);
    const parroquia = planParroquia ? planParroquia.value.trim() : '';
    if (esVacio(parroquia)) {
        mostrarAvisoFormulario('Selecciona o escribe una parroquia.', planParroquia, avisoId);
        return false;
    }
    if (parroquia.length > MAXLEN_PARROQUIA) {
        mostrarAvisoFormulario('La parroquia no puede tener más de ' + MAXLEN_PARROQUIA + ' caracteres.', planParroquia, avisoId);
        return false;
    }
    if (!REGEX_NOMBRE_PROPIO.test(parroquia)) {
        mostrarAvisoFormulario('La parroquia solo puede contener letras y espacios (sin números).', planParroquia, avisoId);
        return false;
    }
    if (esRepetitivo(parroquia)) {
        mostrarAvisoFormulario('La parroquia no puede ser un mismo carácter repetido ni una cadena repetida (ej. "aaa", "abab").', planParroquia, avisoId);
        return false;
    }

    const planEspacio = document.getElementById(ids.espacio);
    const planBiblioteca = document.getElementById(ids.biblioteca);
    const tipoUbicacionSeleccionado = document.querySelector('input[name="tipo_ubicacion"]:checked');
    const tipoUbicacion = tipoUbicacionSeleccionado ? tipoUbicacionSeleccionado.value : 'biblioteca';

    if (tipoUbicacion === 'espacio') {
        const espacio = planEspacio ? planEspacio.value.trim() : '';
        if (esVacio(espacio)) {
            mostrarAvisoFormulario('Selecciona un espacio cultural.', planEspacio, avisoId);
            return false;
        }
        if (planBiblioteca) {
            planBiblioteca.value = '';
        }
    } else {
        if (!planBiblioteca || planBiblioteca.value === '') {
            mostrarAvisoFormulario('Selecciona una biblioteca.', planBiblioteca, avisoId);
            return false;
        }
        if (planEspacio) {
            planEspacio.value = '';
        }
    }

    const planComunas = document.getElementById(ids.comuna);
    const comuna = planComunas ? planComunas.value.trim() : '';
    if (comuna !== '' && !REGEX_TEXTO_VALIDO.test(comuna)) {
        mostrarAvisoFormulario('La comuna seleccionada no tiene un formato válido.', planComunas, avisoId);
        return false;
    }

    const planResponsable = document.getElementById(ids.responsable);
    const responsableValor = planResponsable ? planResponsable.value.trim() : '';
    if (responsableValor !== '') {
        const responsableTexto = planResponsable && planResponsable.tagName === 'SELECT'
            ? (planResponsable.options[planResponsable.selectedIndex]?.textContent || '').trim()
            : (planResponsable ? planResponsable.value.trim() : '');

        if (responsableTexto.length < 2) {
            mostrarAvisoFormulario('El nombre del responsable debe tener al menos 2 caracteres.', planResponsable, avisoId);
            return false;
        }
        if (responsableTexto.length > MAXLEN_RESPONSABLE) {
            mostrarAvisoFormulario('El nombre del responsable no puede tener más de ' + MAXLEN_RESPONSABLE + ' caracteres.', planResponsable, avisoId);
            return false;
        }
        if (!REGEX_NOMBRE_PROPIO.test(responsableTexto)) {
            mostrarAvisoFormulario('El nombre del responsable solo puede contener letras y espacios (sin números).', planResponsable, avisoId);
            return false;
        }
        if (esRepetitivo(responsableTexto)) {
            mostrarAvisoFormulario('El nombre del responsable no puede ser un mismo carácter repetido ni una cadena repetida (ej. "aaa", "abab").', planResponsable, avisoId);
            return false;
        }
    }

    const planTelefono = document.getElementById(ids.telefono);
    const telefono = planTelefono ? planTelefono.value.trim() : '';
    if (telefono !== '' && !REGEX_TELEFONO.test(telefono)) {
        mostrarAvisoFormulario('El teléfono debe tener exactamente 11 caracteres, usando solo números, espacios, guiones (-) o el signo +. Ejemplo: 04123456789', planTelefono, avisoId);
        return false;
    }

    if (esCompleta) {
        const planObjetivo = document.getElementById(ids.objetivo);
        const objetivo = planObjetivo ? planObjetivo.value.trim() : '';
        if (esVacio(objetivo)) {
            mostrarAvisoFormulario('Escribe el objetivo o enfoque de la actividad.', planObjetivo, avisoId);
            return false;
        }
        if (objetivo.length < 2) {
            mostrarAvisoFormulario('El objetivo debe tener al menos 2 caracteres.', planObjetivo, avisoId);
            return false;
        }
        if (objetivo.length > MAXLEN_OBJETIVO) {
            mostrarAvisoFormulario('El objetivo no puede tener más de ' + MAXLEN_OBJETIVO + ' caracteres.', planObjetivo, avisoId);
            return false;
        }
        if (!REGEX_TEXTO_VALIDO.test(objetivo)) {
            mostrarAvisoFormulario('El objetivo solo puede contener letras, números, espacios y los signos \' - .', planObjetivo, avisoId);
            return false;
        }
        if (esRepetitivo(objetivo)) {
            mostrarAvisoFormulario('El objetivo no puede ser un mismo carácter repetido ni una cadena repetida (ej. "aaa", "abab").', planObjetivo, avisoId);
            return false;
        }

        const planParticipantes = document.getElementById(ids.participantes);
        const participantesStr = planParticipantes ? planParticipantes.value.trim() : '';
        if (esVacio(participantesStr)) {
            mostrarAvisoFormulario('Indica la cantidad de participantes.', planParticipantes, avisoId);
            return false;
        }
        const participantes = Number(participantesStr);
        if (!Number.isInteger(participantes) || participantes < 0 || participantes > MAX_PARTICIPANTES) {
            mostrarAvisoFormulario('La cantidad de participantes debe ser un número entero entre 0 y ' + MAX_PARTICIPANTES + '.', planParticipantes, avisoId);
            return false;
        }

        const planNivelImpacto = document.getElementById(ids.nivelImpacto);
        const nivelImpacto = planNivelImpacto ? planNivelImpacto.value.trim() : '';
        if (esVacio(nivelImpacto)) {
            mostrarAvisoFormulario('Indica el nivel de impacto de la actividad.', planNivelImpacto, avisoId);
            return false;
        }
        if (nivelImpacto.length > MAXLEN_NIVEL_IMPACTO) {
            mostrarAvisoFormulario('El nivel de impacto no puede tener más de ' + MAXLEN_NIVEL_IMPACTO + ' caracteres.', planNivelImpacto, avisoId);
            return false;
        }
        if (!REGEX_NOMBRE_PROPIO.test(nivelImpacto)) {
            mostrarAvisoFormulario('El nivel de impacto solo puede contener letras y espacios (sin números).', planNivelImpacto, avisoId);
            return false;
        }
        if (esRepetitivo(nivelImpacto)) {
            mostrarAvisoFormulario('El nivel de impacto no puede ser un mismo carácter repetido ni una cadena repetida (ej. "aaa", "abab").', planNivelImpacto, avisoId);
            return false;
        }
    }

    return true;
}
document.addEventListener("DOMContentLoaded", () => {
    const selectMunicipios = document.getElementById("planificacion-municipios");

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

    const planTipo = document.getElementById('plan-tipo');
    const fieldsHidden = document.getElementById('fields-hidden');

    const toggleFields = () => {
        if (!fieldsHidden || !planTipo) return;
        const show = planTipo.value.trim().length > 0;
        fieldsHidden.classList.toggle('hidden', !show);
    };

    if (planTipo) {
        planTipo.addEventListener('input', toggleFields);
        toggleFields();
    }

    const planMunicipio = document.getElementById('planificacion-municipios');
    const municipioHidden = document.getElementById('municipio-hidden');
    const planFechaInput = document.getElementById('plan-fecha');
    const planDiaInput = document.getElementById('plan-dia');
    const editarFecha = document.getElementById('editar-fecha');
    const editarDia = document.getElementById('editar-dia');

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

    if (editarFecha && editarDia) {
        if (editarFecha.value) {
            editarDia.value = calcularDiaSemana(editarFecha.value);
        }
        editarFecha.addEventListener('change', () => {
            editarDia.value = calcularDiaSemana(editarFecha.value);
        });
    }

    function resetFormularioPlanificacion() {
        const form = document.getElementById('form-planificacion');
        const titulo = document.getElementById('planModalTitulo');
        const planAction = document.getElementById('plan-action');
        const actividadId = document.getElementById('actividad-id');
        const solicitudesSelector = document.getElementById('solicitudesSelector');

        if (form) form.reset();
        if (titulo) titulo.textContent = 'Nueva Planificación de Actividad';
        if (planAction) planAction.value = 'crear';
        if (actividadId) actividadId.value = '';
        if (solicitudesSelector) solicitudesSelector.hidden = true;

        limpiarCamposInvalidos(form);
        ocultarAvisoFormulario();

        toggleFields();
        toggleMunicipio();
        toggleTipoFormulario();
        toggleTipoUbicacion();
    }

    const modalEditar = document.getElementById('modalEditarActividad');
    const btnCerrarEditar = document.querySelectorAll('.close-button-editar');
    const btnCargarSolicitud = document.getElementById('btnCargarSolicitud');
    const solicitudesSelector = document.getElementById('solicitudesSelector');
    let solicitudesCache = [];

    async function cargarSolicitudesDeServidor() {
        try {
            const response = await fetch('../controladores/solicitud_contr.php?action=listar');
            if (!response.ok) throw new Error('Error cargando solicitudes');
            const data = await response.json();
            if (data.success) {
                solicitudesCache = data.data || [];
            } else {
                solicitudesCache = [];
            }
        } catch (error) {
            console.error(error);
            solicitudesCache = [];
        }
    }

    function renderSolicitudesSelector() {
        if (!solicitudesSelector) return;
        if (!solicitudesCache.length) {
            solicitudesSelector.innerHTML = '<div class="solicitudes-selector__empty">No hay solicitudes disponibles.</div>';
            return;
        }
        solicitudesSelector.innerHTML = solicitudesCache.map(solicitud => {
            const nombre = solicitud.descripcion ? solicitud.descripcion : solicitud.lugar;
            const subtitle = solicitud.responsable ? `${solicitud.responsable} · ${solicitud.fecha_solicitud} ${solicitud.hora_solicitud}` : `${solicitud.fecha_solicitud} ${solicitud.hora_solicitud}`;
            return `
                <button type="button" class="solicitudes-selector__item" data-id="${solicitud.id}">
                    <strong>${escapeHtml(nombre)}</strong>
                    <span>${escapeHtml(subtitle)}</span>
                </button>
            `;
        }).join('');

        solicitudesSelector.querySelectorAll('.solicitudes-selector__item').forEach(button => {
            button.addEventListener('click', () => {
                const solicitudId = button.dataset.id;
                const solicitud = solicitudesCache.find(item => String(item.id) === String(solicitudId));
                if (solicitud) {
                    aplicarSolicitudAlFormulario(solicitud);
                }
            });
        });
    }

    function aplicarSolicitudAlFormulario(solicitud) {
        const planTipo = document.getElementById('plan-tipo');
        const planDescripcion = document.getElementById('plan-descripcion');
        const planFecha = document.getElementById('plan-fecha');
        const planHora = document.getElementById('plan-hora');
        const planParticipantes = document.getElementById('plan-participantes');
        const planObjetivo = document.getElementById('plan-objetivo');
        const planBiblioteca = document.getElementById('plan-biblioteca');
        const planResponsable = document.getElementById('plan-responsable');
        const planTelefono = document.getElementById('plan-telefono');

        if (planTipo) {
            planTipo.value = solicitud.lugar || solicitud.descripcion || '';
        }
        if (planDescripcion) {
            planDescripcion.value = solicitud.descripcion || '';
        }
        if (planFecha) {
            planFecha.value = solicitud.fecha_solicitud || '';
        }
        if (planHora) {
            planHora.value = solicitud.hora_solicitud || '';
        }
        if (planParticipantes) {
            planParticipantes.value = solicitud.participantes || '';
        }
        if (planObjetivo) {
            planObjetivo.value = solicitud.responsable || '';
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

        if (planResponsable) {
            const nombreResponsable = (solicitud.responsable || '').trim();
            const opciones = Array.from(planResponsable.options);
            const encontrado = opciones.find(opt =>
                opt.textContent.trim().toLowerCase() === nombreResponsable.toLowerCase()
            ) || opciones.find(opt => opt.value.trim().toLowerCase() === nombreResponsable.toLowerCase());

            if (encontrado) {
                planResponsable.value = encontrado.value;
                planResponsable.dispatchEvent(new Event('change'));
            } else if (planTelefono) {
                planTelefono.value = '';
            }
        }

        if (solicitudesSelector) {
            solicitudesSelector.hidden = true;
        }
    }

    function toggleSolicitudesSelector() {
        if (!solicitudesSelector || !btnCargarSolicitud) return;
        if (solicitudesSelector.hidden) {
            if (!solicitudesCache.length) {
                cargarSolicitudesDeServidor().then(renderSolicitudesSelector);
            } else {
                renderSolicitudesSelector();
            }
            solicitudesSelector.hidden = false;
        } else {
            solicitudesSelector.hidden = true;
        }
    }

    function ocultarSolicitudesSelector() {
        if (solicitudesSelector) solicitudesSelector.hidden = true;
    }

    if (btnCargarSolicitud) {
        btnCargarSolicitud.addEventListener('click', (e) => {
            e.preventDefault();
            toggleSolicitudesSelector();
        });
    }

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
        setValue('editar-municipio', btn.dataset.municipioId);
        setValue('editar-parroquia', btn.dataset.parroquia);
        setValue('editar-comuna', btn.dataset.comuna);
        setValue('editar-espacio', btn.dataset.espacio);
        setValue('editar-biblioteca', btn.dataset.idBiblioteca);
        setValue('editar-responsable', btn.dataset.responsable);
        setValue('editar-telefono', btn.dataset.telefono);

        openModalEditar();
    }

    document.querySelectorAll('.btn-edit-actividad').forEach((btn) => {
        btn.addEventListener('click', () => abrirEditarActividad(btn));
    });

    // jesus: toggle de la fila de detalle ("Ver más" / "Ver menos") en la tabla de actividades
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

    const userMenuButton = document.getElementById('userMenuButton');
    const userMenu = document.getElementById('userMenu');
    const logoutBtn = document.getElementById('logoutBtn');
    const configBtn = document.getElementById('configBtn');

    if (userMenuButton && userMenu) {
        userMenuButton.addEventListener('click', (ev) => {
            ev.stopPropagation();
            const expanded = userMenuButton.getAttribute('aria-expanded') === 'true';
            userMenuButton.setAttribute('aria-expanded', String(!expanded));
            if (userMenu.hasAttribute('hidden')) userMenu.removeAttribute('hidden');
            else userMenu.setAttribute('hidden', '');
        });

        document.addEventListener('click', (ev) => {
            if (!userMenu.contains(ev.target) && ev.target !== userMenuButton) {
                userMenu.setAttribute('hidden', '');
                userMenuButton.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', (ev) => {
            if (ev.key === 'Escape') {
                userMenu.setAttribute('hidden', '');
                userMenuButton.setAttribute('aria-expanded', 'false');
            }
        });
    }

    if (logoutBtn) {
        logoutBtn.addEventListener('click', (ev) => {
            ev.preventDefault();
            if (userMenu) userMenu.setAttribute('hidden', '');
            if (userMenuButton) userMenuButton.setAttribute('aria-expanded', 'false');
            window.location.href = 'login.php';
        });
    }

    if (configBtn) {
        configBtn.addEventListener('click', (ev) => {
        });
    }

    const loginForm = document.querySelector('form[name="login"]');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');

    if (loginForm && usernameInput && passwordInput) {
        loginForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const username = usernameInput.value.trim();
            const password = passwordInput.value;

            if (username === '' || password === '') {
                alert('Debes ingresar usuario y contraseña.');
                usernameInput.focus();
                return;
            }

            const formData = new FormData();
            formData.append('username', username);
            formData.append('password', password);

            const submitBtn = loginForm.querySelector('button[type="submit"], input[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            try {
                const response = await fetch('auth.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Respuesta no válida del servidor (' + response.status + ').');
                }

                const data = await response.json();

                if (data.success) {
                    window.location.href = data.redirect || 'main2.php';
                } else {
                    alert(data.message || 'Usuario o contraseña incorrectos.');
                    passwordInput.value = '';
                    usernameInput.focus();
                }
            } catch (error) {
                console.error('Error al iniciar sesión:', error);
                alert('No se pudo conectar con el servidor. Inténtalo de nuevo.');
            } finally {
                if (submitBtn) submitBtn.disabled = false;
            }
        });
    }
});
