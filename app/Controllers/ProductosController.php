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
    public function store(): void
    {
        $datos = Request::body();
        // Validar campos obligatorios
        if (empty($datos['nombre']) || empty($datos['presentacion']) || empty($datos['id_categoria'])) {
            Response::error('Faltan datos obligatorios: nombre, presentacion e id_categoria', 400);
        }

        // Validar precio
        if (!isset($datos['precio']) || !is_numeric($datos['precio']) || $datos['precio'] <= 0) {
            Response::error('El precio debe ser un número mayor a 0', 400);
        }

        //Validar stock
        if (isset($datos['stock']) && (!is_numeric($datos['stock']) || $datos['stock'] < 0)) {
            Response::error('El stock debe ser un número mayor o igual a 0', 400);
        }

        $id = $this->productos->crear($datos);

        Response::success(
            ['id_producto' => $id],
            'Producto creado correctamente',
            201
        );
    }
}
