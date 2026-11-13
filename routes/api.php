<?php

use App\Controllers\AuthController;
use App\Controllers\ClientesController;
use App\Controllers\ProductosController;
use App\Controllers\CategoriasController;


$clientesController = new ClientesController($db);
$productosController = new ProductosController($db);
$categoriasController = new CategoriasController($db);
$authcontroller = new AuthController($db);


// Listar clientes
$router->get(
    '/api/clientes',
    [$clientesController, 'index']
);
// Listar Productos
$router->get(
    '/api/productos',
    [$productosController, 'index']
);
// Obtener un producto por ID
$router->get(
    '/api/productos/{id}',
    [$productosController, 'show']
);
// Crear un producto
$router->post(
    '/api/productos',
    [$productosController, 'store']
);
// Actualizar un producto
$router->put(
    '/api/productos/{id}',
    [$productosController, 'update']
);
// Eliminar un producto
$router->delete(
    '/api/productos/{id}',
    [$productosController, 'destroy']
);
// Listar Categorías
$router->get(
    '/api/categorias',
    [$categoriasController, 'index']
);
//Login de usuario
$router->post(
    '/api/login',
    [$authcontroller, 'login']
);
//Logout de usuario
$router->post(
    '/api/logout',
    [$authcontroller, 'logout']
);
//Verificar sesión
$router->get(
    '/api/sesion',
    [$authcontroller, 'sesion']
);