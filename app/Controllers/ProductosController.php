<?php

namespace App\Controllers;

use App\Models\Productos;
use Core\Request;
use Core\Response;
use PDO;

class ProductosController
{
    private Productos $productos;

    public function __construct(PDO $db)
    {
        $this->productos = new Productos($db);
    }

    public function index(): void
    {
        $productos = $this->productos->obtenerTodos();

        Response::success(
            $productos,
            'Productos obtenidos correctamente'
        );
    }
    public function show(int $id): void
    {
        $producto = $this->productos->obtenerPorId($id);
        if (!$producto) {
            Response::error('Producto no encontrado', 404);
        }
        Response::success(
            $producto,
            'Producto encontrado'
        );
    }
}
