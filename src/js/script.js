
let actividadesDesdeBD = []; 

// Controla el estado de la fecha actual del calendario
let fechaActual = new Date();
let mesActual = fechaActual.getMonth(); 
let anioActual = fechaActual.getFullYear();

const meses = [
    "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
];

document.addEventListener("DOMContentLoaded", () => {
    //  Deberia cargar los datos de la base de datos
    cargarActividadesDesdeBD();

    //  oculta y muestra el calendario
    const btnToggle = document.getElementById("btn-toggle-calendario");
    const calendario = document.querySelector(".calendar-container"); 

    // Mostrar el calendario visible desde que carga la página
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

    // 3. Escuchadores de eventos para los botones de navegacion
    document.getElementById("prev-month").addEventListener("click", () => cambiarMes(-1));
    document.getElementById("next-month").addEventListener("click", () => cambiarMes(1));

    // 4. Eventos para cerrar el Modal de manera segura
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

// Funcion para conectar con el Backend PHP
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

// Funcion que dibuja la cuadricula de dias 
function renderizarCalendario(mes, anio) {
    const contenedorDias = document.getElementById("days-grid");
    const tituloMesAnio = document.getElementById("month-year-title");
    
    contenedorDias.innerHTML = ""; 
    tituloMesAnio.innerText = `${meses[mes]} ${anio}`;

    const primerDiaIndex = new Date(anio, mes, 1).getDay();
    const totalDiasMes = new Date(anio, mes + 1, 0).getDate();

    // esta broma crea las celdas de los dias vacias
    for (let i = 0; i < primerDiaIndex; i++) {
        const celdaVacia = document.createElement("div");
        contenedorDias.appendChild(celdaVacia);
    }

    // crea los dias del mes
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

// FUNCIONES DEL MODAL, se añadio esta broma porque daba error
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