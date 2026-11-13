<?php

namespace App\Models;

use PDO;

class Categorias
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
            id_categoria,
            nombre   
            FROM categorias
            WHERE activo = 1
            ORDER BY nombre;
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
