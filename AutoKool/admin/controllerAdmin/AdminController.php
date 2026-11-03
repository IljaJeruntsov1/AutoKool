<?php

require_once dirname(__DIR__) . '/modelAdmin/AdminRegistration.php';

require_once dirname(__DIR__) . '/modelAdmin/AdminReview.php';

class AdminController
{
    public static function dashboard()
    {
        require __DIR__ . '/../viewAdmin/dashboard.php';
    }

    // Создать CSRF-токен для защиты форм
    private static function getCsrfToken()
    {
        if (empty($_SESSION['admin_csrf_token'])) {
            $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['admin_csrf_token'];
    }

    // Проверить CSRF-токен
    private static function validateCsrfToken()
    {
        $token = $_POST['csrf_token'] ?? '';

        if (
            !is_string($token) ||
            empty($_SESSION['admin_csrf_token']) ||
            !hash_equals($_SESSION['admin_csrf_token'], $token)
        ) {
            http_response_code(403);
            exit('Vigane turvakood. Laadi leht uuesti.');
        }
    }

    // Показать все комментарии
    public static function reviews()
    {
        $reviews = AdminReview::getAllReviews();
        $csrfToken = self::getCsrfToken();

        require __DIR__ . '/../viewAdmin/reviews.php';
    }

    // Сохранить изменённый комментарий
    public static function updateReview()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Meetod pole lubatud.');
        }

        self::validateCsrfToken();

        $id = filter_var(
            $_POST['review_id'] ?? null,
            FILTER_VALIDATE_INT
        );

        $text = trim($_POST['text'] ?? '');

        if (
            !$id ||
            $id < 1 ||
            $text === '' ||
            mb_strlen($text) > 2000
        ) {
            header('Location: ?page=reviews&message=invalid');
            exit;
        }

        AdminReview::updateReview($id, $text);

        header('Location: ?page=reviews&message=updated');
        exit;
    }

    // Удалить комментарий
    public static function deleteReview()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Meetod pole lubatud.');
        }

        self::validateCsrfToken();

        $id = filter_var(
            $_POST['review_id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if (!$id || $id < 1) {
            header('Location: ?page=reviews&message=invalid');
            exit;
        }

        AdminReview::deleteReview($id);

        header('Location: ?page=reviews&message=deleted');
        exit;
    }

    public static function registrations()
{
    $registrations = AdminRegistration::getAllRegistrations();
    $csrfToken = self::getCsrfToken();

    require __DIR__ . '/../viewAdmin/registrations.php';
}

public static function cancelRegistration()
{
    self::validateCsrfToken();

    $id = filter_var(
        $_POST['registration_id'] ?? null,
        FILTER_VALIDATE_INT
    );

    if (!$id || $id < 1) {
        header('Location: ?page=registrations&message=invalid');
        exit;
    }

    AdminRegistration::cancelRegistration($id);

    header('Location: ?page=registrations&message=cancelled');
    exit;
}
}
?>