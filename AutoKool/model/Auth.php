<?php

class Auth
{
    private static function updateLoginAttempts(string $email, string $operation): bool
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = hash('sha256', strtolower($email) . '|' . $ip);
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'autokool-login-' . $key . '.json';
        $handle = @fopen($path, 'c+');
        if ($handle === false || !flock($handle, LOCK_EX)) {
            if (is_resource($handle)) {
                fclose($handle);
            }
            // Fail closed if the rate-limit store is unavailable.
            return true;
        }

        rewind($handle);
        $stored = stream_get_contents($handle);
        $attempts = json_decode($stored ?: '[]', true);
        if (!is_array($attempts)) {
            $attempts = [];
        }
        $cutoff = time() - 900;
        $attempts = array_values(array_filter(
            $attempts,
            static fn($timestamp) => is_int($timestamp) && $timestamp > $cutoff
        ));

        $limited = count($attempts) >= 3;
        if ($operation === 'failed' && !$limited) {
            $attempts[] = time();
            $limited = count($attempts) >= 3;
        } elseif ($operation === 'success') {
            $attempts = [];
        }

        rewind($handle);
        ftruncate($handle, 0);
        fwrite($handle, json_encode($attempts));
        fflush($handle);
        flock($handle, LOCK_UN);
        fclose($handle);

        if ($operation === 'check' && $limited) {
            $_SESSION['login_locked_until'] = end($attempts) + 900;
        }

        return in_array($operation, ['check', 'failed'], true) && $limited;
    }

    public static function isLoginLockedForSession(): bool
    {
        return self::getLoginLockRemaining() > 0;
    }

    public static function getLoginLockRemaining(): int
    {
        $lockedUntil = (int)($_SESSION['login_locked_until'] ?? 0);
        if ($lockedUntil <= time()) {
            unset($_SESSION['login_locked_until']);
            return 0;
        }
        return $lockedUntil - time();
    }

    public static function login($email, $password)
    {
        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            return [
                'success' => false,
                'message' => 'Palun kontrolli e-posti aadressi ja parooli.'
            ];
        }

        if (self::updateLoginAttempts($email, 'check')) {
            return [
                'success' => false,
                'message' => 'Vale e-posti aadress vГµi parool.'
            ];
        }

        $db = new Database();

        $sql = "SELECT id, username, email, password, status
                FROM users
                WHERE email = ?
                LIMIT 1";

        $user = $db->getOne($sql, [$email]);

        if (
            !$user ||
            $user['status'] === 'deleted' ||
            !is_string($user['password']) ||
            !password_verify($password, $user['password'])
        ) {
            $locked = self::updateLoginAttempts($email, 'failed');
            if ($locked) {
                $_SESSION['login_locked_until'] = time() + 900;
            }
            return [
                'success' => false,
                'message' => 'Vale e-posti aadress või parool.'
            ];
        }

        self::updateLoginAttempts($email, 'success');

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

    public static function isAdmin()
{
    return isset($_SESSION['user'])
        && ($_SESSION['user']['status'] ?? '') === 'admin';
}
}
?>
