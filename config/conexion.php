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

        // opciones básicas para pdo
        $this->options = [
            pdo::attr_errmode            => pdo::errmode_exception,
            pdo::attr_default_fetch_mode => pdo::fetch_assoc,
        ];
    }

    public function iniciar(): ?pdo {
        try {
            if ($this->conexion === null) {
                $this->conexion = new pdo(
                    $this->dsn,
                    $this->username,
                    $this->password,
                    $this->options
                );
            }
            return $this->conexion;
        } catch (pdoexception $e) {
            throw new exception("error en la conexion a la base de datos: " . $e->getmessage());
        }
    }

    public function terminar(): void {
        $this->conexion = null;
    }
}
