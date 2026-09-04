
let actividadesDesdeBD = []; 


let fechaActual = new Date();
let mesActual = fechaActual.getMonth(); 
let anioActual = fechaActual.getFullYear();

const meses = [
    "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
];

document.addEventListener("DOMContentLoaded", () => {
    
    cargarActividadesDesdeBD();

    
    const btnToggle = document.getElementById("btn-toggle-calendario");
    const calendario = document.querySelector(".calendar-container"); 

    
    calendario.classList.add("mostrar");
    btnToggle.innerText = "Cerrar Calendario";

    btnToggle.addEventListener("click", () => {
        calendario.classList.toggle("mostrar");
        
        if (calendario.classList.contains("mostrar")) {
            btnToggle.innerText = "Cerrar Calendario";
        } else {
            btnToggle.innerText = "Ver Calendario de Actividades";
        }
    });

    
    document.getElementById("prev-month").addEventListener("click", () => cambiarMes(-1));
    document.getElementById("next-month").addEventListener("click", () => cambiarMes(1));

    
    const closeBtn = document.querySelector(".close-btn");
    if (closeBtn) {
        closeBtn.addEventListener("click", cerrarModal);
    }
    
    window.addEventListener("click", (evento) => {
        const modal = document.getElementById("activity-modal");
        if (evento.target === modal) {
            cerrarModal();
        }
    });
});


function cargarActividadesDesdeBD() {
    fetch('main2.php?accion=obtener_actividades')
        .then(respuesta => {
            if (!respuesta.ok) throw new Error("Error en la respuesta del servidor");
            return respuesta.json();
        })
        .then(datos => {
            actividadesDesdeBD = datos; 
            renderizarCalendario(mesActual, anioActual); 
        })
        .catch(error => {
            console.error("No se pudieron cargar las actividades:", error);
            renderizarCalendario(mesActual, anioActual); 
        });
}


function renderizarCalendario(mes, anio) {
    const contenedorDias = document.getElementById("days-grid");
    const tituloMesAnio = document.getElementById("month-year-title");
    
    contenedorDias.innerHTML = ""; 
    tituloMesAnio.innerText = `${meses[mes]} ${anio}`;

    const primerDiaIndex = new Date(anio, mes, 1).getDay();
    const totalDiasMes = new Date(anio, mes + 1, 0).getDate();

    
    for (let i = 0; i < primerDiaIndex; i++) {
        const celdaVacia = document.createElement("div");
        contenedorDias.appendChild(celdaVacia);
    }

    
    for (let dia = 1; dia <= totalDiasMes; dia++) {
        const celdaDia = document.createElement("div");
        celdaDia.innerText = dia;

        const mesFormateado = String(mes + 1).padStart(2, '0');
        const diaFormateado = String(dia).padStart(2, '0');
        const fechaString = `${anio}-${mesFormateado}-${diaFormateado}`;

        const actividadesDelDia = actividadesDesdeBD.filter(act => act.fecha === fechaString);
        if (actividadesDelDia.length > 0) {
            celdaDia.classList.add("has-activity"); 
        }
        celdaDia.addEventListener("click", () => {
            abrirModal(actividadesDelDia, fechaString);
        });

        contenedorDias.appendChild(celdaDia);
    }
}

function cambiarMes(direccion) {
    mesActual += direccion;

    if (mesActual < 0) {
        mesActual = 11;
        anioActual--;
    } else if (mesActual > 11) {
        mesActual = 0;
        anioActual++;
    }
    renderizarCalendario(mesActual, anioActual);
}


function abrirModal(actividades, fecha) {
    const modal = document.getElementById("activity-modal");
    const modalBody = document.getElementById("activity-modal-body");
    const modalTitle = document.getElementById("activity-modal-title");
    if (!modal || !modalBody || !modalTitle) return;

    modalTitle.innerText = `Actividades del día ${fecha}`;
    if (!actividades.length) {
        modalBody.innerHTML = '<p>No hay actividades registradas para este día.</p>';
    } else {
        const filas = actividades.map((act, index) => `
            <tr>
                <td>${index + 1}</td>
                <td>${act.nombre || ''}</td>
                <td>${act.descripcion || ''}</td>
                <td>${act.dia_semana || ''}</td>
                <td>${act.fecha || ''}</td>
            </tr>
        `).join('');

        modalBody.innerHTML = `
            <div class="activity-modal-table-wrapper">
                <table class="activity-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Actividad</th>
                            <th>Descripción</th>
                            <th>Día</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>${filas}</tbody>
                </table>
            </div>
        `;
    }

    modal.style.display = "flex";
}

function cerrarModal() {
    const modal = document.getElementById("activity-modal");
    if (modal) {
        modal.style.display = "none";
    }
}