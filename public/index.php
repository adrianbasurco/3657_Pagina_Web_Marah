<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Middleware\CorsMiddleware;
use Config\Database;
use Core\Router;

// ==============================
// CORS
// ==============================

CorsMiddleware::handle();

// =====================================
// CONEXIÓN
// =====================================

$database = new Database();
$db = $database->connect();


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

$basePath = '/PROYECTO_MARAH/public';

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