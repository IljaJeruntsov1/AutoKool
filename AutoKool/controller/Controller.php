<?php

class Controller
{
    public static function StartSite()
    {
        $arr = Course::getPopularCourses();
        include __DIR__ . '/../view/start.php';
    }

    public static function AllCategory()
    {
        $arr = Category::getAllCategory();
        include __DIR__ . '/../view/category.php';
    }

    public static function AllCourses()
    {
        $arr = Course::getAllCourses();
        include __DIR__ . '/../view/allcourses.php';
    }

    public static function CoursesByCatID($id)
    {
        $arr = Course::getCoursesByCategoryID((int)$id);
        include __DIR__ . '/../view/catcourses.php';
    }

    public static function CourseByID($id)
    {
        $courseId = filter_var($id, FILTER_VALIDATE_INT);

        if (!$courseId || $courseId < 1) {
            self::error404();
            return;
        }

        $n = Course::getCourseByID($courseId);

        if (!$n) {
            self::error404();
            return;
        }

        $reviews = Review::getReviewsByCourseID($courseId);
        $reviewCount = Review::getReviewsCountByCourseID($courseId);

        include __DIR__ . '/../view/course.php';
    }

    public static function error404()
    {
        http_response_code(404);
        include __DIR__ . '/../view/error404.php';
    }

    public static function InsertReview($text, $id)
    {
        $courseId = filter_var($id, FILTER_VALIDATE_INT);
        $text = trim($text);

        if (!$courseId || $courseId < 1 || $text === '' || mb_strlen($text) > 2000) {
            header('Location: allcourses');
            exit;
        }

        $course = Course::getCourseByID($courseId);

        if (!$course) {
            self::error404();
            return;
        }

        Review::insertReview($text, $courseId);

        header('Location: course?id=' . $courseId . '#reviewtable');
        exit;
    }

    public static function Reviews($courseid)
    {
        $arr = Review::getReviewsByCourseID((int)$courseid);
        include __DIR__ . '/../view/reviews.php';
    }

    public static function ReviewsCount($courseid)
    {
        return Review::getReviewsCountByCourseID((int)$courseid);
    }

    public static function registerForm()
    {
        include __DIR__ . '/../view/formRegister.php';
    }

    public static function registerUser()
    {
        $result = User::registerUser();
        include __DIR__ . '/../view/answerRegister.php';
    }

    // Авторизация: показать форму входа
    public static function loginForm()
    {
        $result = null;
        include __DIR__ . '/../view/login.php';
    }

    // Авторизация: обработать форму входа
    public static function loginUser()
    {
        $result = Auth::login(
            $_POST['email'] ?? '',
            $_POST['password'] ?? ''
        );

        if ($result['success']) {
            header('Location: ./');
            exit;
        }

        include __DIR__ . '/../view/login.php';
    }

    // Выход из аккаунта
    public static function logoutUser()
    {
        Auth::logout();

        header('Location: ./');
        exit;
    }
}
?>