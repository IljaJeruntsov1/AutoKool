
<?php
require_once __DIR__ . '/../../inc/language.php';

$currentLanguage = $_SESSION['language'] ?? 'et';
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
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand">AutoKool Admin</span>

        <?php
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        ?>

        <form method="GET"
              action="<?= htmlspecialchars($currentPath) ?>"
              class="d-inline-flex align-items-center">

            <?php foreach ($_GET as $key => $value): ?>
                <?php if ($key !== 'lang' && is_scalar($value)): ?>
                    <input type="hidden"
                           name="<?= htmlspecialchars($key) ?>"
                           value="<?= htmlspecialchars((string)$value) ?>">
                <?php endif; ?>
            <?php endforeach; ?>

            <select name="lang"
                    class="form-select form-select-sm"
                    onchange="this.form.submit()"
                    aria-label="Choose language">

                <option value="et"
                    <?= $currentLanguage === 'et' ? 'selected' : '' ?>>
                    ET
                </option>

                <option value="en"
                    <?= $currentLanguage === 'en' ? 'selected' : '' ?>>
                    EN
                </option>
            </select>
        </form>

        <a href="../" class="btn btn-outline-light">
            <?= t('back') ?>
        </a>
    </div>
</nav>

<main class="container py-5">
    <div class="card shadow-sm">
        <div class="card-body p-4">

            <h1 class="h3">
                <?= t('admin_panel') ?>
            </h1>

            <p class="text-muted">
                <?php if ($currentLanguage === 'en'): ?>
                    Welcome to the AutoKool administration panel!
                <?php else: ?>
                    Tere tulemast AutoKool administratsioonipaneeli!
                <?php endif; ?>
            </p>

            <div class="list-group">

                <a class="list-group-item list-group-item-action"
                   href="?page=reviews">
                    <?= t('manage_reviews') ?>
                </a>

                <a class="list-group-item list-group-item-action"
                   href="?page=registrations">
                    <?= t('registrations') ?>
                </a>

            </div>

        </div>
    </div>
</main>

</body>
</html>
