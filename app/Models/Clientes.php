<?php

namespace App\Models;

use PDO;

class Clientes
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
                id_cliente,
                nombres,
                apellidos,
                correo
            FROM clientes
            ORDER BY correo
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}  