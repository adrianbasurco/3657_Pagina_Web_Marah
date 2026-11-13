<?php

namespace App\Controllers;

use App\Models\Usuarios;
use Core\Request;
use Core\Response;
use PDO;

class AuthController
{
    private Usuarios $usuarios;

    public function __construct(PDO $db)
    {
        $this->usuarios = new Usuarios($db);
    }

    public function login(): void
    {
        $datos = Request::body();

        $correo   = trim($datos['correo'] ?? '');
        $password = $datos['password'] ?? '';

        // 1. Validar que vengan los dos datos
        if ($correo === '' || $password === '') {
            Response::error('Correo y contraseña son obligatorios', 400);
        }

        // 2. Buscar al usuario con el procedimiento almacenado
        $usuario = $this->usuarios->buscarPorCorreo($correo);

        // 3. Comparar la contraseña con el hash
        if (!$usuario || !password_verify($password, $usuario['password'])) {
            Response::error('Credenciales incorrectas', 401);
        }

        // 4. Crear la sesión
        session_regenerate_id(true);
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre']     = $usuario['nombre'];

        Response::success(
            ['nombre' => $usuario['nombre']],
            'Inicio de sesión exitoso'
        );
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();

        Response::success(null, 'Sesión cerrada correctamente');
    }

    public function sesion(): void
    {
        if (!isset($_SESSION['id_usuario'])) {
            Response::error('No has iniciado sesión', 401);
        }

        Response::success(
            ['nombre' => $_SESSION['nombre']],
            'Sesión activa'
        );
    }
}