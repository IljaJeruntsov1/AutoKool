
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Разрешённые языки
$allowedLanguages = ['et', 'en'];

// Сохраняем выбор языка
if (
    isset($_GET['lang']) &&
    in_array($_GET['lang'], $allowedLanguages, true)
) {
    $_SESSION['language'] = $_GET['lang'];
}

// Язык по умолчанию
$currentLanguage = $_SESSION['language'] ?? 'et';

// Словарь переводов
$translations = [

    'et' => [
        'site_name' => 'AutoKool',
        'home' => 'Avaleht',
        'courses' => 'Kursused',
        'instructors' => 'Õpetajad',
        'reviews' => 'Tagasiside',
        'login' => 'Logi sisse',
        'register' => 'Registreeru',
        'logout' => 'Logi välja',
        'admin_panel' => 'Administraatori paneel',
        'dashboard' => 'Juhtpaneel',
        'students' => 'Õpilased',
        'registrations' => 'Kursustele registreerumised',
        'manage_reviews' => 'Halda kommentaare',
        'student' => 'Õpilane',
        'email' => 'E-post',
        'course' => 'Kursus',
        'date' => 'Kuupäev',
        'status' => 'Staatus',
        'action' => 'Tegevus',
        'pending' => 'Ootel',
        'confirmed' => 'Kinnitatud',
        'cancelled' => 'Tühistatud',
        'remove_from_course' => 'Eemalda kursuselt',
        'restore_to_course' => 'Lisa kursusele tagasi',
        'back' => 'Tagasi',
        'save' => 'Salvesta',
        'delete' => 'Kustuta',
        'language' => 'Keel',
        'all_courses' => 'Kõik kursused',
        'popular_courses' => 'Populaarsed kursused',
        'price' => 'Hind',
        'duration' => 'Kestus',
        'view_course' => 'Vaata kursust'
    ],

    'en' => [
        'site_name' => 'AutoKool',
        'home' => 'Home',
        'courses' => 'Courses',
        'instructors' => 'Instructors',
        'reviews' => 'Reviews',
        'login' => 'Log in',
        'register' => 'Register',
        'logout' => 'Log out',
        'admin_panel' => 'Admin panel',
        'dashboard' => 'Dashboard',
        'students' => 'Students',
        'registrations' => 'Course registrations',
        'manage_reviews' => 'Manage reviews',
        'student' => 'Student',
        'email' => 'Email',
        'course' => 'Course',
        'date' => 'Date',
        'status' => 'Status',
        'action' => 'Action',
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'cancelled' => 'Cancelled',
        'remove_from_course' => 'Remove from course',
        'restore_to_course' => 'Restore to course',
        'back' => 'Back',
        'save' => 'Save',
        'delete' => 'Delete',
        'language' => 'Language',
        'all_courses' => 'All courses',
        'popular_courses' => 'Popular courses',
        'price' => 'Price',
        'duration' => 'Duration',
        'view_course' => 'View course'
    ]

];

// Функция получения перевода
function t($key)
{
    global $translations, $currentLanguage;

    return $translations[$currentLanguage][$key]
        ?? $translations['et'][$key]
        ?? $key;
}

?>
