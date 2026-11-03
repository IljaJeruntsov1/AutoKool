<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../inc/Database.php';
require_once __DIR__ . '/../model/Auth.php';

if (!Auth::isLoggedIn()) {
    header('Location: ../login');
    exit;
}

if (!Auth::isAdmin()) {
    http_response_code(403);
    exit('403 — Juurdepääs keelatud. Ainult administraatoritele.');
}


require_once __DIR__ . '/routeAdmin/routingAdmin.php';
?>