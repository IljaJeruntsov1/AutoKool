<?php

class Controller
{
    public static function StartSite()
    {
        $arr = Course::getPopularCourses();
        include_once 'view/start.php';
    }

    public static function AllCategory()
    {
        $arr = Category::getAllCategory();
        include_once 'view/category.php';
    }

    public static function AllCourses()
    {
        $arr = Course::getAllCourses();
        include_once 'view/allcourses.php';
    }

    public static function CoursesByCatID($id)
    {
        $arr = Course::getCoursesByCategoryID($id);
        include_once 'view/catcourses.php';
    }


    public static function CourseByID($id)
    {
        $n = Course::getCourseByID((int)$id);

        if (!$n) {
            self::error404();
            return;
        }

        $reviews = Review::getReviewsByCourseID((int)$id);
        $reviewCount = Review::getReviewsCountByCourseID((int)$id);

        include_once __DIR__ . '/../view/course.php';
    }



    public static function error404()
    {
        include_once 'view/error404.php';
    }

    public static function InsertReview($c, $id)
    {
        Review::insertReview($c, $id);

        header('Location:course?id=' . $id . '#reviewtable');
    }

    public static function Reviews($courseid)
    {
        $arr = Review::getReviewsByCourseID($courseid);

        include_once 'view/reviews.php';
    }

    public static function ReviewsCount($courseid)
    {
        $arr = Review::getReviewsCountByCourseID($courseid);

        return $arr;
    }

    public static function registerForm()
    {
        include_once 'view/formRegister.php';
    }

    public static function registerUser()
    {
        $result = User::registerUser();

        include_once 'view/answerRegister.php';
    }
}
?>