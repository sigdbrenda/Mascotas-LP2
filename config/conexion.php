<?php

class conexion {
    private string $dsn;
    private string $username;
    private string $password;
    private array $options;
    private ?PDO $conexion = null;

    public function __construct(
        string $host = 'localhost',
        string $dbname = 'mascotas_db',
        string $username = 'root',
        string $password = ''
    ) {
        $this->dsn      = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
        $this->username = $username;
        $this->password = $password;

        // estas constantes DEBEN ir así
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
            throw new Exception(
                'error en la conexion a la base de datos: ' . $e->getMessage()
            );
        }
    }

    public function terminar(): void {
        $this->conexion = null;
    }
}
?>
