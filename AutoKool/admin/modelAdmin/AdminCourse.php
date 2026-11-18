
<?php

class AdminCourse
{
    public static function getCategories()
    {
        $db = new Database();

        return $db->getAll(
            "SELECT id, name
             FROM course_categories
             ORDER BY id"
        );
    }

    public static function categoryExists($id)
    {
        $db = new Database();

        return (bool) $db->getOne(
            "SELECT id
             FROM course_categories
             WHERE id = ?",
            [$id]
        );
    }


public static function createCourse($data)
{
    $db = new Database();

    $sql = "INSERT INTO courses
                (category_id, instructor_id,
                 title, description, price, duration,
                 title_en, description_en)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    return $db->executeRun($sql, [
        $data['category_id'],
        $data['instructor_id'],
        $data['title'],
        $data['description'],
        $data['price'],
        $data['duration'],
        $data['title_en'],
        $data['description_en']
    ]);
}


    // Получить все курсы
    public static function getAllCourses()
    {
        $db = new Database();

        return $db->getAll(
            "SELECT
                courses.*,
                course_categories.name AS category_name,
                instructors.name AS instructor_name
             FROM courses
             LEFT JOIN course_categories
                ON courses.category_id = course_categories.id
             LEFT JOIN instructors
                ON courses.instructor_id = instructors.id
             ORDER BY courses.id DESC"
        );
    }

    // Получить один курс
    public static function getCourseById($id)
    {
        $db = new Database();

        return $db->getOne(
            "SELECT * FROM courses WHERE id = ?",
            [$id]
        );
    }

    // Обновить курс
    public static function updateCourse($id, $data)
    {
        $db = new Database();

        return $db->executeRun(
            "UPDATE courses
             SET category_id = ?,
                 instructor_id = ?,
                 title = ?,
                 description = ?,
                 price = ?,
                 duration = ?,
                 title_en = ?,
                 description_en = ?
             WHERE id = ?",
            [
                $data['category_id'],
                $data['instructor_id'],
                $data['title'],
                $data['description'],
                $data['price'],
                $data['duration'],
                $data['title_en'],
                $data['description_en'],
                $id
            ]
        );
    }

public static function deleteCourse($id)
{
    $db = new Database();

    return $db->executeRun(
        "UPDATE courses
         SET status = 'deleted'
         WHERE id = ? AND status = 'active'",
        [$id]
    );
}

public static function restoreCourse($id)
{
    $db = new Database();

    return $db->executeRun(
        "UPDATE courses
         SET status = 'active'
         WHERE id = ? AND status = 'deleted'",
        [$id]
    );
}

}
?>
