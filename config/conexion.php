<?php

class conexion {
    private $dsn;
    private $username;
    private $password;
    private $options;
    private $conexion;

    public function __construct(
        string $host = "localhost",
        string $dbname = "tienda_mascotas_g",
        string $username = "root",
        string $password = ""
    ) {
        $this->dsn      = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
        $this->username = $username;
        $this->password = $password;

        // Opciones correctas de PDO
        $this->options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
    }

    public function iniciar(): ?PDO {
        try {
            if ($this->conexion === null) {
                $this->conexion = new PDO(
                    $this->dsn,
                    $this->username,
                    $this->password,
                    $this->options
                );
            }
            return $this->conexion;
        } catch (PDOException $e) {
            throw new Exception("error en la conexion a la base de datos: " . $e->getMessage());
        }
    }

    public function terminar(): void {
        $this->conexion = null;
    }
}
