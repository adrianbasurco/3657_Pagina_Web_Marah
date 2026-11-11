<?php

namespace App\Controllers;

use App\Models\Cliente;
use App\Models\Clientes;
use Core\Request;
use Core\Response;
use PDO;

class ClientesController
{
    private Clientes $cliente;

    public function __construct(PDO $db)
    {
        $this->cliente = new Clientes($db);
    }

    public function index(): void
    {
        $cliente = $this->cliente->obtenerTodos();

        Response::success(
            $cliente,
            'Clientes obtenidos correctamente'
        );
    }
}