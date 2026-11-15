<?php

namespace App\Middleware;

use Core\Response;

class AuthMiddleware
{
    public static function handle(): void
    {
        if (!isset($_SESSION['id_usuario'])) {
            Response::error('Debes iniciar sesión para realizar esta acción', 401);
        }
    }
}