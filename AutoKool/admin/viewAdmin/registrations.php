
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

    <title><?= $isEnglish ? 'Student registrations' : 'Kursustele registreerunud õpilased' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>
            <?= $isEnglish ? 'Student registrations' : 'Õpilaste registreeringud' ?>
        </h2>

        <div class="d-flex gap-2 align-items-center">

            <?php
            $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            ?>

            <form method="GET" action="<?= htmlspecialchars($currentPath) ?>">
                <?php foreach ($_GET as $key => $value): ?>
                    <?php if ($key !== 'lang' && is_scalar($value)): ?>
                        <input type="hidden"
                               name="<?= htmlspecialchars($key) ?>"
                               value="<?= htmlspecialchars((string)$value) ?>">
                    <?php endif; ?>
                <?php endforeach; ?>

                <select name="lang"
                        class="form-select"
                        onchange="this.form.submit()">

                    <option value="et" <?= !$isEnglish ? 'selected' : '' ?>>ET</option>
                    <option value="en" <?= $isEnglish ? 'selected' : '' ?>>EN</option>

                </select>
            </form>

            <a href="index.php?page=dashboard" class="btn btn-secondary">
                <?= $isEnglish ? 'Back' : 'Tagasi' ?>
            </a>

        </div>
    </div>

    <?php if (($_GET['message'] ?? '') === 'cancelled'): ?>
        <div class="alert alert-success">
            <?= $isEnglish ? 'Student registration has been cancelled.' : 'Õpilase registreering on tühistatud.' ?>
        </div>

    <?php elseif (($_GET['message'] ?? '') === 'restored'): ?>
        <div class="alert alert-success">
            <?= $isEnglish ? 'Student has been restored to the course!' : 'Õpilane on kursusele tagasi lisatud!' ?>
        </div>

    <?php elseif (($_GET['message'] ?? '') === 'invalid'): ?>
        <div class="alert alert-danger">
            <?= $isEnglish ? 'Invalid data.' : 'Vigased andmed.' ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <?php if (empty($registrations)): ?>

                <p class="text-muted">
                    <?= $isEnglish ? 'There are no registrations.' : 'Registreeringuid ei ole.' ?>
                </p>

            <?php else: ?>

                <div class="table-responsive">

                    <table class="table table-striped align-middle">

                        <thead>
                            <tr>
                                <th><?= $isEnglish ? 'Student' : 'Õpilane' ?></th>
                                <th><?= $isEnglish ? 'Email' : 'E-post' ?></th>
                                <th><?= $isEnglish ? 'Course' : 'Kursus' ?></th>
                                <th><?= $isEnglish ? 'Registration date' : 'Registreerimise kuupäev' ?></th>
                                <th><?= $isEnglish ? 'Status' : 'Staatus' ?></th>
                                <th><?= $isEnglish ? 'Action' : 'Tegevus' ?></th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($registrations as $registration): ?>

                            <tr>

                                <td><?= htmlspecialchars($registration['username']) ?></td>

                                <td><?= htmlspecialchars($registration['email']) ?></td>

                                <td><?= htmlspecialchars($registration['course_title']) ?></td>

                                <td><?= htmlspecialchars($registration['registration_date']) ?></td>

                                <td>
                                    <?php if ($registration['status'] === 'cancelled'): ?>

                                        <span class="badge bg-secondary">
                                            <?= $isEnglish ? 'Cancelled' : 'Tühistatud' ?>
                                        </span>

                                    <?php elseif ($registration['status'] === 'confirmed'): ?>

                                        <span class="badge bg-success">
                                            <?= $isEnglish ? 'Confirmed' : 'Kinnitatud' ?>
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-warning text-dark">
                                            <?= $isEnglish ? 'Pending' : 'Ootel' ?>
                                        </span>

                                    <?php endif; ?>
                                </td>

                                <td>

                                    <?php if ($registration['status'] !== 'cancelled'): ?>

                                        <form method="POST"
                                              action="index.php"
                                              onsubmit="return confirm('<?= $isEnglish ? 'Are you sure you want to remove this student from the course?' : 'Kas soovid selle õpilase kursuselt eemaldada?' ?>');">

                                            <input type="hidden" name="action" value="cancel_registration">

                                            <input type="hidden" name="csrf_token"
                                                   value="<?= htmlspecialchars($csrfToken) ?>">

                                            <input type="hidden" name="registration_id"
                                                   value="<?= (int)$registration['id'] ?>">

                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <?= $isEnglish ? 'Remove from course' : 'Eemalda kursuselt' ?>
                                            </button>

                                        </form>

                                    <?php else: ?>

                                        <form method="POST"
                                              action="index.php"
                                              onsubmit="return confirm('<?= $isEnglish ? 'Do you want to restore this student to the course?' : 'Kas soovid õpilase uuesti kursusele registreerida?' ?>');">

                                            <input type="hidden" name="action" value="restore_registration">

                                            <input type="hidden" name="csrf_token"
                                                   value="<?= htmlspecialchars($csrfToken) ?>">

                                            <input type="hidden" name="registration_id"
                                                   value="<?= (int)$registration['id'] ?>">

                                            <button type="submit" class="btn btn-success btn-sm">
                                                <?= $isEnglish ? 'Restore to course' : 'Lisa kursusele tagasi' ?>
                                            </button>

                                        </form>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>
    </div>

</div>

</body>
</html>
