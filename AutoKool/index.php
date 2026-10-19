
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/inc/Database.php';

require_once __DIR__ . '/model/Auth.php';
require_once __DIR__ . '/model/Course.php';
require_once __DIR__ . '/model/Category.php';
require_once __DIR__ . '/model/Review.php';
require_once __DIR__ . '/model/User.php';

require_once __DIR__ . '/controller/Controller.php';

$response = '';
require_once __DIR__ . '/route/routing.php';

if (isset($response)) {
    echo $response;
}
?>
