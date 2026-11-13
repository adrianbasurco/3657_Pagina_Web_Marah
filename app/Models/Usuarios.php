<?php

namespace App\Models;

use PDO;

class Usuarios
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function buscarPorCorreo(string $correo): array|false
    {
        $stmt = $this->db->prepare('CALL sp_validar_usuario(:correo)');
        $stmt->execute(['correo' => $correo]);

        $usuario = $stmt->fetch();
        $stmt->closeCursor();

        return $usuario;
    }
}