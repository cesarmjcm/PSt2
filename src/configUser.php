<?php
include '../include/guardian.php';
include '../include/header.php';
require_once __DIR__ . '/../modelos/usuario.php';
require_once __DIR__ . '/../modelos/empleado.php';

$model = new Usuario();
$empleadoModel = new Empleado();
$mensajeUsuarios = '';
$errorUsuarios = '';

$esAdmin = esAdministrador();
$idSesion = intval($_SESSION['user_id'] ?? 0);

// Procesamiento de alta/edición/baja de usuarios (POST tradicional, sin AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario_action'])) {
    $usuarioAction = $_POST['usuario_action'];

    // Un usuario básico nunca puede crear usuarios ni editar a otros.
    if ($usuarioAction === 'crear' && !$esAdmin) {
        $usuarioAction = null;
        $errorUsuarios = 'No tienes permisos para crear usuarios.';
    } elseif ($usuarioAction === 'eliminar' && !$esAdmin) {
        $usuarioAction = null;
        $errorUsuarios = 'No tienes permisos para eliminar usuarios.';
    } elseif ($usuarioAction === 'actualizar' && !$esAdmin && intval($_POST['id'] ?? 0) !== $idSesion) {
        $usuarioAction = null;
        $errorUsuarios = 'No tienes permisos para editar este usuario.';
    }

    if ($usuarioAction === 'crear' || $usuarioAction === 'actualizar') {
        $esActualizacion = ($usuarioAction === 'actualizar');

        $id = intval($_POST['id'] ?? 0);
        $nombre = trim($_POST['nuevo_nombre'] ?? '');
        $clave = trim($_POST['nueva_clave'] ?? '');
        $claveConfirmacion = trim($_POST['nueva_clave_confirmacion'] ?? '');
        $telefono = trim($_POST['nuevo_telefono'] ?? '');
        $id_empleado = intval($_POST['nuevo_id_empleado'] ?? 0);

        // El rol solo lo puede fijar un administrador.
        $rol = null;
        if ($esAdmin) {
            $rolEnviado = trim($_POST['nuevo_rol'] ?? '');
            if (in_array($rolEnviado, ['administrador', 'usuario'], true)) {
                $rol = $rolEnviado;
            }
        }

        if ($esActualizacion && $id <= 0) {
            $errorUsuarios = 'ID de usuario inválido.';
        } elseif (!$esActualizacion && ($nombre === '' || $clave === '')) {
            $errorUsuarios = 'Nombre y clave son obligatorios.';
        } else {
            $datosValidar = [
                'nombre' => $nombre,
                'clave' => $clave,
                'telefono' => $telefono,
                'id_empleado' => $id_empleado,
            ];
            if ($rol !== null) {
                $datosValidar['rol'] = $rol;
            }
            $errors = $model->validarUsuario($datosValidar, $esActualizacion);

            if ($clave !== '' && $clave !== $claveConfirmacion) {
                $errors[] = 'La confirmación de la clave no coincide.';
            } elseif (!$esActualizacion && $clave === '') {
                // ya cubierto arriba, pero por si acaso
            }

            $empleadoSeleccionado = $empleadoModel->obtenerEmpleadoPorId($id_empleado);

            if (!empty($errors)) {
                $errorUsuarios = implode(' ', $errors);
            } elseif ($model->existeUsuario($nombre, $esActualizacion ? $id : null)) {
                $errorUsuarios = 'Ya existe un usuario con ese nombre.';
            } elseif (!$empleadoSeleccionado) {
                $errorUsuarios = 'El empleado seleccionado no existe.';
            } elseif (trim((string) ($empleadoSeleccionado['telefono'] ?? '')) !== $telefono) {
                $errorUsuarios = 'El teléfono no coincide con el registrado para ese empleado.';
            } elseif ($model->existeUsuarioPorEmpleado($id_empleado, $esActualizacion ? $id : null)) {
                $errorUsuarios = 'Ese empleado ya tiene un usuario registrado. No se puede crear más de uno por empleado.';
            } else {
                if ($esActualizacion) {
                    $ok = $model->actualizarUsuario($id, $nombre, $telefono, $id_empleado, $clave === '' ? null : $clave, $rol);
                    $mensajeExito = 'Usuario actualizado correctamente.';
                    $mensajeFallo = 'No se pudo actualizar el usuario.';
                } else {
                    $ok = $model->crearUsuario($nombre, $clave, $telefono, $id_empleado, $rol ?? 'usuario');
                    $mensajeExito = 'Usuario creado correctamente.';
                    $mensajeFallo = 'No se pudo crear el usuario.';
                }

                if ($ok) {
                    $mensajeUsuarios = $mensajeExito;
                } else {
                    $errorUsuarios = $mensajeFallo;
                }
            }
        }
    } elseif ($usuarioAction === 'eliminar' && $esAdmin) {
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            $errorUsuarios = 'ID inválido.';
        } else {
            $ok = $model->eliminarUsuario($id);
            if ($ok) {
                $mensajeUsuarios = 'Usuario eliminado correctamente.';
            } else {
                $errorUsuarios = 'No se pudo eliminar el usuario.';
            }
        }
    }
}

$usuarios = [];
try {
    $usuarios = $model->mostrarUsuarios();
    if (!$esAdmin) {
        $usuarios = array_values(array_filter($usuarios, fn($u) => (int) $u['id'] === $idSesion));
    }
} catch (Exception $e) {
    $usuarios = [];
}

$empleados = [];
try {
    $empleados = $empleadoModel->mostrarEmpleados();
} catch (Exception $e) {
    $empleados = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de usuario</title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/configuracion.css">
    <link rel="stylesheet" href="./css/fontawesome-all.min.css">
</head>
<body>
    <main class="config-page">
        <div class="config-container">
            <div class="config-card">
                <div class="config-card__header">
                    <div>
                        <span class="config-badge">Configuración</span>
                        <h1>Configuración de usuario</h1>
                        <p>Gestiona los usuarios del sistema con un diseño limpio y moderno.</p>
                    </div>
                   
                </div>
                <div class="config-grid">
                    <aside class="config-menu" aria-label="Menú de configuración">
                        
                        <button type="button" class="config-menu__item active" data-tab="usuarios">Usuarios</button>
                        <button type="button" class="config-menu__item" id="cerrarSesion">Cerrar sesión</button>
                    </aside>
                    <section class="config-details">
                        

                        <div class="config-panel active" id="usuarios">
                            <h2 id="usuario-form-titulo"><?php echo $esAdmin ? 'Usuarios' : 'Mi perfil'; ?></h2>

                            <?php if ($mensajeUsuarios): ?>
                                <div class="config-note" style="border-color:#156939; color:#156939;">
                                    <p><?php echo htmlspecialchars($mensajeUsuarios, ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if ($errorUsuarios): ?>
                                <div class="config-note" style="border-color:#b3261e; color:#b3261e;">
                                    <p><?php echo htmlspecialchars($errorUsuarios, ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php
                                // Un usuario básico siempre está editando su propio registro;
                                // un administrador arranca en modo "crear" por defecto.
                                $miUsuario = $esAdmin ? null : ($usuarios[0] ?? null);
                            ?>
                            <form method="POST" action="" id="form-usuario" style="margin-top:16px;">
                                <input type="hidden" name="usuario_action" id="usuario_action"
                                       value="<?php echo $esAdmin ? 'crear' : 'actualizar'; ?>">
                                <input type="hidden" name="id" id="usuario_id"
                                       value="<?php echo $esAdmin ? '' : htmlspecialchars($miUsuario['id'] ?? ''); ?>">

                                <div class="config-field">
                                    <label for="nuevo_nombre">Nombre</label>
                                    <input id="nuevo_nombre" name="nuevo_nombre" type="text" required
                                           value="<?php echo $esAdmin ? '' : htmlspecialchars($miUsuario['nombre'] ?? ''); ?>">
                                </div>

                                <div class="config-field">
                                    <label for="nuevo_id_empleado">Empleado</label>
                                    <select id="nuevo_id_empleado" name="nuevo_id_empleado" required>
                                        <option value="">Seleccione un empleado</option>
                                        <?php foreach ($empleados as $emp): ?>
                                            <option value="<?php echo htmlspecialchars($emp['id']); ?>"
                                                    data-telefono="<?php echo htmlspecialchars($emp['telefono'] ?? ''); ?>"
                                                    <?php echo (!$esAdmin && (string) $emp['id'] === (string) ($miUsuario['id_empleado'] ?? '')) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($emp['nombre'] . ' ' . $emp['apellido']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small>Cada empleado solo puede tener un usuario.</small>
                                </div>

                                <div class="config-field">
                                    <label for="nuevo_telefono">Teléfono</label>
                                    <input id="nuevo_telefono" name="nuevo_telefono" type="text"
                                           placeholder="Selecciona primero un empleado" required readonly
                                           value="<?php echo $esAdmin ? '' : htmlspecialchars($miUsuario['telefono'] ?? ''); ?>">
                                    <small>Se autocompleta con el teléfono registrado del empleado; deben coincidir.</small>
                                </div>

                                <?php if ($esAdmin): ?>
                                <div class="config-field">
                                    <label for="nuevo_rol">Rol</label>
                                    <select id="nuevo_rol" name="nuevo_rol">
                                        <option value="usuario">Usuario</option>
                                        <option value="administrador">Administrador</option>
                                    </select>
                                    <small>Solo un administrador puede asignar este privilegio.</small>
                                </div>
                                <?php endif; ?>

                                <div class="config-field">
                                    <label for="nueva_clave" id="label-clave">
                                        <?php echo $esAdmin ? 'Clave' : 'Nueva clave (dejar en blanco para no cambiarla)'; ?>
                                    </label>
                                    <input id="nueva_clave" name="nueva_clave" type="password">
                                </div>
                                <div class="config-field">
                                    <label for="nueva_clave_confirmacion">Confirmar clave</label>
                                    <input id="nueva_clave_confirmacion" name="nueva_clave_confirmacion" type="password">
                                </div>

                                <button class="btn-save" type="submit" id="btn-guardar-usuario">
                                    <?php echo $esAdmin ? 'Crear usuario' : 'Guardar cambios'; ?>
                                </button>
                                <button class="btn-secondary" type="button" id="btn-cancelar-edicion" style="display:none;">Cancelar edición</button>
                            </form>

                            <?php if ($esAdmin): ?>
                            <div class="config-note" style="margin-top:24px;">
                                <p>Listado de usuarios registrados en la base de datos.</p>
                            </div>
                            <div class="tabla__container" style="margin-top:30px;">
                                <table class="tabla-planificacion" style="min-width:0; margin-top:10px;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Usuario</th>
                                            <th>Teléfono</th>
                                            <th>Empleado</th>
                                            <th>Rol</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($usuarios)): ?>
                                            <?php foreach ($usuarios as $u): ?>
                                                <?php
                                                    $empleadoNombre = trim($u['id_empleado_nombre'] ?? '');
                                                    $empleadoNombre = $empleadoNombre !== '' ? $empleadoNombre : 'Sin empleado';
                                                    $rolUsuario = $u['rol'] ?? 'usuario';
                                                ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($u['id']); ?></td>
                                                    <td><?php echo htmlspecialchars($u['nombre']); ?></td>
                                                    <td><?php echo htmlspecialchars($u['telefono'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($empleadoNombre); ?></td>
                                                    <td><?php echo $rolUsuario === 'administrador' ? 'Administrador' : 'Usuario'; ?></td>
                                                    <td>
                                                        <button type="button" class="btn-secondary btn-editar-usuario"
                                                                data-id="<?php echo htmlspecialchars($u['id']); ?>"
                                                                data-nombre="<?php echo htmlspecialchars($u['nombre']); ?>"
                                                                data-telefono="<?php echo htmlspecialchars($u['telefono'] ?? ''); ?>"
                                                                data-id-empleado="<?php echo htmlspecialchars($u['id_empleado'] ?? ''); ?>"
                                                                data-rol="<?php echo htmlspecialchars($rolUsuario); ?>">
                                                            Editar
                                                        </button>
                                                        <form method="POST" action="" style="display:inline;">
                                                            <input type="hidden" name="usuario_action" value="eliminar">
                                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($u['id']); ?>">
                                                            <button class="btn-secondary" type="submit" onclick="return confirm('¿Eliminar este usuario?');">Eliminar</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="6">No hay usuarios registrados.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                        </div>

                    </section>
                </div>
                <div class="config-actions">
                    <button class="btn-primary btn-save" type="button">Guardar cambios</button>
                    <button class="btn-secondary" type="button">Cancelar</button>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const menuButtons = document.querySelectorAll('.config-menu__item[data-tab]');
            const panels = document.querySelectorAll('.config-panel');

            menuButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const target = button.dataset.tab;
                    menuButtons.forEach(btn => btn.classList.toggle('active', btn === button));
                    panels.forEach(panel => panel.classList.toggle('active', panel.id === target));
                });
            });

            const logoutBtn = document.getElementById('cerrarSesion');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', () => {
                    window.location.href = 'logout.php';
                });
            }

            // --- Edición de usuarios: reutiliza el mismo formulario de creación ---
            const formTitulo = document.getElementById('usuario-form-titulo');
            const usuarioActionInput = document.getElementById('usuario_action');
            const usuarioIdInput = document.getElementById('usuario_id');
            const nombreInput = document.getElementById('nuevo_nombre');
            const telefonoInput = document.getElementById('nuevo_telefono');
            const empleadoSelect = document.getElementById('nuevo_id_empleado');
            const claveInput = document.getElementById('nueva_clave');
            const claveConfirmInput = document.getElementById('nueva_clave_confirmacion');
            const rolSelect = document.getElementById('nuevo_rol'); // solo existe si el panel es de administrador
            const labelClave = document.getElementById('label-clave');
            const btnGuardar = document.getElementById('btn-guardar-usuario');
            const btnCancelar = document.getElementById('btn-cancelar-edicion');
            const formUsuario = document.getElementById('form-usuario');

            function autocompletarTelefonoDesdeEmpleado() {
                const opcion = empleadoSelect.options[empleadoSelect.selectedIndex];
                if (opcion && opcion.value !== '') {
                    telefonoInput.value = opcion.dataset.telefono || '';
                    telefonoInput.readOnly = true;
                } else {
                    telefonoInput.value = '';
                    telefonoInput.readOnly = false;
                }
            }

            if (empleadoSelect) {
                empleadoSelect.addEventListener('change', autocompletarTelefonoDesdeEmpleado);
            }

            function entrarModoCreacion() {
                usuarioActionInput.value = 'crear';
                usuarioIdInput.value = '';
                formUsuario.reset();
                telefonoInput.readOnly = false;
                formTitulo.textContent = 'Usuarios';
                btnGuardar.textContent = 'Crear usuario';
                btnCancelar.style.display = 'none';
                labelClave.textContent = 'Clave';
                claveInput.required = true;
                claveConfirmInput.required = true;
                if (rolSelect) rolSelect.value = 'usuario';
            }

            function entrarModoEdicion(datos) {
                usuarioActionInput.value = 'actualizar';
                usuarioIdInput.value = datos.id;
                nombreInput.value = datos.nombre;
                telefonoInput.value = datos.telefono;
                empleadoSelect.value = datos.idEmpleado || '';
                autocompletarTelefonoDesdeEmpleado();
                claveInput.value = '';
                claveConfirmInput.value = '';
                formTitulo.textContent = 'Editando usuario: ' + datos.nombre;
                btnGuardar.textContent = 'Guardar cambios';
                btnCancelar.style.display = 'inline-block';
                labelClave.textContent = 'Nueva clave (dejar en blanco para no cambiarla)';
                claveInput.required = false;
                claveConfirmInput.required = false;
                if (rolSelect) rolSelect.value = datos.rol || 'usuario';
                formUsuario.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            document.querySelectorAll('.btn-editar-usuario').forEach((btn) => {
                btn.addEventListener('click', () => {
                    entrarModoEdicion({
                        id: btn.dataset.id,
                        nombre: btn.dataset.nombre,
                        telefono: btn.dataset.telefono,
                        idEmpleado: btn.dataset.idEmpleado,
                        rol: btn.dataset.rol,
                    });
                });
            });

            if (btnCancelar) {
                btnCancelar.addEventListener('click', entrarModoCreacion);
            }

            if (formUsuario) {
                formUsuario.addEventListener('submit', (ev) => {
                    const clave = claveInput.value.trim();
                    const confirmacion = claveConfirmInput.value.trim();
                    const esActualizacion = usuarioActionInput.value === 'actualizar';

                    if (!esActualizacion && clave === '') {
                        ev.preventDefault();
                        alert('La clave es obligatoria para crear un usuario.');
                        claveInput.focus();
                        return;
                    }

                    if (clave !== confirmacion) {
                        ev.preventDefault();
                        alert('La confirmación de la clave no coincide.');
                        claveConfirmInput.focus();
                    }
                });
            }
        });
    </script>
</body>
</html>
