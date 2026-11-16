<?php

class Profile
{
    public static function getUser(int $userId): ?array
    {
        $db = new Database();
        $user = $db->getOne(
            'SELECT id, username, email, password, status FROM users WHERE id = ?',
            [$userId]
        );
        return $user ?: null;
    }

    public static function getCourses(int $userId): array
    {
        $db = new Database();
        return $db->getAll(
            'SELECT c.id, c.title, c.title_en, c.price, c.duration,
                    r.status AS registration_status, r.registration_date
             FROM courses c
             LEFT JOIN registrations r
                 ON r.course_id = c.id AND r.user_id = ?
             ORDER BY c.id DESC',
            [$userId]
        );
    }

    public static function eraseAccount(int $userId, string $password): bool
    {
        $db = new Database();
        $pdo = $db->connect();
        $pdo->beginTransaction();
        try {
            // Блокировка не позволит двум запросам удалить аккаунт одновременно.
            $stmt = $pdo->prepare(
                'SELECT password, status FROM users WHERE id = ? FOR UPDATE'
            );
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || $user['status'] !== 'student' ||
                !is_string($user['password']) ||
                !password_verify($password, $user['password'])) {
                $pdo->rollBack();
                return false;
            }

            // Удаляем заявки и принадлежащие пользователю отзывы.
            $stmt = $pdo->prepare('DELETE FROM registrations WHERE user_id = ?');
            $stmt->execute([$userId]);

            $stmt = $pdo->prepare('DELETE FROM reviews WHERE user_id = ?');
            $stmt->execute([$userId]);

            // Оставляем только ID (технический ключ), имя и статус.
            $stmt = $pdo->prepare(
                "UPDATE users
                 SET email = NULL,
                     password = NULL,
                     registration_date = NULL,
                     status = 'deleted'
                 WHERE id = ? AND status = 'student'"
            );
            $stmt->execute([$userId]);

            $pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('Profile deletion failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
