<?php

use App\Controllers\ClientesController;

$clientesController = new ClientesController($db);

// Listar categorías
$router->get(
    '/api/clientes',
    [$clientesController, 'index']
);