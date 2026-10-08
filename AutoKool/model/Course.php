<?php

class Course
{


    public static function getPopularCourses()
    {
        $query = "SELECT *
                  FROM courses
                  ORDER BY id DESC
                  LIMIT 3";

        $db = new Database();

        return $db->getAll($query);
    }

    public static function getAllCourses()
    {
        $query = "SELECT *
                  FROM courses
                  ORDER BY id DESC";

        $db = new Database();

        return $db->getAll($query);
    }

    public static function getCoursesByCategoryID($id)
    {
        $query = "SELECT *
                  FROM courses
                  WHERE category_id = ?
                  ORDER BY id DESC";

        $db = new Database();

        return $db->getAll($query, [$id]);
    }

    public static function getCourseByID($id)
    {
        $query = "SELECT *
                  FROM courses
                  WHERE id = ?";

        $db = new Database();

        return $db->getOne($query, [$id]);
    }
}
?>