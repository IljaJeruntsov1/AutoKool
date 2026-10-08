
<?php

session_start();

include_once __DIR__ . '/inc/Database.php';

require_once __DIR__ . '/model/Course.php';
require_once __DIR__ . '/model/Category.php';
require_once __DIR__ . '/model/Review.php';
require_once __DIR__ . '/model/User.php';

require_once __DIR__ . '/controller/Controller.php';

require_once __DIR__ . '/route/routing.php';

echo $response;

?>