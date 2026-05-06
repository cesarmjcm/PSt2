function validacionesformulario(form) {
    const planTipo = document.getElementById('plan-tipo');
    if (!planTipo || planTipo.value === '') {
         planTipo.focus();
        alert('Ingresa el tipo de actividad.');
        return false;
    }

    const planDia = document.getElementById('plan-dia');
    if (!planDia || planDia.value === '') {
         planDia.focus();
        alert('Selecciona el día de la actividad.');
        return false;
    }

    const planFecha = document.getElementById('plan-fecha');
    if (!planFecha || planFecha.value === '') {
        planFecha.focus();
        alert('Selecciona la fecha de la actividad.');
        return false;
    }

    const planHora = document.getElementById('plan-hora');
    if (!planHora || planHora.value === '') {
      planHora.focus();
        alert('Selecciona la hora de la actividad.');
        return false;
    }

    const planDescripcion = document.getElementById('plan-descripcion');
    if (!planDescripcion || planDescripcion.value === '') {
         planDescripcion.focus();
        alert('Escribe una descripción de la actividad.');
        return false;
    }

    const planObjetivo = document.getElementById('plan-objetivo');
    if (!planObjetivo || planObjetivo.value === '') {
         planObjetivo.focus();
        alert('Ingresa el objetivo o enfoque.');
        return false;
    }

    const nivelImpacto = document.getElementById('nivel-impacto');
    if (!nivelImpacto || nivelImpacto.value === '') {
        nivelImpacto.focus();
        alert('Selecciona el nivel de impacto.');
        return false;
    }

    const planParticipantes = document.getElementById('plan-participantes');
    if (!planParticipantes || planParticipantes.value === '') {
        planParticipantes.value = 0;
        
    }

    const planMunicipios = document.getElementById('planificacion-municipios');
    if (!planMunicipios || planMunicipios.value === '') {
     planMunicipios.focus();
        alert('Selecciona un municipio.');
        return false;
    }

    const planParroquia = document.getElementById('plan-parroquia');
    if (!planParroquia || planParroquia.value === '') {
        planParroquia.focus();
        alert('Selecciona o escribe una parroquia.');
        return false;
    }

    const planEspacio = document.getElementById('plan-espacio');
    if (!planEspacio || planEspacio.value === '') {
         planEspacio.focus();
        alert('Ingresa el espacio cultural.');
        return false;
    }

    const planComunas = document.getElementById('planificacion-comunas');
    if (!planComunas || planComunas.value === '') {
            planComunas.focus();
        alert('Selecciona una comuna.');
        return false;
    }

   

    return true;
}
