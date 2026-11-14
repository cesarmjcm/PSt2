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

// Años COMPLETOS de servicio entre la fecha de ingreso y la fecha final, o hoy si sigue vigente.
// Devuelve null si alguna fecha no es válida, el inicio es futuro o el final es anterior al inicio.
function calcularAniosServicio(fechaIngreso, fechaFin, hoy) {
    const ingreso = parsearFechaISO(fechaIngreso);
    if (!ingreso) return null;

    hoy = hoy || new Date();
    const hoyLocal = new Date(hoy.getFullYear(), hoy.getMonth(), hoy.getDate());
    if (ingreso > hoyLocal) return null;

    const fin = fechaFin
        ? parsearFechaISO(fechaFin)
        : hoyLocal;
    if (!fin || fin < ingreso) return null;

    let anios = fin.getFullYear() - ingreso.getFullYear();
    // Si todavía no llegó el aniversario este año, falta un año por cumplir.
    const yaCumplioAniversario =
        fin.getMonth() > ingreso.getMonth() ||
        (fin.getMonth() === ingreso.getMonth() && fin.getDate() >= ingreso.getDate());
    if (!yaCumplioAniversario) anios--;

    return anios;
}

// Valida la fecha de ingreso. Devuelve un mensaje de error o null si está bien.
function validarFechaIngreso(fechaIngreso, fechaFin, hoy) {
    const ingreso = parsearFechaISO(fechaIngreso);
    if (!ingreso) return 'La fecha de ingreso no es válida.';
    const fin = fechaFin ? parsearFechaISO(fechaFin) : null;
    if (fechaFin && !fin) return 'La fecha final del cargo no es válida.';
    if (fin && fin < ingreso) return 'La fecha final del cargo no puede ser anterior a la fecha de inicio.';
    const anios = calcularAniosServicio(fechaIngreso, fechaFin, hoy);
    if (anios === null) return 'La fecha de ingreso no puede ser futura.';
    if (anios > MAX_ANIOS_SERVICIO) return 'La fecha de ingreso es demasiado antigua (más de ' + MAX_ANIOS_SERVICIO + ' años de servicio).';
    return null;
}
