<?php

class Database
{
    private $conn;
    private $host;
    private $user;
    private $password;
    private $baseName;

    public function __construct()
    {
        $this->host = getenv('AUTOKOOL_DB_HOST') ?: 'localhost';
        $this->baseName = getenv('AUTOKOOL_DB_NAME') ?: 'autokool';

        $configuredUser = getenv('AUTOKOOL_DB_USER');
        $configuredPassword = getenv('AUTOKOOL_DB_PASSWORD');
        $isLocalXamppRequest = in_array(
            $_SERVER['REMOTE_ADDR'] ?? '',
            ['127.0.0.1', '::1'],
            true
        ) && preg_match('/^(localhost|127\.0\.0\.1)(:\d+)?$/i', $_SERVER['HTTP_HOST'] ?? '');

        $usingLocalFallback = ($configuredUser === false || $configuredUser === '') && $isLocalXamppRequest;
        if ($usingLocalFallback) {
            // Keep the existing default XAMPP setup working on loopback only.
            $this->user = 'root';
            $this->password = '';
        } else {
            $this->user = $configuredUser ?: '';
            $this->password = $configuredPassword ?: '';
        }

        if (!$usingLocalFallback &&
            ($this->user === '' || $this->user === 'root' || $this->password === '')) {
            throw new RuntimeException(
                'Configure AUTOKOOL_DB_USER with a dedicated database user and AUTOKOOL_DB_PASSWORD.'
            );
        }

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
