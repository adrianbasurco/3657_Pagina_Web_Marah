<?php

use App\Controllers\ClientesController;

$clientesController = new ClientesController($db);

$router->get(
    '/api/clientes',
    [$clientesController, 'index']
);