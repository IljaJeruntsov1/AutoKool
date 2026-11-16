
<?php
require_once __DIR__ . '/../../inc/language.php';

$currentLanguage = $_SESSION['language'] ?? 'et';
$isEnglish = $currentLanguage === 'en';
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($currentLanguage) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AutoKool — Admin</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f3f6fb !important;
        }

        .admin-navbar {
            background: #142238;
            padding: 16px 0;
        }

        .admin-heading {
            font-size: 32px;
            font-weight: 750;
            color: #17243a;
        }

        .admin-subtitle {
            color: #64748b;
            font-size: 16px;
        }

        .admin-card {
            display: flex;
            align-items: center;
            gap: 20px;

            background: #ffffff;
            border: 1px solid #e6ebf2;
            border-radius: 18px;

            padding: 26px;
            height: 100%;

            text-decoration: none;
            color: #17243a;

            box-shadow: 0 4px 18px rgba(20, 34, 56, 0.04);
            transition: all 0.25s ease;
        }

        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(20, 34, 56, 0.12);
            border-color: #cdd7e5;
            color: #17243a;
        }

        .admin-icon {
            width: 64px;
            height: 64px;
            min-width: 64px;
            border-radius: 16px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 28px;
        }

        .icon-add {
            background: #dcfce7;
            color: #16a34a;
        }

        .icon-courses {
            background: #dbeafe;
            color: #2563eb;
        }

        .icon-reviews {
            background: #f3e8ff;
            color: #9333ea;
        }

        .icon-registrations {
            background: #ffedd5;
            color: #ea580c;
        }

        .icon-instructors {
            background: #fce7f3;
            color: #db2777;
        }

        .admin-card-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .admin-card-description {
            font-size: 14px;
            color: #64748b;
            margin: 0;
        }

        .admin-arrow {
            margin-left: auto;
            color: #94a3b8;
            font-size: 20px;
            transition: transform 0.25s ease;
        }

        .admin-card:hover .admin-arrow {
            transform: translateX(5px);
            color: #2563eb;
        }

        .admin-section-title {
            font-size: 19px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #334155;
        }

        @media (max-width: 576px) {
            .admin-heading {
                font-size: 26px;
            }

            .admin-card {
                padding: 20px;
                gap: 14px;
            }

            .admin-icon {
                width: 52px;
                height: 52px;
                min-width: 52px;
                font-size: 24px;
            }

            .admin-card-title {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark admin-navbar">
    <div class="container">

        <a href="index.php?page=dashboard"
           class="navbar-brand fw-bold fs-4">
            <i class="bi bi-car-front-fill me-2"></i>
            AutoKool Admin
        </a>

        <div class="d-flex align-items-center gap-3">

            <?php
            $currentPath = parse_url(
                $_SERVER['REQUEST_URI'],
                PHP_URL_PATH
            );
            ?>

            <form method="GET"
                  action="<?= htmlspecialchars($currentPath) ?>"
                  class="d-inline-flex align-items-center">

                <?php foreach ($_GET as $key => $value): ?>
                    <?php if ($key !== 'lang' && is_scalar($value)): ?>

                        <input
                            type="hidden"
                            name="<?= htmlspecialchars($key) ?>"
                            value="<?= htmlspecialchars((string)$value) ?>"
                        >

                    <?php endif; ?>
                <?php endforeach; ?>

                <select
                    name="lang"
                    class="form-select form-select-sm"
                    onchange="this.form.submit()"
                    aria-label="Choose language">

                    <option value="et"
                        <?= !$isEnglish ? 'selected' : '' ?>>
                        ET
                    </option>

                    <option value="en"
                        <?= $isEnglish ? 'selected' : '' ?>>
                        EN
                    </option>
                </select>

            </form>

            <a href="../"
               class="btn btn-outline-light">

                <i class="bi bi-arrow-left me-1"></i>

                <?= t('back') ?>

            </a>

        </div>
    </div>
</nav>

<main class="container py-5">

    <!-- Заголовок -->
    <div class="mb-5">

        <h1 class="admin-heading mb-2">
            <?= t('admin_panel') ?>
        </h1>

        <p class="admin-subtitle">
            <?= $isEnglish
                ? 'Welcome to the AutoKool administration panel! Manage your courses, instructors, students and reviews.'
                : 'Tere tulemast AutoKool administratsioonipaneeli! Halda kursuseid, sõiduõpetajaid, õpilasi ja tagasisidet.' ?>
        </p>

    </div>

    <h2 class="admin-section-title">
        <?= $isEnglish
            ? 'Management'
            : 'Haldamine' ?>
    </h2>

    <div class="row g-4">

        <!-- Добавление курса -->
        <div class="col-12 col-lg-6">

            <a href="?page=courseAdd"
               class="admin-card">

                <div class="admin-icon icon-add">
                    <i class="bi bi-plus-circle"></i>
                </div>

                <div>
                    <div class="admin-card-title">
                        <?= $isEnglish
                            ? 'Add New Course'
                            : 'Lisa uus kursus' ?>
                    </div>

                    <p class="admin-card-description">
                        <?= $isEnglish
                            ? 'Create a course and assign an instructor.'
                            : 'Loo uus kursus ja vali sõiduõpetaja.' ?>
                    </p>
                </div>

                <i class="bi bi-arrow-right admin-arrow"></i>

            </a>

        </div>

        <!-- Управление курсами -->
        <div class="col-12 col-lg-6">

            <a href="?page=courses"
               class="admin-card">

                <div class="admin-icon icon-courses">
                    <i class="bi bi-journal-bookmark"></i>
                </div>

                <div>
                    <div class="admin-card-title">
                        <?= $isEnglish
                            ? 'Manage Courses'
                            : 'Halda kursuseid' ?>
                    </div>

                    <p class="admin-card-description">
                        <?= $isEnglish
                            ? 'View, edit and delete existing courses.'
                            : 'Vaata, muuda ja kustuta olemasolevaid kursuseid.' ?>
                    </p>
                </div>

                <i class="bi bi-arrow-right admin-arrow"></i>

            </a>

        </div>

        <!-- Управление преподавателями -->
        <div class="col-12 col-lg-6">

            <a href="?page=instructors"
               class="admin-card">

                <div class="admin-icon icon-instructors">
                    <i class="bi bi-person-badge"></i>
                </div>

                <div>
                    <div class="admin-card-title">
                        <?= $isEnglish
                            ? 'Manage Instructors'
                            : 'Halda sõiduõpetajaid' ?>
                    </div>

                    <p class="admin-card-description">
                        <?= $isEnglish
                            ? 'Add instructors and their photos.'
                            : 'Lisa sõiduõpetajaid ja nende fotosid.' ?>
                    </p>
                </div>

                <i class="bi bi-arrow-right admin-arrow"></i>

            </a>

        </div>

        <div class="col-12 col-lg-6">

    <a href="?page=instructorAdd" class="admin-card">

        <div class="admin-icon icon-add">
            <i class="bi bi-person-plus"></i>
        </div>

        <div>
            <div class="admin-card-title">
                <?= $isEnglish
                    ? 'Add Instructor'
                    : 'Lisa sõiduõpetaja' ?>
            </div>

            <p class="admin-card-description">
                <?= $isEnglish
                    ? 'Create an instructor profile with a photo.'
                    : 'Lisa uus sõiduõpetaja koos fotoga.' ?>
            </p>
        </div>

        <i class="bi bi-arrow-right admin-arrow"></i>

    </a>

</div>

        <!-- Управление отзывами -->
        <div class="col-12 col-lg-6">

            <a href="?page=reviews"
               class="admin-card">

                <div class="admin-icon icon-reviews">
                    <i class="bi bi-chat-left-text"></i>
                </div>

                <div>
                    <div class="admin-card-title">
                        <?= t('manage_reviews') ?>
                    </div>

                    <p class="admin-card-description">
                        <?= $isEnglish
                            ? 'Read, edit and delete student reviews.'
                            : 'Loe, muuda ja kustuta õpilaste tagasisidet.' ?>
                    </p>
                </div>

                <i class="bi bi-arrow-right admin-arrow"></i>

            </a>

        </div>

        <!-- Регистрации учеников -->
        <div class="col-12 col-lg-6">

            <a href="?page=registrations"
               class="admin-card">

                <div class="admin-icon icon-registrations">
                    <i class="bi bi-people"></i>
                </div>

                <div>
                    <div class="admin-card-title">
                        <?= t('registrations') ?>
                    </div>

                    <p class="admin-card-description">
                        <?= $isEnglish
                            ? 'View registrations and manage students.'
                            : 'Vaata registreeringuid ja halda õpilasi.' ?>
                    </p>
                </div>

                <i class="bi bi-arrow-right admin-arrow"></i>

            </a>

        </div>

    </div>

</main>

</body>
</html>
