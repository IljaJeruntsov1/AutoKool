<?php

class Review
{
    public static function userCanPostReview(int $userId): bool
    {
        $db = new Database();
        $row = $db->getOne(
            "SELECT COUNT(*) AS review_count
             FROM reviews
             WHERE user_id = ?
               AND date >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 HOUR)",
            [$userId]
        );

        return (int)($row['review_count'] ?? 0) < 3;
    }

    public static function insertReview($text, $courseId, $userId = null)
    {
        $query = "INSERT INTO reviews
                  (course_id, user_id, text, date)
                  VALUES (?, ?, ?, CURRENT_TIMESTAMP)";

        $db = new Database();

        return $db->executeRun($query, [
            $courseId,
            $userId,
            $text
        ]);
    }

    public static function getReviewsByCourseID($courseId)
    {
        $query = "SELECT reviews.*, users.username
                  FROM reviews
                  LEFT JOIN users ON reviews.user_id = users.id
                  WHERE reviews.course_id = ?
                  ORDER BY reviews.id DESC";

        $db = new Database();

        return $db->getAll($query, [$courseId]);
    }

    public static function getReviewsCountByCourseID($courseId)
    {
        $query = "SELECT COUNT(id) AS count
                  FROM reviews
                  WHERE course_id = ?";

        $db = new Database();

        return $db->getOne($query, [$courseId]);
    }
}
?>
