<?php

namespace App\Middleware;

class CorsMiddleware
{
    public static function handle(): void
    {
        // Origen permitido
        header('Access-Control-Allow-Origin: *');

        // Métodos HTTP permitidos
        header(
            'Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'
        );

        // Cabeceras permitidas
        header(
            'Access-Control-Allow-Headers: Content-Type, Authorization'
        );

        // Tipo de contenido
        header(
            'Content-Type: application/json; charset=UTF-8'
        );

        // Responder a las solicitudes preflight
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {

            http_response_code(204);

            exit;
        }
    }
}