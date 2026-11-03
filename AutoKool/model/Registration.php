<?php

class Registration
{
    public static function registerForCourse($userId, $courseId)
    {
        $db = new Database();

        // Проверяем, записан ли пользователь на этот курс
        $sql = "SELECT id
                FROM registrations
                WHERE user_id = ? AND course_id = ?
                LIMIT 1";

        $existing = $db->getOne($sql, [$userId, $courseId]);

        if ($existing) {
            return [
                'success' => false,
                'message' => 'Oled sellele kursusele juba registreeritud.'
            ];
        }

        // Сохраняем запись на курс
        $sql = "INSERT INTO registrations
                    (user_id, course_id, status)
                VALUES (?, ?, 'pending')";

        try {
            $db->executeRun($sql, [$userId, $courseId]);

            return [
                'success' => true,
                'message' => 'Kursusele registreerimine õnnestus!'
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage());

            return [
                'success' => false,
                'message' => 'Registreerimisel tekkis viga. Palun proovi uuesti.'
            ];
        }
    }

    public static function getUserRegistrations($userId)
    {
        $sql = "SELECT
                    registrations.id AS registration_id,
                    registrations.registration_date,
                    registrations.status,
                    courses.id AS course_id,
                    courses.title,
                    courses.price,
                    courses.duration
                FROM registrations
                INNER JOIN courses
                    ON registrations.course_id = courses.id
                WHERE registrations.user_id = ?
                ORDER BY registrations.registration_date DESC";

        $db = new Database();

        return $db->getAll($sql, [$userId]);
    }
}
?>