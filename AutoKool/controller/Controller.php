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



        $myRegistration = null;

        if (!empty($_SESSION['user']['id']) && !empty($n['id'])) {
            $myRegistration = Registration::getUserRegistration(
                (int)$_SESSION['user']['id'],
                (int)$n['id']
            );
        }

        if (empty($_SESSION['enrollment_csrf_token'])) {
            $_SESSION['enrollment_csrf_token'] = bin2hex(
                random_bytes(32)
            );
        }
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
            $returnTo = $_SESSION['return_to'] ?? './';

            unset($_SESSION['return_to']);

            // Разрешаем перенаправление только на внутренние страницы
            if (
                !is_string($returnTo) ||
                $returnTo === '' ||
                str_starts_with($returnTo, '/') ||
                str_contains($returnTo, '://') ||
                str_contains($returnTo, '\\')
            ) {
                $returnTo = './';
            }

            header('Location: ' . $returnTo);
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

    public static function enrollCourse()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: allcourses');
        exit;
    }

    $courseId = filter_var(
        $_POST['course_id'] ?? null,
        FILTER_VALIDATE_INT
    );

    if (!$courseId || $courseId < 1) {
        header('Location: allcourses');
        exit;
    }

    // Проверяем существование курса
    $course = Course::getCourseByID($courseId);

    if (!$course) {
        self::error404();
        return;
    }

    // Если пользователь не авторизован,
    // запоминаем курс и отправляем его на вход
    if (!Auth::isLoggedIn()) {
        $_SESSION['return_to'] = 'course?id=' . $courseId;

        header('Location: login');
        exit;
    }

    $user = Auth::currentUser();

    $result = Registration::registerForCourse(
        $user['id'],
        $courseId
    );

    $status = $result['success'] ? 'success' : 'error';

    $_SESSION['enrollment_message'] = $result['message'];

    header(
        'Location: course?id=' . $courseId .
        '&enrollment=' . $status . '#enrollment'
    );

    exit;
}


    public static function cancelEnrollment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method not allowed');
        }

        if (
            empty($_SESSION['user']) ||
            empty($_SESSION['user']['id'])
        ) {
            header('Location: login');
            exit;
        }

        $token = $_POST['csrf_token'] ?? '';

        if (
            !is_string($token) ||
            empty($_SESSION['enrollment_csrf_token']) ||
            !hash_equals(
                $_SESSION['enrollment_csrf_token'],
                $token
            )
        ) {
            http_response_code(403);
            exit('Invalid security token');
        }

        $courseId = filter_var(
            $_POST['course_id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if (!$courseId || $courseId < 1) {
            http_response_code(400);
            exit('Invalid course');
        }

        $userId = (int)$_SESSION['user']['id'];

        $registration = Registration::getUserRegistration(
            $userId,
            $courseId
        );

        if (
            !$registration ||
            $registration['status'] !== 'pending'
        ) {
            $_SESSION['enrollment_message'] =
                'Seda registreeringut ei saa tühistada.';

            header(
                'Location: course?id=' . $courseId .
                '&enrollment=error'
            );
            exit;
        }

        try {
            Registration::cancelUserRegistration(
                $userId,
                $courseId
            );

            $_SESSION['enrollment_message'] =
                'Registreering on edukalt tühistatud.';

            header(
                'Location: course?id=' . $courseId .
                '&enrollment=cancelled'
            );
            exit;

        } catch (PDOException $e) {
            error_log($e->getMessage());

            $_SESSION['enrollment_message'] =
                'Registreeringu tühistamine ebaõnnestus.';

            header(
                'Location: course?id=' . $courseId .
                '&enrollment=error'
            );
            exit;
        }
    }


public static function restoreEnrollment()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Method not allowed');
    }

    if (empty($_SESSION['user']['id'])) {
        header('Location: login');
        exit;
    }

    $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token) ||
        empty($_SESSION['enrollment_csrf_token']) ||
        !hash_equals(
            $_SESSION['enrollment_csrf_token'],
            $token
        )
    ) {
        http_response_code(403);
        exit('Invalid security token');
    }

    $courseId = filter_var(
        $_POST['course_id'] ?? null,
        FILTER_VALIDATE_INT
    );

    if (!$courseId || $courseId < 1) {
        http_response_code(400);
        exit('Invalid course');
    }

    $userId = (int)$_SESSION['user']['id'];

    $registration = Registration::getUserRegistration(
        $userId,
        $courseId
    );

    if (
        !$registration ||
        $registration['status'] !== 'cancelled'
    ) {
        header(
            'Location: course?id=' . $courseId .
            '&enrollment=error'
        );
        exit;
    }

    try {
        Registration::restoreUserRegistration(
            $userId,
            $courseId
        );

        $_SESSION['enrollment_message'] =
            ($_SESSION['language'] ?? 'et') === 'en'
                ? 'You have successfully applied again!'
                : 'Oled edukalt uuesti kursusele registreerunud!';

        header(
            'Location: course?id=' . $courseId .
            '&enrollment=success'
        );
        exit;

    } catch (PDOException $e) {
        error_log($e->getMessage());

        $_SESSION['enrollment_message'] =
            'Registreerimine ebaõnnestus.';

        header(
            'Location: course?id=' . $courseId .
            '&enrollment=error'
        );
        exit;
    }
}


}
?>