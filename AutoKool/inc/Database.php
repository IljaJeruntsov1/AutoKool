<?php

class Database
{
    private $conn;
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $baseName = 'autokool';

    public function __construct()
    {
        $this->connect();
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function connect()
    {
        if (!$this->conn) {
            try {
                $this->conn = new PDO(
                    'mysql:host=' . $this->host .
                    ';dbname=' . $this->baseName .
                    ';charset=utf8mb4',
                    $this->user,
                    $this->password
                );

                $this->conn->setAttribute(
                    PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION
                );

                $this->conn->setAttribute(
                    PDO::ATTR_DEFAULT_FETCH_MODE,
                    PDO::FETCH_ASSOC
                );

            } catch (PDOException $e) {
                die('Ошибка подключения к базе данных: ' . $e->getMessage());
            }
        }

        return $this->conn;
    }

    public function disconnect()
    {
        $this->conn = null;
    }

    public function getOne($query, $params = [])
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

        return $stmt->fetch();
    }

    public function getAll($query, $params = [])
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function executeRun($query, $params = [])
    {
        $stmt = $this->conn->prepare($query);

        return $stmt->execute($params);
    }
}

?>