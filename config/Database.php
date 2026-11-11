<?php

namespace Config;

use PDO;
use PDOException;

class Database
{
    private string $host;
    private string $database;
    private string $username;
    private string $password;
    public function __construct()
    {
        $env = require __DIR__ . '/env.php';
        $this->host = $env['DB_HOST'];
        $this->database = $env['DB_NAME'];
        $this->username = $env['DB_USER'];
        $this->password = $env['DB_PASS'];
    } 

    public function connect(): PDO
    {
        try {

            $dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4";

            $connection = new PDO(
                $dsn,
                $this->username,
                $this->password
            );

            $connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            $connection->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );

            $connection->setAttribute(
                PDO::ATTR_EMULATE_PREPARES,
                false
            );

            return $connection;

        } catch (PDOException $e) {

            throw new PDOException(
                'Error al conectar con la base de datos.'
            );
        }
    }
}