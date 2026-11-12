<?php

namespace App\Models;

use PDO;

class Productos
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function obtenerTodos(): array
    {
        $sql = "
            SELECT 
                p.id_producto,
                p.nombre,
                p.descripcion,
                p.presentacion,
                p.precio,
                p.stock,
                c.nombre AS categoria
            FROM productos p
            INNER JOIN categorias c ON p.id_categoria = c.id_categoria
            WHERE p.activo = 1
            ORDER BY p.nombre
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id): array|false
    {
        $sql = "
            SELECT
                p.id_producto,
                p.nombre,
                p.descripcion,
                p.presentacion,
                p.precio,
                p.stock,
                c.nombre AS categoria
            FROM productos p
            INNER JOIN categorias c ON p.id_categoria = c.id_categoria
            WHERE p.id_producto = :id
            AND p.activo = 1
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    public function crear(array $datos): int
    {
        $sql = "
        INSERT INTO productos
            (id_categoria, nombre, descripcion, presentacion, precio, stock)
        VALUES
            (:id_categoria, :nombre, :descripcion, :presentacion, :precio, :stock)
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_categoria' => $datos['id_categoria'],
            'nombre'       => $datos['nombre'],
            'descripcion'  => $datos['descripcion'] ?? null,
            'presentacion' => $datos['presentacion'],
            'precio'       => $datos['precio'],
            'stock'        => $datos['stock'] ?? 0,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function actualizar(int $id, array $datos): void
    {
        $sql = "
        UPDATE productos
        SET
        id_categoria    = :id_categoria,
        nombre          = :nombre,
        descripcion     = :descripcion,
        presentacion    = :presentacion,
        precio          = :precio,
        stock           = :stock
        WHERE id_producto = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt -> execute([
            'id'            =>$id,
            'id_categoria'  =>$datos['id_categoria'],
            'nombre'        =>$datos['nombre'],
            'descripcion'   =>$datos['descripcion'] ?? null,
            'presentacion'  =>$datos['presentacion'],
            'precio'        =>$datos['precio'],
            'stock'         =>$datos['stock'] ?? 0,
        ]);
    }

    public function desactivar(int $id): void
    {
        $sql ="
        UPDATE productos
        SET activo = 0
        WHERE id_producto = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt -> execute(['id' => $id]);
    }
}