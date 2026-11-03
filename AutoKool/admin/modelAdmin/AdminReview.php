<?php

class AdminReview
{
    public static function getAllReviews()
    {
        $db = new Database();

        return $db->getAll(
            "SELECT
                reviews.id,
                reviews.text,
                reviews.date AS review_date,
                users.username,
                courses.title AS course_title
             FROM reviews   
             LEFT JOIN users
                ON reviews.user_id = users.id
             INNER JOIN courses
                ON reviews.course_id = courses.id
             ORDER BY reviews.id DESC"
        );
    }

    public static function updateReview($id, $text)
    {
        $db = new Database();

        return $db->executeRun(
            "UPDATE reviews
             SET text = ?
             WHERE id = ?",
            [$text, $id]
        );
    }

    public static function deleteReview($id)
    {
        $db = new Database();

        return $db->executeRun(
            "DELETE FROM reviews
             WHERE id = ?",
            [$id]
        );
    }
}
?>