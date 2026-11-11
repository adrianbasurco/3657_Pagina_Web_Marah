<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Middleware\CorsMiddleware;
use Config\Database;
use Core\Response;
use Core\Router;

// ==============================
// CORS
// ==============================

CorsMiddleware::handle();

// =====================================
// CONEXIÓN
// =====================================

try{
        $database = new Database();
        $db = $database->connect();
    } 
catch (PDOException $e) {
        Response::error('Error al conectar con la base de datos', 500);
    }

// =====================================
// ROUTER
// =====================================

$router = new Router();


// =====================================
// CARGAR RUTAS
// =====================================

require_once __DIR__ . '/../routes/api.php';


// =====================================
// MÉTODO HTTP
// =====================================

$method = $_SERVER['REQUEST_METHOD'];


// =====================================
// OBTENER URI
// =====================================

$uri = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);


// =====================================
// ELIMINAR RUTA BASE
// =====================================

   $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

if (str_starts_with($uri, $basePath)) {

    $uri = substr(
        $uri,
        strlen($basePath)
    );
}


// Si queda vacío
if ($uri === '') {
    $uri = '/';
}


// =====================================
// EJECUTAR ROUTER
// =====================================

$router->dispatch(
    $method,
    $uri
);