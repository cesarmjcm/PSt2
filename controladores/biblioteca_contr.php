<?php
require_once __DIR__ . '/../modelos/biblioteca.php';
require_once __DIR__ . '/../helpers/validador.php';

class BibliotecaController
{
    private $model;

    public function __construct()
    {
        $this->model = new Biblioteca();
    }

    public function dispatch(): void
    {
        $action = $_REQUEST['action'] ?? '';

        switch ($action) {
            case 'crear':
                $this->crear();
                break;
            case 'actualizar':
                $this->actualizar();
                break;
            case 'eliminar':
                $this->eliminar();
                break;
            case 'listar':
                $this->listar();
                break;
            default:
                $this->error('Acción inválida.');
                break;
        }
    }

    private function crear(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $id_parroquia = intval($_POST['id_parroquia'] ?? 0);
        $correo = trim($_POST['correo'] ?? '');
        $redes_sociales = trim($_POST['redes_sociales'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        if ($nombre === '') {
            $this->error('Nombre es obligatorio.');
            return;
        }
        if ($id_parroquia <= 0) {
            $this->error('Debe seleccionar una parroquia.');
            return;
        }
        if (!Validador::esNombrePropioValido($nombre, 2, 50)) {
            $this->error('El nombre debe tener entre 2 y 50 caracteres y solo puede contener letras, espacios y los signos - \'');
            return;
        }
        if (Validador::tieneRepeticionSospechosa($nombre)) {
            $this->error('El nombre no puede tener caracteres o patrones repetidos.');
            return;
        }
        if ($correo !== '' && !Validador::esCorreoValido($correo, 30)) {
            $this->error('El correo tiene un formato inválido o supera los 30 caracteres.');
            return;
        }
        if ($redes_sociales !== '' && !Validador::esContactoValido($redes_sociales, 0, 40)) {
            $this->error('Las redes sociales tienen un formato o longitud inválida (máx. 40 caracteres).');
            return;
        }
        if ($redes_sociales !== '' && Validador::tieneRepeticionSospechosa($redes_sociales)) {
            $this->error('Las redes sociales no pueden tener caracteres o patrones repetidos.');
            return;
        }
        if ($direccion !== '' && !Validador::esDescripcionValida($direccion, 0, 30)) {
            $this->error('La dirección tiene un formato o longitud inválida (máx. 30 caracteres).');
            return;
        }
        if ($direccion !== '' && Validador::tieneRepeticionSospechosa($direccion)) {
            $this->error('La dirección no puede tener caracteres o patrones repetidos.');
            return;
        }
        if ($this->model->existeNombre($nombre, $id_parroquia)) {
            $this->error('Ya existe una biblioteca con ese nombre en esta parroquia.');
            return;
        }

        try {
            $created = $this->model->crearBiblioteca(
    $nombre,
    $id_parroquia,
    $correo,
    $redes_sociales,
    $direccion
);
            if ($created) {
                $this->success('Biblioteca creada correctamente.');
                return;
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->error('Ya existe una biblioteca con ese nombre en esta parroquia.');
                return;
            }
            $this->error('No se pudo crear la biblioteca.');
            return;
        }

        $this->error('No se pudo crear la biblioteca.');
    }

    private function actualizar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        $id = intval($_POST['id'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$id_parroquia = intval($_POST['id_parroquia'] ?? 0);
$correo = trim($_POST['correo'] ?? '');
$redes_sociales = trim($_POST['redes_sociales'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
        if ($id <= 0 || $nombre === '' || $id_parroquia <= 0) {
            $this->error('ID, nombre y parroquia son obligatorios.');
            return;
        }
        if (!Validador::esNombrePropioValido($nombre, 2, 50)) {
            $this->error('El nombre debe tener entre 2 y 50 caracteres y solo puede contener letras, espacios y los signos - \'');
            return;
        }
        if (Validador::tieneRepeticionSospechosa($nombre)) {
            $this->error('El nombre no puede tener caracteres o patrones repetidos.');
            return;
        }
        if ($this->model->existeNombre($nombre, $id_parroquia, $id)) {
            $this->error('Ya existe otra biblioteca con ese nombre en esta parroquia.');
            return;
        }
        if ($correo !== '' && !Validador::esCorreoValido($correo, 30)) {
            $this->error('El correo tiene un formato inválido o supera los 30 caracteres.');
            return;
        }
        if ($redes_sociales !== '' && !Validador::esContactoValido($redes_sociales, 0, 40)) {
            $this->error('Las redes sociales tienen un formato o longitud inválida (máx. 40 caracteres).');
            return;
        }
        if ($redes_sociales !== '' && Validador::tieneRepeticionSospechosa($redes_sociales)) {
            $this->error('Las redes sociales no pueden tener caracteres o patrones repetidos.');
            return;
        }
        if ($direccion !== '' && !Validador::esDescripcionValida($direccion, 0, 30)) {
            $this->error('La dirección tiene un formato o longitud inválida (máx. 30 caracteres).');
            return;
        }
        if ($direccion !== '' && Validador::tieneRepeticionSospechosa($direccion)) {
            $this->error('La dirección no puede tener caracteres o patrones repetidos.');
            return;
        }

        try {
            $updated = $this->model->actualizarBiblioteca(
    $id,
    $nombre,
    $id_parroquia,
    $correo,
    $redes_sociales,
    $direccion
);
            if ($updated) {
                $this->success('Biblioteca actualizada correctamente.');
                return;
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->error('Ya existe otra biblioteca con ese nombre en esta parroquia.');
                return;
            }
            $this->error('No se pudo actualizar la biblioteca.');
            return;
        }

        $this->error('No se pudo actualizar la biblioteca.');
    }

    private function eliminar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Método no permitido.');
            return;
        }

        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->error('ID inválido.');
            return;
        }

        try {
            $deleted = $this->model->eliminarBiblioteca($id);
            if ($deleted) {
                $this->success('Biblioteca eliminada correctamente.');
                return;
            }
        } catch (Exception $e) {
            $this->error('No se pudo eliminar: esta biblioteca tiene actividades asociadas.');
            return;
        }

        $this->error('No se pudo eliminar la biblioteca.');
    }

    private function listar(): void
    {
        $data = $this->model->mostrarBibliotecas();
        $this->respond(['success' => true, 'data' => $data]);
    }

    private function success(string $message, array $extra = []): void
    {
        $this->respond(array_merge(['success' => true, 'message' => $message], $extra));
    }

    private function error(string $message): void
    {
        $this->respond(['success' => false, 'message' => $message]);
    }

    private function respond(array $payload): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

$controller = new BibliotecaController();
$controller->dispatch();
