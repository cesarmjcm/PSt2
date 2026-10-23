// Utilidades compartidas entre vistas.
// Se carga antes que validaciones.js / main2.js / index.js.

function calcularDiaSemana(fechaString) {
    const dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    if (!fechaString) return '';

    const [year, month, day] = fechaString.split('-').map(Number);
    if (!year || !month || !day) return '';

    const fecha = new Date(year, month - 1, day);
    if (Number.isNaN(fecha.getTime())) return '';
    return dias[fecha.getDay()];
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
