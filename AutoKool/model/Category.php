<?php

class Category
{
    public static function getAllCategory()
    {
        $query = "SELECT *
                  FROM course_categories
                  ORDER BY id";

        $db = new Database();

        return $db->getAll($query);
    }
}
?>