
<?php

class User
{
    public static function registerUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return [
                'success' => false,
                'message' => 'Palun täida registreerimisvorm.'
            ];
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';

        if ($name === '' || mb_strlen($name) > 100) {
            return [
                'success' => false,
                'message' => 'Sisesta nimi (kuni 100 märki).'
            ];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
            return [
                'success' => false,
                'message' => 'Palun sisesta korrektne e-posti aadress.'
            ];
        }

        if (strlen($password) < 8) {
            return [
                'success' => false,
                'message' => 'Parool peab sisaldama vähemalt 8 märki.'
            ];
        }

        if ($password !== $confirm) {
            return [
                'success' => false,
                'message' => 'Paroolid ei kattu.'
            ];
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $date = date('Y-m-d');

        $sql = "INSERT INTO users
                    (username, email, password, status, registration_date)
                VALUES (?, ?, ?, ?, ?)";

        try {
            $db = new Database();
            $db->executeRun($sql, [
                $name,
                $email,
                $passwordHash,
                'student',
                $date
            ]);

            return [
                'success' => true,
                'message' => 'Registreerimine õnnestus! Nüüd saad kursustega tutvuda.'
            ];
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return [
                    'success' => false,
                    'message' => 'Selle e-posti aadressiga konto on juba olemas.'
                ];
            }

            error_log($e->getMessage());

            return [
                'success' => false,
                'message' => 'Registreerimisel tekkis viga. Palun proovi hiljem uuesti.'
            ];
        }
    }
}
?>
