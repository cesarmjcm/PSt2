<div class="btn-container">
    <button id="btn-toggle-calendario" class="main-btn"> Ver Calendario de Actividades</button>
</div>



    <div class="calendar-container">
        <div class="calendar-header">
            <button id="prev-month" class="nav-btn">&#10094;</button>
            <h2 id="month-year-title">Cargando mes...</h2>
            <button id="next-month" class="nav-btn">&#10095;</button>
        </div>

        <div class="weekdays-grid">
            <div>Dom</div><div>Lun</div><div>Mar</div><div>Mie</div><div>Jue</div><div>Vie</div><div>Sáb</div>
        </div>

        <div id="days-grid" class="days-grid"></div>
    </div>

    <div id="activity-modal" class="modal">
        <div class="modal-content-wrapper activity-modal-content">
            <button type="button" class="close-btn activity-close-btn" aria-label="Cerrar">&times;</button>
            <div class="activity-modal-header">
                <h2 id="activity-modal-title">Actividades del día</h2>
            </div>
            <div id="activity-modal-body"></div>
        </div>
    </div>