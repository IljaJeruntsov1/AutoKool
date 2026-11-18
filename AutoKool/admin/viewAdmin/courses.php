
<?php
require_once __DIR__ . '/../../inc/language.php';

$lang = $_SESSION['language'] ?? 'et';
$isEnglish = $lang === 'en';

function courseEscape($value)
{
    return htmlspecialchars(
        (string)($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

$message = $_GET['message'] ?? '';

$messages = [
    'updated' => $isEnglish
        ? 'Course updated successfully!'
        : 'Kursus on edukalt muudetud!',

    'deleted' => $isEnglish
        ? 'Course status changed to deleted!'
        : 'Kursuse staatus on muudetud kustutatuks!',

    'restored' => $isEnglish
        ? 'Course restored successfully!'
        : 'Kursus on edukalt taastatud!',

    'invalid' => $isEnglish
        ? 'Invalid course data.'
        : 'Vigased kursuse andmed.',

    'error' => $isEnglish
        ? 'Operation failed.'
        : 'Toiming ebaõnnestus.'
];

$successMessages = [
    'updated',
    'deleted',
    'restored'
];
?>

<!DOCTYPE html>
<html lang="<?= $isEnglish ? 'en' : 'et' ?>">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>
        AutoKool —
        <?= $isEnglish ? 'Manage Courses' : 'Halda kursuseid' ?>
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f3f6fb;
            min-height: 100vh;
        }

        .admin-navbar {
            background: #142238;
            padding: 15px 0;
        }

        .page-card {
            border: none;
            border-radius: 18px;
            overflow: hidden;
        }

        .page-title {
            font-weight: 700;
            color: #17243a;
        }

        .btn {
            min-height: 40px;
            height: auto;
            line-height: 1.4;
            border-radius: 10px;
            font-weight: 600;
            display: inline-flex;
            justify-content: center;
            align-items: center;
        }

        .btn.btn-danger {
            background: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .btn.btn-danger:hover {
            background: #bb2d3b;
            border-color: #bb2d3b;
            color: white;
        }

        .btn.btn-success {
            background: #16a34a;
            border-color: #16a34a;
            color: white;
        }

        .btn.btn-success:hover {
            background: #15803d;
            border-color: #15803d;
            color: white;
        }

        .status-badge {
            font-size: 13px;
            padding: 8px 12px;
            border-radius: 8px;
        }

        .deleted-row {
            opacity: 0.7;
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .table th {
            color: #475569;
            font-weight: 600;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .course-name {
            font-weight: 600;
            min-width: 140px;
        }

        /* Мобильные карточки */
        @media (max-width: 767.98px) {

            .container {
                padding-left: 14px;
                padding-right: 14px;
            }

            .page-title {
                font-size: 25px;
            }

            .page-card .card-body {
                padding: 14px;
            }

            .table-responsive {
                overflow: visible;
            }

            .table {
                display: block;
                width: 100%;
            }

            .table thead {
                display: none;
            }

            .table tbody {
                display: block;
                width: 100%;
            }

            .table tr {
                display: block;
                background: white;
                border: 1px solid #e2e8f0;
                border-radius: 14px;
                padding: 14px;
                margin-bottom: 15px;
                box-shadow: 0 3px 12px rgba(0,0,0,0.04);
            }

            .table td {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 12px;
                width: 100%;
                padding: 10px 0;
                border-bottom: 1px solid #edf0f4;
                text-align: right;
                white-space: normal;
                overflow-wrap: anywhere;
            }

            .table td::before {
                content: attr(data-label);
                flex: 0 0 36%;
                font-weight: 700;
                color: #475569;
                text-align: left;
            }

            .table td:last-child {
                border-bottom: none;
            }

            .course-name {
                min-width: 0;
            }

            .action-buttons {
                justify-content: flex-end;
                width: 100%;
            }
        }

        @media (max-width: 400px) {
            .table td {
                flex-direction: column;
                align-items: stretch;
                text-align: left;
                gap: 6px;
            }

            .table td::before {
                flex: none;
            }

            .action-buttons {
                justify-content: stretch;
            }

            .action-buttons > a,
            .action-buttons > form {
                flex: 1;
            }

            .action-buttons form button {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark admin-navbar">
    <div class="container">

        <a
            href="index.php?page=dashboard"
            class="navbar-brand fw-bold"
        >
            AutoKool Admin
        </a>

        <a
            href="index.php?page=dashboard"
            class="btn btn-outline-light"
        >
            <?= $isEnglish ? 'Back' : 'Tagasi' ?>
        </a>

    </div>
</nav>

<main class="container py-5" style="max-width:1200px;">

    <!-- Header -->
    <div class="d-flex align-items-center
                justify-content-between flex-wrap
                gap-3 mb-4">

        <div>
            <h1 class="page-title h2 mb-2">
                <?= $isEnglish
                    ? 'Manage Courses'
                    : 'Halda kursuseid' ?>
            </h1>

            <p class="text-muted mb-0">
                <?= $isEnglish
                    ? 'View, edit, deactivate and restore courses.'
                    : 'Vaata, muuda, kustuta ja taasta kursuseid.' ?>
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">

            <a
                href="index.php?page=courseAdd"
                class="btn btn-success"
            >
                + <?= $isEnglish
                    ? 'Add Course'
                    : 'Lisa kursus' ?>
            </a>

            <a
                href="index.php?page=dashboard"
                class="btn btn-secondary"
            >
                <?= $isEnglish ? 'Back' : 'Tagasi' ?>
            </a>

        </div>

    </div>

    <!-- Messages -->
    <?php if (isset($messages[$message])): ?>

        <div class="alert <?= in_array(
            $message,
            $successMessages,
            true
        ) ? 'alert-success' : 'alert-danger' ?>">

            <?= courseEscape($messages[$message]) ?>

        </div>

    <?php endif; ?>

    <!-- Courses -->
    <div class="card page-card shadow-sm">

        <div class="card-body p-4">

            <?php if (empty($courses)): ?>

                <p class="text-muted mb-0">
                    <?= $isEnglish
                        ? 'No courses found.'
                        : 'Kursuseid ei leitud.' ?>
                </p>

            <?php else: ?>

                <div class="table-responsive">

                    <table class="table table-striped align-middle">

                        <thead>
                            <tr>
                                <th>ID</th>

                                <th>
                                    <?= $isEnglish
                                        ? 'Course'
                                        : 'Kursus' ?>
                                </th>

                                <th>
                                    <?= $isEnglish
                                        ? 'Category'
                                        : 'Kategooria' ?>
                                </th>

                                <th>
                                    <?= $isEnglish
                                        ? 'Instructor'
                                        : 'Sõiduõpetaja' ?>
                                </th>

                                <th>
                                    <?= $isEnglish
                                        ? 'Price'
                                        : 'Hind' ?>
                                </th>

                                <th>
                                    <?= $isEnglish
                                        ? 'Duration'
                                        : 'Kestus' ?>
                                </th>

                                <th>
                                    <?= $isEnglish
                                        ? 'Status'
                                        : 'Staatus' ?>
                                </th>

                                <th>
                                    <?= $isEnglish
                                        ? 'Actions'
                                        : 'Tegevused' ?>
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($courses as $course): ?>

                            <?php
                            $isActive = (
                                ($course['status'] ?? 'active')
                                === 'active'
                            );

                            $courseTitle = $isEnglish &&
                                !empty($course['title_en'])
                                    ? $course['title_en']
                                    : $course['title'];
                            ?>

                            <tr class="<?= $isActive ? '' : 'deleted-row' ?>">

                                <!-- ID -->
                                <td data-label="ID">
                                    <?= (int)$course['id'] ?>
                                </td>

                                <!-- Course -->
                                <td
                                    class="course-name"
                                    data-label="<?= $isEnglish
                                        ? 'Course'
                                        : 'Kursus' ?>"
                                >
                                    <?= courseEscape($courseTitle) ?>
                                </td>

                                <!-- Category -->
                                <td data-label="<?= $isEnglish
                                    ? 'Category'
                                    : 'Kategooria' ?>">

                                    <?= courseEscape(
                                        $course['category_name'] ?? '-'
                                    ) ?>
                                </td>

                                <!-- Instructor -->
                                <td data-label="<?= $isEnglish
                                    ? 'Instructor'
                                    : 'Sõiduõpetaja' ?>">

                                    <?= courseEscape(
                                        $course['instructor_name'] ?? '-'
                                    ) ?>
                                </td>

                                <!-- Price -->
                                <td data-label="<?= $isEnglish
                                    ? 'Price'
                                    : 'Hind' ?>">

                                    <?= number_format(
                                        (float)$course['price'],
                                        2,
                                        ',',
                                        ' '
                                    ) ?> €
                                </td>

                                <!-- Duration -->
                                <td data-label="<?= $isEnglish
                                    ? 'Duration'
                                    : 'Kestus' ?>">

                                    <?= courseEscape(
                                        $course['duration']
                                    ) ?>
                                </td>

                                <!-- Status -->
                                <td data-label="<?= $isEnglish
                                    ? 'Status'
                                    : 'Staatus' ?>">

                                    <?php if ($isActive): ?>

                                        <span class="badge bg-success status-badge">
                                            <?= $isEnglish
                                                ? 'Active'
                                                : 'Aktiivne' ?>
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-secondary status-badge">
                                            <?= $isEnglish
                                                ? 'Deleted'
                                                : 'Kustutatud' ?>
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <!-- Actions -->
                                <td data-label="<?= $isEnglish
                                    ? 'Actions'
                                    : 'Tegevused' ?>">

                                    <div class="action-buttons">

                                        <!-- Edit -->
                                        <a
                                            href="index.php?page=courseEdit&amp;id=<?= (int)$course['id'] ?>"
                                            class="btn btn-primary btn-sm"
                                        >
                                            <?= $isEnglish
                                                ? 'Edit'
                                                : 'Muuda' ?>
                                        </a>

                                        <!-- Delete / Restore -->
                                        <form
                                            method="POST"
                                            action="index.php"
                                        >

                                            <input
                                                type="hidden"
                                                name="action"
                                                value="<?= $isActive
                                                    ? 'delete_course'
                                                    : 'restore_course' ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="course_id"
                                                value="<?= (int)$course['id'] ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="csrf_token"
                                                value="<?= courseEscape(
                                                    $csrfToken
                                                ) ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="btn <?= $isActive
                                                    ? 'btn-danger'
                                                    : 'btn-success' ?> btn-sm"
                                            >

                                                <?= $isActive
                                                    ? ($isEnglish
                                                        ? 'Delete'
                                                        : 'Kustuta')
                                                    : ($isEnglish
                                                        ? 'Restore'
                                                        : 'Taasta') ?>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>

    </div>

</main>

</body>
</html>
