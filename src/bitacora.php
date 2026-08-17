<?php
// BITACORA.PHP - Página de historial de acciones (solo administrador)
// Este archivo es autosuficiente: valida sesión, consulta datos y renderiza la vista.
// Se puede abrir directamente (ej: localhost/PSt2-main/src/bitacora.php).

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../modelos/modelo_bitacora.php';

$conex = Conexion::conectar(); // Usa tu clase Conexion existente

// --- Control de acceso: solo administrador puede ver la bitácora ---
// Si tu guardian.php ya expone una función centralizada tipo requerirRol('administrador'),
// reemplaza este bloque por esa llamada (y agrega el require_once correspondiente).
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
    header('Location: main2.php');
    exit;
}

// --- Filtros desde la URL (GET) ---
$filtros = [
    'id_usu'      => $_GET['id_usu']      ?? '',
    'accion'      => $_GET['accion']      ?? '',
    'fecha_desde' => $_GET['fecha_desde'] ?? '',
    'fecha_hasta' => $_GET['fecha_hasta'] ?? '',
];

$registros = obtener_bitacora($filtros);
$usuarios  = obtener_usuarios_para_filtro();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bitácora del sistema - Red de Bibliotecas</title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/bitacora.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php require_once __DIR__ . '/../include/header.php'; ?>

<main class="container__bitacora">
    <h1 class="bitacora__title">Bitácora del sistema</h1>
    <p class="bitacora__subtitle">Historial de acciones realizadas por los usuarios.</p>

    <form method="GET" action="bitacora.php" class="bitacora__filtros">
        <div class="campo">
            <label for="id_usu">Usuario</label>
            <select name="id_usu" id="id_usu">
                <option value="">Todos los usuarios</option>
                <?php foreach ($usuarios as $u): ?>
                    <option value="<?= (int)$u['id'] ?>" <?= ($filtros['id_usu'] == $u['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($u['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="campo">
            <label for="accion">Acción</label>
            <select name="accion" id="accion">
                <option value="">Toda acción</option>
                <?php foreach (['Crear', 'Editar', 'Eliminar', 'Login'] as $acc): ?>
                    <option value="<?= $acc ?>" <?= ($filtros['accion'] === $acc) ? 'selected' : '' ?>>
                        <?= $acc ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="campo">
            <label for="fecha_desde">Desde</label>
            <input type="date" name="fecha_desde" id="fecha_desde" value="<?= htmlspecialchars($filtros['fecha_desde']) ?>">
        </div>

        <div class="campo">
            <label for="fecha_hasta">Hasta</label>
            <input type="date" name="fecha_hasta" id="fecha_hasta" value="<?= htmlspecialchars($filtros['fecha_hasta']) ?>">
        </div>

        <button type="submit">Filtrar</button>
        <a href="bitacora.php" class="bitacora__limpiar">Limpiar</a>
    </form>

    <div class="tabla__container bitacora__tabla-wrap">
        <table class="bitacora__tabla">
            <caption class="tabla-planificacion__caption">Registros de actividad</caption>
            <thead>
                <tr>
                    <th class="col-fecha">Fecha</th>
                    <th class="col-dia">Día</th>
                    <th class="col-hora">Hora</th>
                    <th class="col-usuario">Usuario</th>
                    <th class="col-accion">Acción</th>
                    <th class="col-modulo">Módulo</th>
                    <th class="col-detalle">Detalle</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($registros)): ?>
                    <tr>
                        <td colspan="7" class="bitacora__vacio">No hay registros de bitácora para los filtros seleccionados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($registros as $r):
                        $accionClase = match (strtolower($r['accion'])) {
                            'crear'    => 'bitacora__badge--crear',
                            'editar'   => 'bitacora__badge--editar',
                            'eliminar' => 'bitacora__badge--eliminar',
                            'login'    => 'bitacora__badge--login',
                            default    => 'bitacora__badge--login',
                        };
                    ?>
                        <tr>
                            <td data-label="Fecha"><?= htmlspecialchars($r['fecha']) ?></td>
                            <td data-label="Día"><?= htmlspecialchars($r['nom_dia']) ?></td>
                            <td data-label="Hora"><?= htmlspecialchars($r['hora']) ?></td>
                            <td data-label="Usuario"><?= htmlspecialchars($r['nombre_usuario']) ?></td>
                            <td data-label="Acción">
                                <span class="bitacora__badge <?= $accionClase ?>"><?= htmlspecialchars($r['accion']) ?></span>
                            </td>
                            <td data-label="Módulo"><?= htmlspecialchars($r['descripcion']) ?></td>
                            <td data-label="Detalle"><?= htmlspecialchars($r['detalle']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
<script src="./js/app.js"></script>
</body>
</html>
