// Cálculo y validación de años de servicio a partir de la fecha de ingreso.
// Se carga en maestro.php antes del script del formulario.

// Años máximos de servicio que se consideran razonables (evita fechas como 0001-01-01).
const MAX_ANIOS_SERVICIO = 70;

// Convierte 'YYYY-MM-DD' en un Date LOCAL (a medianoche). Devuelve null si no es una fecha real.
// OJO: no se usa new Date('YYYY-MM-DD') porque JS lo interpreta como UTC y en Venezuela
// (UTC-4) puede desplazar la fecha un día hacia atrás.
function parsearFechaISO(texto) {
    if (typeof texto !== 'string') return null;
    const m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(texto.trim());
    if (!m) return null;
    const anio = Number(m[1]), mes = Number(m[2]), dia = Number(m[3]);
    const fecha = new Date(anio, mes - 1, dia);
    // Rechaza fechas inexistentes como 2025-02-31 (JS las "corrige" a marzo).
    if (fecha.getFullYear() !== anio || fecha.getMonth() !== mes - 1 || fecha.getDate() !== dia) return null;
    return fecha;
}

// Devuelve la fecha de hoy como 'YYYY-MM-DD' (hora local), útil para el atributo max del input.
function fechaHoyISO() {
    const h = new Date();
    const mm = String(h.getMonth() + 1).padStart(2, '0');
    const dd = String(h.getDate()).padStart(2, '0');
    return h.getFullYear() + '-' + mm + '-' + dd;
}

// Años COMPLETOS de servicio entre la fecha de ingreso y hoy.
// Devuelve null si la fecha no es válida o es futura.
// `hoy` es opcional (sirve para probar la función con otra fecha).
function calcularAniosServicio(fechaIngreso, hoy) {
    const ingreso = parsearFechaISO(fechaIngreso);
    if (!ingreso) return null;

    hoy = hoy || new Date();
    const hoyLocal = new Date(hoy.getFullYear(), hoy.getMonth(), hoy.getDate());
    if (ingreso > hoyLocal) return null;

    let anios = hoyLocal.getFullYear() - ingreso.getFullYear();
    // Si todavía no llegó el aniversario este año, falta un año por cumplir.
    const yaCumplioAniversario =
        hoyLocal.getMonth() > ingreso.getMonth() ||
        (hoyLocal.getMonth() === ingreso.getMonth() && hoyLocal.getDate() >= ingreso.getDate());
    if (!yaCumplioAniversario) anios--;

    return anios;
}

// Valida la fecha de ingreso. Devuelve un mensaje de error o null si está bien.
function validarFechaIngreso(fechaIngreso, hoy) {
    if (!parsearFechaISO(fechaIngreso)) return 'La fecha de ingreso no es válida.';
    const anios = calcularAniosServicio(fechaIngreso, hoy);
    if (anios === null) return 'La fecha de ingreso no puede ser una fecha futura.';
    if (anios > MAX_ANIOS_SERVICIO) return 'La fecha de ingreso es demasiado antigua (más de ' + MAX_ANIOS_SERVICIO + ' años de servicio).';
    return null;
}
