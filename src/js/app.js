function validacionesformulario(form) {
    const planTipo = document.getElementById('plan-tipo');
    if (!planTipo || planTipo.value === '') {
        planTipo.focus();
        alert('Ingresa el tipo de actividad.');
        return false;
    }
    const planDescripcion = document.getElementById('plan-descripcion');
    if (!planDescripcion || planDescripcion.value === '') {
        planDescripcion.focus();
        alert('Escribe una descripción de la actividad.');
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
    const planObjetivo = document.getElementById('plan-objetivo');
    if (!planObjetivo || planObjetivo.value === '') {
         planObjetivo.focus();
        alert('Ingresa el objetivo o enfoque.');
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
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById("modalPlanificacion");
    const btnNuevaActividad = document.querySelector(".btn-primary"); 
    const btnCerrar = document.querySelector(".close-button");

    // Abrir al hacer clic en "Nueva Actividad"
    btnNuevaActividad.addEventListener("click", (e) => {
        e.preventDefault();
        modal.style.display = "block";
        document.body.style.overflow = "hidden"; 
    });


    btnCerrar.addEventListener("click", () => {
        modal.style.display = "none";
        document.body.style.overflow = "auto";
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

    const toggleMunicipio = () => {
        if (!municipioHidden || !planMunicipio) return;
        const show = planMunicipio.value.trim().length > 0;
        municipioHidden.classList.toggle('hidden', !show);
    };

    if (planMunicipio) {
        planMunicipio.addEventListener('change', toggleMunicipio);
        toggleMunicipio();
    }

    // Menú de usuario: abrir/cerrar y manejadores
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

        // Cerrar al hacer clic fuera
        document.addEventListener('click', (ev) => {
            if (!userMenu.contains(ev.target) && ev.target !== userMenuButton) {
                userMenu.setAttribute('hidden', '');
                userMenuButton.setAttribute('aria-expanded', 'false');
            }
        });

        // Cerrar con Escape
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
            // Redirigir a la página de login
            if (userMenu) userMenu.setAttribute('hidden', '');
            if (userMenuButton) userMenuButton.setAttribute('aria-expanded', 'false');
            window.location.href = 'login.html';
        });
    }

    if (configBtn) {
        configBtn.addEventListener('click', (ev) => {
            ev.preventDefault();
            alert('Abrir configuración...');
            // Abrir modal o navegar a la página de configuración
        });
    }
});