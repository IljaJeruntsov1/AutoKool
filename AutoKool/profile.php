<?php
// Личный кабинет: самостоятельная точка входа, не требует правок routing.php.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/inc/Database.php';
require_once __DIR__ . '/model/Auth.php';
require_once __DIR__ . '/model/Profile.php';
require_once __DIR__ . '/inc/language.php';

if (!Auth::isLoggedIn()) {
    if (($_GET['deleted'] ?? '') === '1') {
        $isEnglish = ($_SESSION['language'] ?? 'et') === 'en';
        $content = '<div class="alert alert-success mt-4">' .
            ($isEnglish
                ? 'Your profile data has been erased. Only your name and deleted status remain.'
                : 'Sinu profiili andmed on kustutatud. Alles jäid ainult nimi ja kustutatud staatus.') .
            '</div><a href="./" class="btn btn-primary">' .
            ($isEnglish ? 'Home' : 'Avaleht') . '</a>';
        require __DIR__ . '/view/layout.php';
        exit;
    }
    header('Location: login');
    exit;
}

$userId = (int)(Auth::currentUser()['id'] ?? 0);
$user = Profile::getUser($userId);
if (!$user || $user['status'] === 'deleted') {
    Auth::logout();
    header('Location: login');
    exit;
}

if (empty($_SESSION['profile_csrf_token'])) {
    $_SESSION['profile_csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($user['status'] !== 'student') {
        http_response_code(403);
        exit('Forbidden');
    }
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) ||
        !hash_equals($_SESSION['profile_csrf_token'], $token)) {
        http_response_code(403);
        exit('Invalid security token');
    }
    if (($_POST['action'] ?? '') !== 'delete_profile') {
        http_response_code(400);
        exit('Invalid action');
    }
    $password = $_POST['password'] ?? '';
    if (!is_string($password) || $password === '') {
        $error = 'password';
    } else {
        try {
            if (Profile::eraseAccount($userId, $password)) {
                Auth::logout();
                unset($_SESSION['profile_csrf_token']);
                header('Location: profile.php?deleted=1');
                exit;
            }
            $error = 'password';
        } catch (Throwable $e) {
            error_log('AutoKool profile: ' . $e->getMessage());
            $error = 'server';
        }
    }
}

$courses = Profile::getCourses($userId);
require __DIR__ . '/view/profile.php';
