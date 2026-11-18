
<?php

class Profile
{
    // Данные пользователя
    public static function getUser(int $userId): ?array
    {
        $db = new Database();

        $user = $db->getOne(
            "SELECT id, username, email, password, status
             FROM users
             WHERE id = ?",
            [$userId]
        );

        return $user ?: null;
    }

    // Все курсы + статус заявки пользователя
    public static function getCourses(int $userId): array
    {
        $db = new Database();

        $courses = $db->getAll(
            "SELECT
                c.id,
                c.title,
                c.title_en,
                c.price,
                c.duration,
                r.status AS registration_status
             FROM courses c
             LEFT JOIN registrations r
                ON r.course_id = c.id
                AND r.user_id = ?
             ORDER BY c.id DESC",
            [$userId]
        );

        return $courses ?: [];
    }

    // Удалить персональные данные аккаунта
    public static function eraseAccount(
        int $userId,
        string $password
    ): bool {
        $db = new Database();

        // Метод connect() должен возвращать PDO.
        $pdo = $db->connect();

        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare(
                "SELECT password, status
                 FROM users
                 WHERE id = ?
                 FOR UPDATE"
            );

            $stmt->execute([$userId]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (
                !$user ||
                $user['status'] !== 'student' ||
                !is_string($user['password']) ||
                !password_verify(
                    $password,
                    $user['password']
                )
            ) {
                $pdo->rollBack();
                return false;
            }

            // Удаляем заявки пользователя
            $stmt = $pdo->prepare(
                "DELETE FROM registrations
                 WHERE user_id = ?"
            );
            $stmt->execute([$userId]);

            // Удаляем отзывы пользователя
            $stmt = $pdo->prepare(
                "DELETE FROM reviews
                 WHERE user_id = ?"
            );
            $stmt->execute([$userId]);

            // Сохраняем только ID, username, status
            $stmt = $pdo->prepare(
                "UPDATE users
                 SET email = NULL,
                     password = NULL,
                     registration_date = NULL,
                     status = 'deleted'
                 WHERE id = ?
                   AND status = 'student'"
            );

            $stmt->execute([$userId]);

            if ($stmt->rowCount() !== 1) {
                $pdo->rollBack();
                return false;
            }

            $pdo->commit();
            return true;

        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            error_log($e->getMessage());
            throw $e;
        }
    }
}
