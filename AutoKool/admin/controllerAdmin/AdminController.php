<?php

require_once dirname(__DIR__) . '/modelAdmin/AdminRegistration.php';
require_once dirname(__DIR__) . '/modelAdmin/AdminReview.php';
require_once dirname(__DIR__) . '/modelAdmin/AdminCourse.php';
require_once dirname(__DIR__) . '/modelAdmin/AdminInstructor.php';

class AdminController
{
    public static function dashboard()
    {
        require __DIR__ . '/../viewAdmin/dashboard.php';
    }

    private static function getCsrfToken()
    {
        if (empty($_SESSION['admin_csrf_token'])) {
            $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['admin_csrf_token'];
    }

    private static function validateCsrfToken()
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!is_string($token) || empty($_SESSION['admin_csrf_token']) ||
            !hash_equals($_SESSION['admin_csrf_token'], $token)) {
            http_response_code(403);
            exit('Vigane turvakood. Laadi leht uuesti.');
        }
    }

    private static function requirePost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method not allowed');
        }
    }

    private static function validId($value)
    {
        $id = filter_var($value, FILTER_VALIDATE_INT);
        return ($id !== false && $id !== null && $id > 0) ? $id : null;
    }

    // Returns null when no photo was uploaded; otherwise returns validated image bytes.
    // Throws InvalidArgumentException for an invalid image.
    private static function uploadedInstructorPhoto()
    {
        if (!isset($_FILES['picture']) ||
            $_FILES['picture']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES['picture'];
        if ($file['error'] !== UPLOAD_ERR_OK ||
            $file['size'] <= 0 ||
            $file['size'] > 2 * 1024 * 1024 ||
            !is_uploaded_file($file['tmp_name'])) {
            throw new InvalidArgumentException('Invalid uploaded photo');
        }

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true) ||
            @getimagesize($file['tmp_name']) === false) {
            throw new InvalidArgumentException('Invalid photo format');
        }

        $bytes = file_get_contents($file['tmp_name']);
        if ($bytes === false) {
            throw new InvalidArgumentException('Could not read photo');
        }
        return $bytes;
    }

    // REVIEWS
    public static function reviews()
    {
        $reviews = AdminReview::getAllReviews();
        $csrfToken = self::getCsrfToken();
        require __DIR__ . '/../viewAdmin/reviews.php';
    }

    public static function updateReview()
    {
        self::requirePost();
        self::validateCsrfToken();
        $id = self::validId($_POST['review_id'] ?? null);
        $text = trim($_POST['text'] ?? '');
        if (!$id || $text === '' || mb_strlen($text) > 2000) {
            header('Location: ?page=reviews&message=invalid');
            exit;
        }
        AdminReview::updateReview($id, $text);
        header('Location: ?page=reviews&message=updated');
        exit;
    }

    public static function deleteReview()
    {
        self::requirePost();
        self::validateCsrfToken();
        $id = self::validId($_POST['review_id'] ?? null);
        if (!$id) {
            header('Location: ?page=reviews&message=invalid');
            exit;
        }
        AdminReview::deleteReview($id);
        header('Location: ?page=reviews&message=deleted');
        exit;
    }

    // REGISTRATIONS
    public static function registrations()
    {
        $registrations = AdminRegistration::getAllRegistrations();
        $csrfToken = self::getCsrfToken();
        require __DIR__ . '/../viewAdmin/registrations.php';
    }

    public static function cancelRegistration()
    {
        self::requirePost();
        self::validateCsrfToken();
        $id = self::validId($_POST['registration_id'] ?? null);
        if (!$id) {
            header('Location: ?page=registrations&message=invalid');
            exit;
        }
        AdminRegistration::cancelRegistration($id);
        header('Location: ?page=registrations&message=cancelled');
        exit;
    }

    public static function restoreRegistration()
    {
        self::requirePost();
        self::validateCsrfToken();
        $id = self::validId($_POST['registration_id'] ?? null);
        if (!$id) {
            header('Location: ?page=registrations&message=invalid');
            exit;
        }
        AdminRegistration::restoreRegistration($id);
        header('Location: ?page=registrations&message=restored');
        exit;
    }

    // COURSES
    public static function courses()
    {
        $courses = AdminCourse::getAllCourses();
        $csrfToken = self::getCsrfToken();
        require __DIR__ . '/../viewAdmin/courses.php';
    }

    public static function courseAdd()
    {
        $categories = AdminCourse::getCategories();
        $instructors = AdminInstructor::getActive();
        $csrfToken = self::getCsrfToken();
        require __DIR__ . '/../viewAdmin/courseAdd.php';
    }

    private static function courseFormData($editing = false)
    {
        $categoryId = self::validId($_POST['category_id'] ?? null);
        $instructorId = self::validId($_POST['instructor_id'] ?? null);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $titleEn = trim($_POST['title_en'] ?? '');
        $descriptionEn = trim($_POST['description_en'] ?? '');
        $duration = trim($_POST['duration'] ?? '');
        $price = str_replace(',', '.', trim((string)($_POST['price'] ?? '')));

        if (!$categoryId || !$instructorId ||
            $title === '' || mb_strlen($title) > 200 ||
            $description === '' || $duration === '' ||
            mb_strlen($duration) > 100 || mb_strlen($titleEn) > 255 ||
            !preg_match('/^\d{1,8}(\.\d{1,2})?$/', $price) ||
            !AdminCourse::categoryExists($categoryId) ||
            !AdminInstructor::exists($instructorId)) {
            return null;
        }

        // Existing courses may keep an inactive instructor, but new ones cannot.
        if (!$editing) {
            $active = AdminInstructor::getActive();
            $found = false;
            foreach ($active as $item) {
                if ((int)$item['id'] === $instructorId) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                return null;
            }
        }

        return [
            'category_id' => $categoryId,
            'instructor_id' => $instructorId,
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'duration' => $duration,
            'title_en' => $titleEn !== '' ? $titleEn : null,
            'description_en' => $descriptionEn !== '' ? $descriptionEn : null
        ];
    }

    public static function createCourse()
    {
        self::requirePost();
        self::validateCsrfToken();
        $data = self::courseFormData();
        if ($data === null) {
            header('Location: ?page=courseAdd&message=invalid');
            exit;
        }
        try {
            $success = AdminCourse::createCourse($data);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $success = false;
        }
        header('Location: ?page=courseAdd&message=' . ($success ? 'success' : 'error'));
        exit;
    }

    public static function courseEdit()
    {
        $id = self::validId($_GET['id'] ?? null);
        $course = $id ? AdminCourse::getCourseById($id) : null;
        if (!$course) {
            http_response_code(404);
            exit('Kursust ei leitud.');
        }
        $categories = AdminCourse::getCategories();
        $instructors = AdminInstructor::getAll();
        $csrfToken = self::getCsrfToken();
        require __DIR__ . '/../viewAdmin/courseEdit.php';
    }

    public static function updateCourse()
    {
        self::requirePost();
        self::validateCsrfToken();
        $id = self::validId($_POST['course_id'] ?? null);
        $data = self::courseFormData(true);
        if (!$id || !$data) {
            header('Location: ?page=courses&message=invalid');
            exit;
        }
        if (!AdminCourse::getCourseById($id)) {
            http_response_code(404);
            exit('Kursust ei leitud.');
        }
        try {
            $success = AdminCourse::updateCourse($id, $data);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $success = false;
        }
        header('Location: ?page=courses&message=' . ($success ? 'updated' : 'error'));
        exit;
    }

    public static function deleteCourse()
    {
        self::requirePost();
        self::validateCsrfToken();
        $id = self::validId($_POST['course_id'] ?? null);
        if (!$id) {
            header('Location: ?page=courses&message=invalid');
            exit;
        }
        if (!AdminCourse::getCourseById($id)) {
            http_response_code(404);
            exit('Kursust ei leitud.');
        }
        try {
            $success = AdminCourse::deleteCourse($id);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $success = false;
        }
        header('Location: ?page=courses&message=' . ($success ? 'deleted' : 'error'));
        exit;
    }

    // INSTRUCTORS
    public static function instructors()
    {
        $instructors = AdminInstructor::getAll();
        $csrfToken = self::getCsrfToken();
        require __DIR__ . '/../viewAdmin/instructors.php';
    }

    public static function instructorAdd()
    {
        $csrfToken = self::getCsrfToken();
        require __DIR__ . '/../viewAdmin/instructorAdd.php';
    }

    public static function createInstructor()
    {
        self::requirePost();
        self::validateCsrfToken();
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if ($name === '' || mb_strlen($name) > 150 ||
            mb_strlen($email) > 150 || mb_strlen($phone) > 30 ||
            ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL))) {
            header('Location: ?page=instructorAdd&message=invalid');
            exit;
        }
        try {
            $picture = self::uploadedInstructorPhoto();
        } catch (InvalidArgumentException $e) {
            header('Location: ?page=instructorAdd&message=invalid');
            exit;
        }
        try {
            $success = AdminInstructor::create([
                'name' => $name,
                'email' => $email !== '' ? $email : null,
                'phone' => $phone !== '' ? $phone : null,
                'description' => $description !== '' ? $description : null,
                'picture' => $picture
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $success = false;
        }
        header('Location: ?page=instructorAdd&message=' . ($success ? 'success' : 'error'));
        exit;
    }

    public static function instructorEdit()
    {
        $id = self::validId($_GET['id'] ?? null);
        $instructor = $id ? AdminInstructor::getById($id) : null;
        if (!$instructor) {
            http_response_code(404);
            exit('Sõiduõpetajat ei leitud.');
        }
        $csrfToken = self::getCsrfToken();
        require __DIR__ . '/../viewAdmin/instructorEdit.php';
    }

    public static function updateInstructor()
    {
        self::requirePost();
        self::validateCsrfToken();
        $id = self::validId($_POST['instructor_id'] ?? null);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if (!$id || $name === '' || mb_strlen($name) > 150 ||
            mb_strlen($email) > 150 || mb_strlen($phone) > 30 ||
            ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL))) {
            header('Location: ?page=instructors&message=invalid');
            exit;
        }
        if (!AdminInstructor::exists($id)) {
            http_response_code(404);
            exit('Sõiduõpetajat ei leitud.');
        }
        try {
            $picture = self::uploadedInstructorPhoto();
        } catch (InvalidArgumentException $e) {
            header('Location: ?page=instructorEdit&id=' . $id . '&message=invalid');
            exit;
        }
        try {
            $success = AdminInstructor::update($id, [
                'name' => $name,
                'email' => $email !== '' ? $email : null,
                'phone' => $phone !== '' ? $phone : null,
                'description' => $description !== '' ? $description : null,
                'picture' => $picture // null preserves existing photo in AdminInstructor::update
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $success = false;
        }
        header('Location: ?page=instructors&message=' . ($success ? 'updated' : 'error'));
        exit;
    }

    // Soft delete: status changes instead of removing instructor from database.
    public static function changeInstructorStatus()
    {
        self::requirePost();
        self::validateCsrfToken();
        $id = self::validId($_POST['instructor_id'] ?? null);
        $status = $_POST['status'] ?? '';
        if (!$id || !is_string($status) ||
            !in_array($status, ['active', 'inactive'], true)) {
            header('Location: ?page=instructors&message=invalid');
            exit;
        }
        if (!AdminInstructor::exists($id)) {
            http_response_code(404);
            exit('Sõiduõpetajat ei leitud.');
        }
        try {
            $success = AdminInstructor::changeStatus($id, $status);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $success = false;
        }
        header('Location: ?page=instructors&message=' .
            ($success ? ($status === 'active' ? 'activated' : 'deactivated') : 'error'));
        exit;
    }

    public static function restoreCourse()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Method not allowed');
    }

    self::validateCsrfToken();

    $id = filter_var(
        $_POST['course_id'] ?? null,
        FILTER_VALIDATE_INT
    );

    if (!$id || $id < 1) {
        header('Location: ?page=courses&message=invalid');
        exit;
    }

    if (!AdminCourse::getCourseById($id)) {
        http_response_code(404);
        exit('Kursust ei leitud.');
    }

    try {
        $success = AdminCourse::restoreCourse($id);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $success = false;
    }

    header(
        'Location: ?page=courses&message=' .
        ($success ? 'restored' : 'error')
    );
    exit;
}
}
