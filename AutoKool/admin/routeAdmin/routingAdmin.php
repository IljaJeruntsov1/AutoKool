
<?php

require_once __DIR__ . '/../controllerAdmin/AdminController.php';

// Обработка POST-запросов
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    switch ($action) {

        case 'update_review':
            AdminController::updateReview();
            break;

        case 'delete_review':
            AdminController::deleteReview();
            break;

        case 'cancel_registration':
            AdminController::cancelRegistration();
            break;

        default:
            http_response_code(404);
            echo 'Toimingut ei leitud.';
            break;
    }

    exit;
}

// Обычные страницы
$page = $_GET['page'] ?? 'dashboard';

switch ($page) {

    case 'dashboard':
        AdminController::dashboard();
        break;

    case 'reviews':
        AdminController::reviews();
        break;

    case 'registrations':
        AdminController::registrations();
        break;

    default:
        http_response_code(404);
        echo '<h1>404 — Lehte ei leitud</h1>';
        break;
}

?>
