<?php
require_once __DIR__ . '/../controllerAdmin/AdminController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'update_review': AdminController::updateReview(); break;
        case 'delete_review': AdminController::deleteReview(); break;
        case 'cancel_registration': AdminController::cancelRegistration(); break;
        case 'restore_registration': AdminController::restoreRegistration(); break;
        case 'create_course': AdminController::createCourse(); break;
        case 'update_course': AdminController::updateCourse(); break;
        case 'delete_course': AdminController::deleteCourse(); break;
        case 'create_instructor': AdminController::createInstructor(); break;
        case 'update_instructor': AdminController::updateInstructor(); break;
    case 'change_instructor_status':
    AdminController::changeInstructorStatus();
    break;
        default: http_response_code(404); exit('Toimingut ei leitud.');
    }
    exit;
}

$page = $_GET['page'] ?? 'dashboard';
switch ($page) {
    case 'dashboard': AdminController::dashboard(); break;
    case 'reviews': AdminController::reviews(); break;
    case 'registrations': AdminController::registrations(); break;
    case 'courseAdd': AdminController::courseAdd(); break;
    case 'courses': AdminController::courses(); break;
    case 'courseEdit': AdminController::courseEdit(); break;
case 'instructors':
    AdminController::instructors();
    break;

case 'instructorAdd':
    AdminController::instructorAdd();
    break;

case 'instructorEdit':
    AdminController::instructorEdit();
    break;
    default: http_response_code(404); echo '<h1>404 — Lehte ei leitud</h1>';
}
