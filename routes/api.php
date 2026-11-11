<?php

use App\Controllers\ClientesController;

$clientesController = new ClientesController($db);

// Listar clientes
$router->get(
    '/api/clientes',
    [$clientesController, 'index']
);