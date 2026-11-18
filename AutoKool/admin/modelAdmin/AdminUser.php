<?php

class AdminUser
{
    public static function getDeletedProfiles(): array
    {
        $db = new Database();

        return $db->getAll(
            "SELECT id, username
             FROM users
             WHERE status = 'deleted'
             ORDER BY username, id"
        ) ?: [];
    }

    public static function restoreProfile(int $id, string $email, string $password): bool
    {
        $db = new Database();

        $pdo = $db->connect();
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare(
            "UPDATE users
             SET email = ?, password = ?, registration_date = CURDATE(), status = 'student'
             WHERE id = ? AND status = 'deleted'"
        );

        $stmt->execute([$email, $passwordHash, $id]);
        return $stmt->rowCount() === 1;
    }
}
