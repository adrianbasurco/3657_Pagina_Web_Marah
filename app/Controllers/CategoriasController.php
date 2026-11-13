<?php

namespace App\Controllers;

use App\Models\Categorias;
use Core\Response;
use PDO;

class CategoriasController
{
    private Categorias $categorias;

    public function __construct(PDO $db)
    {
        $this->categorias = new Categorias($db);
    }

    public function index(): void
    {
        $categoria = $this->categorias->obtenerTodos();

        Response::success(
            $categoria,
            'Categorías obtenidas correctamente'
        );
    }
}