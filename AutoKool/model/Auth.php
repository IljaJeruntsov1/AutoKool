<?php

class Auth
{
    public static function login($email, $password)
    {
        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            return [
                'success' => false,
                'message' => 'Palun kontrolli e-posti aadressi ja parooli.'
            ];
        }

        $db = new Database();

        $sql = "SELECT id, username, email, password, status
                FROM users
                WHERE email = ?
                LIMIT 1";

        $user = $db->getOne($sql, [$email]);

        if (!$user || !password_verify($password, $user['password'])) {
            return [
                'success' => false,
                'message' => 'Vale e-posti aadress või parool.'
            ];
        }

        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'status' => $user['status']
        ];

        return [
            'success' => true,
            'message' => 'Sisselogimine õnnestus!'
        ];
    }

    public static function logout()
    {
        unset($_SESSION['user']);
        session_regenerate_id(true);
    }

    public static function currentUser()
    {
        return $_SESSION['user'] ?? null;
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['user']);
    }
}
?>