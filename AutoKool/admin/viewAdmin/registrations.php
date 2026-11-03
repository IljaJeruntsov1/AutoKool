
<?php
// Ожидаются переменные $registrations и $csrfToken
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kursustele registreerunud õpilased</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Õpilaste registreeringud</h2>
        <a href="index.php?page=dashboard" class="btn btn-secondary">
            Tagasi
        </a>
    </div>

    <?php if (($_GET['message'] ?? '') === 'cancelled'): ?>
        <div class="alert alert-success">
            Õpilase registreering on tühistatud.
        </div>
    <?php elseif (($_GET['message'] ?? '') === 'invalid'): ?>
        <div class="alert alert-danger">
            Vigased andmed.
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <?php if (empty($registrations)): ?>

                <p class="text-muted">Registreeringuid ei ole.</p>

            <?php else: ?>

                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Õpilane</th>
                                <th>E-post</th>
                                <th>Kursus</th>
                                <th>Registreerimise kuupäev</th>
                                <th>Staatus</th>
                                <th>Tegevus</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($registrations as $registration): ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($registration['username']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($registration['email']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($registration['course_title']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($registration['registration_date']) ?>
                                    </td>

                                    <td>
                                        <?php if ($registration['status'] === 'cancelled'): ?>
                                            <span class="badge bg-secondary">
                                                Tühistatud
                                            </span>
                                        <?php elseif ($registration['status'] === 'confirmed'): ?>
                                            <span class="badge bg-success">
                                                Kinnitatud
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">
                                                Ootel
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if ($registration['status'] !== 'cancelled'): ?>

                                            <form method="POST"
                                                  action="index.php?page=registrations"
                                                  onsubmit="return confirm('Kas soovid selle õpilase kursuselt eemaldada?');">

                                                <input type="hidden"
                                                       name="action"
                                                       value="cancel_registration">

                                                <input type="hidden"
                                                       name="csrf_token"
                                                       value="<?= htmlspecialchars($csrfToken) ?>">

                                                <input type="hidden"
                                                       name="registration_id"
                                                       value="<?= (int)$registration['id'] ?>">

                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    Eemalda kursuselt
                                                </button>
                                            </form>

                                        <?php else: ?>

                                            <span class="text-muted">Juba tühistatud</span>

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
