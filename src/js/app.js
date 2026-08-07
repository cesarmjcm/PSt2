
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

function mostrarAvisoFormulario(mensaje, campoAEnfocar) {
    const aviso = document.getElementById('form-planificacion-aviso');
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

function ocultarAvisoFormulario() {
    const aviso = document.getElementById('form-planificacion-aviso');
    if (aviso) {
        aviso.style.visibility = 'hidden';
        aviso.textContent = '';
    }
}

function validacionesformulario(form) {
    ocultarAvisoFormulario();
    limpiarCamposInvalidos(form);

    const planTipo = document.getElementById('plan-tipo');
    const nombre = planTipo ? planTipo.value.trim() : '';
    if (esVacio(nombre)) {
        mostrarAvisoFormulario('El nombre de la actividad no puede quedar vacío.', planTipo);
        return false;
    }
    if (nombre.length < 2) {
        mostrarAvisoFormulario('El nombre de la actividad debe tener al menos 2 caracteres.', planTipo);
        return false;
    }
    if (nombre.length > MAXLEN_ACTIVIDAD_NOMBRE) {
        mostrarAvisoFormulario('El nombre de la actividad no puede tener más de ' + MAXLEN_ACTIVIDAD_NOMBRE + ' caracteres.', planTipo);
        return false;
    }
    if (!REGEX_TEXTO_VALIDO.test(nombre)) {
        mostrarAvisoFormulario('El nombre de la actividad solo puede contener letras, números, espacios y los signos \' - .', planTipo);
        return false;
    }
    if (esRepetitivo(nombre)) {
        mostrarAvisoFormulario('El nombre de la actividad no puede ser un mismo carácter repetido ni una cadena repetida (ej. "aaa", "abab").', planTipo);
        return false;
    }

    const planDescripcion = document.getElementById('plan-descripcion');
    const descripcion = planDescripcion ? planDescripcion.value.trim() : '';
    if (esVacio(descripcion)) {
        mostrarAvisoFormulario('Escribe una descripción de la actividad.', planDescripcion);
        return false;
    }
    if (descripcion.length > MAXLEN_DESCRIPCION) {
        mostrarAvisoFormulario('La descripción no puede tener más de ' + MAXLEN_DESCRIPCION + ' caracteres.', planDescripcion);
        return false;
    }

    const planFecha = document.getElementById('plan-fecha');
    if (!planFecha || planFecha.value === '') {
        mostrarAvisoFormulario('Selecciona la fecha de la actividad.', planFecha);
        return false;
    }

    const planDia = document.getElementById('plan-dia');
    if (planDia && planFecha) {
        planDia.value = calcularDiaSemana(planFecha.value);
    }

    const planHora = document.getElementById('plan-hora');
    if (!planHora || planHora.value === '') {
        mostrarAvisoFormulario('Selecciona la hora de la actividad.', planHora);
        return false;
    }

    const planMunicipios = document.getElementById('planificacion-municipios');
    if (!planMunicipios || planMunicipios.value === '') {
        mostrarAvisoFormulario('Selecciona un municipio.', planMunicipios);
        return false;
    }

    const planParroquia = document.getElementById('plan-parroquia');
    const parroquia = planParroquia ? planParroquia.value.trim() : '';
    if (esVacio(parroquia)) {
        mostrarAvisoFormulario('Selecciona o escribe una parroquia.', planParroquia);
        return false;
    }
    if (parroquia.length > MAXLEN_PARROQUIA) {
        mostrarAvisoFormulario('La parroquia no puede tener más de ' + MAXLEN_PARROQUIA + ' caracteres.', planParroquia);
        return false;
    }
    if (!REGEX_NOMBRE_PROPIO.test(parroquia)) {
        mostrarAvisoFormulario('La parroquia solo puede contener letras y espacios (sin números).', planParroquia);
        return false;
    }
    if (esRepetitivo(parroquia)) {
        mostrarAvisoFormulario('La parroquia no puede ser un mismo carácter repetido ni una cadena repetida (ej. "aaa", "abab").', planParroquia);
        return false;
    }

    const planEspacio = document.getElementById('plan-espacio');
    const espacio = planEspacio ? planEspacio.value.trim() : '';
    if (esVacio(espacio)) {
        mostrarAvisoFormulario('Ingresa un espacio cultural.', planEspacio);
        return false;
    }
    if (espacio.length > MAXLEN_ESPACIO) {
        mostrarAvisoFormulario('El espacio cultural no puede tener más de ' + MAXLEN_ESPACIO + ' caracteres.', planEspacio);
        return false;
    }
    if (!REGEX_TEXTO_VALIDO.test(espacio)) {
        mostrarAvisoFormulario('El espacio cultural solo puede contener letras, números, espacios y los signos \' - .', planEspacio);
        return false;
    }
    if (esRepetitivo(espacio)) {
        mostrarAvisoFormulario('El espacio cultural no puede ser un mismo carácter repetido ni una cadena repetida (ej. "aaa", "abab").', planEspacio);
        return false;
    }

    const planBiblioteca = document.getElementById('plan-biblioteca');
    if (!planBiblioteca || planBiblioteca.value === '') {
        mostrarAvisoFormulario('Selecciona una biblioteca.', planBiblioteca);
        return false;
    }

    const planComunas = document.getElementById('planificacion-comunas');
    const comuna = planComunas ? planComunas.value.trim() : '';
    if (comuna !== '' && !REGEX_TEXTO_VALIDO.test(comuna)) {
        mostrarAvisoFormulario('La comuna seleccionada no tiene un formato válido.', planComunas);
        return false;
    }

    const planResponsable = document.getElementById('plan-responsable');
    const responsable = planResponsable ? planResponsable.value.trim() : '';
    if (responsable !== '') {
        if (responsable.length < 2) {
            mostrarAvisoFormulario('El nombre del responsable debe tener al menos 2 caracteres.', planResponsable);
            return false;
        }
        if (responsable.length > MAXLEN_RESPONSABLE) {
            mostrarAvisoFormulario('El nombre del responsable no puede tener más de ' + MAXLEN_RESPONSABLE + ' caracteres.', planResponsable);
            return false;
        }
        if (!REGEX_NOMBRE_PROPIO.test(responsable)) {
            mostrarAvisoFormulario('El nombre del responsable solo puede contener letras y espacios (sin números).', planResponsable);
            return false;
        }
        if (esRepetitivo(responsable)) {
            mostrarAvisoFormulario('El nombre del responsable no puede ser un mismo carácter repetido ni una cadena repetida (ej. "aaa", "abab").', planResponsable);
            return false;
        }
    }

    const planTelefono = document.getElementById('plan-telefono');
    const telefono = planTelefono ? planTelefono.value.trim() : '';
    if (telefono !== '' && !REGEX_TELEFONO.test(telefono)) {
        mostrarAvisoFormulario('El teléfono debe tener exactamente 11 caracteres, usando solo números, espacios, guiones (-) o el signo +. Ejemplo: 04123456789', planTelefono);
        return false;
    }

    const tipoFormularioCompleta = document.getElementById('tipo-formulario-completa');
    const esCompleta = tipoFormularioCompleta ? tipoFormularioCompleta.checked : false;

    if (esCompleta) {
        const planObjetivo = document.getElementById('plan-objetivo');
        const objetivo = planObjetivo ? planObjetivo.value.trim() : '';
        if (esVacio(objetivo)) {
            mostrarAvisoFormulario('Escribe el objetivo o enfoque de la actividad.', planObjetivo);
            return false;
        }
        if (objetivo.length < 2) {
            mostrarAvisoFormulario('El objetivo debe tener al menos 2 caracteres.', planObjetivo);
            return false;
        }
        if (objetivo.length > MAXLEN_OBJETIVO) {
            mostrarAvisoFormulario('El objetivo no puede tener más de ' + MAXLEN_OBJETIVO + ' caracteres.', planObjetivo);
            return false;
        }
        if (!REGEX_TEXTO_VALIDO.test(objetivo)) {
            mostrarAvisoFormulario('El objetivo solo puede contener letras, números, espacios y los signos \' - .', planObjetivo);
            return false;
        }
        if (esRepetitivo(objetivo)) {
            mostrarAvisoFormulario('El objetivo no puede ser un mismo carácter repetido ni una cadena repetida (ej. "aaa", "abab").', planObjetivo);
            return false;
        }

        const planParticipantes = document.getElementById('plan-participantes');
        const participantesStr = planParticipantes ? planParticipantes.value.trim() : '';
        if (esVacio(participantesStr)) {
            mostrarAvisoFormulario('Indica la cantidad de participantes.', planParticipantes);
            return false;
        }
        const participantes = Number(participantesStr);
        if (!Number.isInteger(participantes) || participantes < 0 || participantes > MAX_PARTICIPANTES) {
            mostrarAvisoFormulario('La cantidad de participantes debe ser un número entero entre 0 y ' + MAX_PARTICIPANTES + '.', planParticipantes);
            return false;
        }

        const planNivelImpacto = document.getElementById('plan-nivel-impacto');
        const nivelImpacto = planNivelImpacto ? planNivelImpacto.value.trim() : '';
        if (esVacio(nivelImpacto)) {
            mostrarAvisoFormulario('Indica el nivel de impacto de la actividad.', planNivelImpacto);
            return false;
        }
        if (nivelImpacto.length > MAXLEN_NIVEL_IMPACTO) {
            mostrarAvisoFormulario('El nivel de impacto no puede tener más de ' + MAXLEN_NIVEL_IMPACTO + ' caracteres.', planNivelImpacto);
            return false;
        }
        if (!REGEX_NOMBRE_PROPIO.test(nivelImpacto)) {
            mostrarAvisoFormulario('El nivel de impacto solo puede contener letras y espacios (sin números).', planNivelImpacto);
            return false;
        }
        if (esRepetitivo(nivelImpacto)) {
            mostrarAvisoFormulario('El nivel de impacto no puede ser un mismo carácter repetido ni una cadena repetida (ej. "aaa", "abab").', planNivelImpacto);
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

    const toggleTipoFormulario = () => {
        if (!camposCompleta) return;
        const completaRadio = document.getElementById('tipo-formulario-completa');
        const show = completaRadio ? completaRadio.checked : false;
        camposCompleta.classList.toggle('hidden', !show);
    };

    if (tipoFormularioRadios.length > 0) {
        tipoFormularioRadios.forEach((radio) => radio.addEventListener('change', toggleTipoFormulario));
        toggleTipoFormulario();
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

        if (form) form.reset();
        if (titulo) titulo.textContent = 'Nueva Planificación de Actividad';
        if (planAction) planAction.value = 'crear';
        if (actividadId) actividadId.value = '';

        limpiarCamposInvalidos(form);
        ocultarAvisoFormulario();

        toggleFields();
        toggleMunicipio();
        toggleTipoFormulario();
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
        setValue('editar-dia', btn.dataset.dia);

        setValue('editar-nivel-impacto', btn.dataset.nivelImpacto);
        setValue('editar-municipio-id', btn.dataset.municipioId);
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

            if (username !== 'admin' || password !== '123456') {
                event.preventDefault();
                alert('Usuario o contraseña incorrectos. Usa admin / 123456.');
                usernameInput.focus();
            }
            // Si pasa, se permite el envío del formulario
        });
    }
});
