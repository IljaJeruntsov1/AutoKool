<?php

session_start();

include_once 'inc/Database.php';

require_once 'model/Course.php';
require_once 'model/Category.php';
require_once 'model/Review.php';
require_once 'model/User.php';

require_once 'view/Course.php';
require_once 'view/Reviews.php';

require_once 'controller/Controller.php';

require_once 'route/routing.php';

echo $response;

?>