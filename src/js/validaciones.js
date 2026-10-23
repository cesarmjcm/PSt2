// Validación y envío de los formularios de actividad
// (modal "Nueva Planificación" en main2.php y modal "Editar actividad" en index.php).
// Requiere utilidades.js (calcularDiaSemana).
// Los formularios la invocan con: onsubmit="return validacionesformulario(this)"

const REGEX_TEXTO_VALIDO = /^[A-Za-zÁÉÍÓÚáéíóúÑñÜü0-9 '\-.]+$/u;
const REGEX_NOMBRE_PROPIO = /^[A-Za-zÁÉÍÓÚáéíóúÑñÜü '\-]+$/u;
const REGEX_TELEFONO = /^[0-9\-\+ ]{7,15}$/;

const MAXLEN_ACTIVIDAD_NOMBRE = 30;
const MAXLEN_DESCRIPCION = 200;
const MAXLEN_PARROQUIA = 30;
const MAXLEN_ESPACIO = 30;
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

// Envía el formulario de actividad por AJAX para poder leer el JSON de
// error que ya genera ActividadController::error() (por ejemplo, el
// conflicto de "misma actividad, mismo lugar, misma fecha/hora"). Un
// <form> nativo no manda el header X-Requested-With, así que el backend
// respondía con un redirect silencioso y el mensaje se perdía.
async function enviarFormularioActividad(form, avisoId) {
    // OJO: no usar `form.action` aquí. Igual que con `form.id` (ver el
    // comentario en validacionesformulario), el formulario tiene un campo
    // <input name="action" id="plan-action">, y el navegador sobrescribe
    // la propiedad `form.action` para que apunte a ESE INPUT en lugar de
    // devolver la URL real del atributo action del <form>. Eso hacía que
    // fetch() recibiera el elemento del input (convertido a texto como
    // "[object HTMLInputElement]") en vez de la URL, y el servidor
    // respondía 404 con HTML, no JSON. form.getAttribute('action') no
    // tiene ese problema.
    const actionUrl = form.getAttribute('action');
    const formData = new FormData(form);
    if (!formData.has('return_url')) {
        formData.append('return_url', window.location.pathname + window.location.search);
    }

    try {
        const response = await fetch(actionUrl, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData,
        });

        let data;
        try {
            data = await response.json();
        } catch (parseErr) {
            mostrarAvisoFormulario('No se pudo procesar la respuesta del servidor. Intenta nuevamente.', null, avisoId);
            return;
        }

        if (data && data.success) {
            window.location.reload();
            return;
        }

        mostrarAvisoFormulario(
            (data && data.message) || 'No se pudo guardar la actividad. Intenta nuevamente.',
            null,
            avisoId
        );
    } catch (networkErr) {
        mostrarAvisoFormulario('Error de conexión al guardar la actividad. Intenta nuevamente.', null, avisoId);
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
        tipoActividad: 'editar-tipo-actividad',
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
        tipoActividad: 'plan-tipo-actividad',
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

    const planTipoActividad = document.getElementById(ids.tipoActividad);
    if (!planTipoActividad || planTipoActividad.value === '') {
        mostrarAvisoFormulario('Selecciona el tipo de actividad.', planTipoActividad, avisoId);
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
    // CORRECCIÓN: antes se buscaba en todo el documento, así que si el
    // OTRO formulario (Nueva Planificación / Editar) tenía un radio
    // marcado, esta validación podía leer el tipo_ubicacion equivocado.
    // Se acota la búsqueda al form que se está validando.
    const tipoUbicacionSeleccionado = form.querySelector('input[name="tipo_ubicacion"]:checked');
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

    // Al seleccionar un empleado se guarda su identificador en la actividad.
    const planResponsable = document.getElementById(ids.responsable);
    const responsableValor = planResponsable ? planResponsable.value.trim() : '';
    if (responsableValor !== '' && responsableValor !== '__actual__' && !/^[1-9][0-9]*$/.test(responsableValor)) {
        mostrarAvisoFormulario('Selecciona un responsable válido de la lista.', planResponsable, avisoId);
        return false;
    }

    const planTelefono = document.getElementById(ids.telefono);
    const telefono = planTelefono ? planTelefono.value.trim() : '';
    if (telefono !== '' && !REGEX_TELEFONO.test(telefono)) {
        mostrarAvisoFormulario('El teléfono debe tener entre 7 y 15 caracteres, usando solo números, espacios, guiones (-) o el signo +.', planTelefono, avisoId);
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

    // Las validaciones de campo pasaron. El chequeo de conflicto de
    // horario (misma actividad, mismo lugar, misma fecha/hora) solo se
    // puede hacer en el servidor, así que el envío se hace por AJAX para
    // poder mostrar ese mensaje de error en el propio modal en vez de
    // dejar que el navegador navegue a otra página.
    enviarFormularioActividad(form, avisoId);
    return false;
}
