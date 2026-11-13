<?php

use App\Controllers\ClientesController;
use App\Controllers\ProductosController;
use App\Controllers\CategoriasController;


$clientesController = new ClientesController($db);
$productosController = new ProductosController($db);
$categoriasController = new CategoriasController($db);


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
// eliminar un producto
$router->delete(
    '/api/productos/{id}',
    [$productosController, 'destroy']
);
//listar por Categoria
$router->get(
    '/api/categorias',
    [$categoriasController, 'index']
);