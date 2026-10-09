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
    $reviewText = trim($_POST['review']);
    $courseId = filter_var($_POST['id'], FILTER_VALIDATE_INT);

    if ($courseId && $reviewText !== '' && mb_strlen($reviewText) <= 2000) {
        Controller::InsertReview($reviewText, $courseId);
    } else {
        header('Location: allcourses');
        exit;
    }
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

else {
    $response = Controller::error404();
}

?>