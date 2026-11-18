<?php
require_once __DIR__ . '/../../inc/language.php';

$currentLanguage = $_SESSION['language'] ?? 'et';
$isEnglish = $currentLanguage === 'en';
$escape = static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
$messages = [
    'restored' => [$isEnglish ? 'Profile restored.' : 'Profiil taastati.', 'success'],
    'invalid' => [$isEnglish ? 'Enter a valid email and a password of at least 8 characters.' : 'Sisesta korrektne e-post ja vähemalt 8-märgiline parool.', 'danger'],
    'email_exists' => [$isEnglish ? 'That email is already in use.' : 'See e-posti aadress on juba kasutusel.', 'danger'],
    'not_found' => [$isEnglish ? 'This deleted profile was not found.' : 'Kustutatud profiili ei leitud.', 'warning'],
    'error' => [$isEnglish ? 'Could not restore the profile.' : 'Profiili taastamine ebaõnnestus.', 'danger'],
];
?>
<!DOCTYPE html>
<html lang="<?= $escape($currentLanguage) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $isEnglish ? 'Deleted profiles' : 'Kustutatud profiilid' ?> — AutoKool Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="?page=dashboard">AutoKool Admin</a>
        <a class="btn btn-outline-light" href="?page=dashboard"><?= $isEnglish ? 'Back to dashboard' : 'Tagasi juhtpaneelile' ?></a>
    </div>
</nav>
<main class="container py-5">
    <h1 class="mb-3"><?= $isEnglish ? 'Deleted profiles' : 'Kustutatud profiilid' ?></h1>
    <p class="text-muted mb-4"><?= $isEnglish
        ? 'Deleted profiles retain only the name. To restore access, assign a new email and password. Previously deleted applications and reviews cannot be recovered.'
        : 'Kustutatud profiilist on alles ainult nimi. Juurdepääsu taastamiseks määra uus e-post ja parool. Kustutatud registreeringuid ja tagasisidet taastada ei saa.' ?></p>

    <?php if (isset($messages[$message])): ?>
        <div class="alert alert-<?= $escape($messages[$message][1]) ?>" role="status"><?= $escape($messages[$message][0]) ?></div>
    <?php endif; ?>

    <?php if (!$profiles): ?>
        <div class="alert alert-info"><?= $isEnglish ? 'There are no deleted profiles.' : 'Kustutatud profiile ei ole.' ?></div>
    <?php else: ?>
        <div class="card shadow-sm"><div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr>
                    <th><?= $isEnglish ? 'Name' : 'Nimi' ?></th>
                    <th><?= $isEnglish ? 'New email' : 'Uus e-post' ?></th>
                    <th><?= $isEnglish ? 'New password' : 'Uus parool' ?></th>
                    <th></th>
                </tr></thead>
                <tbody>
                <?php foreach ($profiles as $profile): ?>
                    <?php $formId = 'restore-' . (int)$profile['id']; ?>
                    <tr>
                        <td><?= $escape($profile['username']) ?></td>
                        <td><form id="<?= $formId ?>" method="POST" action="index.php"></form>
                            <input form="<?= $formId ?>" type="email" name="email" class="form-control" maxlength="150" required autocomplete="email" aria-label="<?= $isEnglish ? 'New email' : 'Uus e-post' ?>"></td>
                        <td><input form="<?= $formId ?>" type="password" name="password" class="form-control" minlength="8" required autocomplete="new-password" aria-label="<?= $isEnglish ? 'New password' : 'Uus parool' ?>"></td>
                        <td>
                            <input form="<?= $formId ?>" type="hidden" name="action" value="restore_profile">
                            <input form="<?= $formId ?>" type="hidden" name="user_id" value="<?= (int)$profile['id'] ?>">
                            <input form="<?= $formId ?>" type="hidden" name="csrf_token" value="<?= $escape($csrfToken) ?>">
                            <button form="<?= $formId ?>" class="btn btn-success" type="submit"><?= $isEnglish ? 'Restore' : 'Taasta' ?></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div></div>
    <?php endif; ?>
</main>
</body>
</html>
