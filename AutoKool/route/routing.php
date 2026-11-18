<?php

$host = explode('?', $_SERVER['REQUEST_URI'])[0];
$num = substr_count($host, '/');
$path = explode('/', $host)[$num];

if ($path == '' OR $path == 'index' OR $path == 'index.php') {
    $response = Controller::StartSite();
}

elseif ($path == 'allcourses') {
    $response = Controller::AllCourses();
}

elseif ($path == 'category' AND isset($_GET['id'])) {
    $response = Controller::CoursesByCatID($_GET['id']);
}

elseif ($path == 'course' AND isset($_GET['id'])) {
    $response = Controller::CourseByID($_GET['id']);
}

elseif (
    $path == 'insertreview'
    && $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['review'], $_POST['id'])
) {
    $user = Auth::currentUser();
    $courseId = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    if (!$courseId || $courseId < 1) {
        header('Location: allcourses');
        exit;
    }

    $token = $_POST['csrf_token'] ?? '';
    if (!$user || ($user['status'] ?? '') !== 'student') {
        $_SESSION['review_login_required'] = true;
        $_SESSION['return_to'] = 'course?id=' . $courseId . '#reviewtable';
        header('Location: course?id=' . $courseId . '#reviewtable');
        exit;
    }
    if (!is_string($token) || empty($_SESSION['review_csrf_token']) ||
        !hash_equals($_SESSION['review_csrf_token'], $token)) {
        http_response_code(403);
        exit('Invalid security token');
    }

    $reviewText = trim($_POST['review']);
    if ($courseId && $reviewText !== '' && mb_strlen($reviewText) <= 2000) {
        Controller::InsertReview($reviewText, $courseId);
    } else {
        header('Location: allcourses');
        exit;
    }
}

elseif ($path == 'login' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    Controller::loginForm();
}

elseif ($path == 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    Controller::loginUser();
}

elseif ($path == 'logout' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    Controller::logoutUser();
}


elseif ($path == 'reviews' AND isset($_GET['id'])) {
    $response = Controller::Reviews($_GET['id']);
}

elseif ($path == 'registerForm') {
    $control = new Controller();
    $response = $control->registerForm();
}

elseif ($path == 'registerAnswer') {
    $control = new Controller();
    $response = $control->registerUser();
}
elseif (
    $path === 'enroll'
    && $_SERVER['REQUEST_METHOD'] === 'POST'
) {
    Controller::enrollCourse();
}

elseif (
    $path === 'cancelenroll'
    && $_SERVER['REQUEST_METHOD'] === 'POST'
) {
    Controller::cancelEnrollment();
}
elseif (
    $path === 'restoreenroll'
    && $_SERVER['REQUEST_METHOD'] === 'POST'
) {
    Controller::restoreEnrollment();
}

else {
    $response = Controller::error404();
}

?>
