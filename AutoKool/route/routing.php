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

elseif ($path == 'insertreview' AND isset($_GET['review'], $_GET['id'])) {
    $response = Controller::InsertReview(
        $_GET['review'],
        $_GET['id']
    );
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