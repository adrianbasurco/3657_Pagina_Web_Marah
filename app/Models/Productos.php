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
}  