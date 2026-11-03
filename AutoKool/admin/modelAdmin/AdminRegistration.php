
<?php

class AdminRegistration
{
    // Получить все записи учеников на курсы
    public static function getAllRegistrations()
    {
        $db = new Database();

        return $db->getAll(
            "SELECT
                registrations.id,
                registrations.registration_date,
                registrations.status,
                users.username,
                users.email,
                courses.title AS course_title
             FROM registrations
             INNER JOIN users
                ON registrations.user_id = users.id
             INNER JOIN courses
                ON registrations.course_id = courses.id
             ORDER BY registrations.id DESC"
        );
    }

    // Отменить запись ученика
    public static function cancelRegistration($id)
    {
        $db = new Database();

        return $db->executeRun(
            "UPDATE registrations
             SET status = 'cancelled'
             WHERE id = ?",
            [$id]
        );
    }

    
    // Вернуть ученика на курс
    public static function restoreRegistration($id)
    {
        $db = new Database();

        return $db->executeRun(
            "UPDATE registrations
             SET status = 'pending'
             WHERE id = ? AND status = 'cancelled'",
            [$id]
        );
    }

}

?>
