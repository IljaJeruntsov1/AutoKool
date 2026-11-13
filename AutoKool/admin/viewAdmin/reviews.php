
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

    <title>AutoKool — <?= $isEnglish ? 'Review management' : 'Kommentaaride haldus' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand" href="?page=dashboard">
            AutoKool Admin
        </a>

        <div class="d-flex align-items-center gap-2">

            <?php $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>

            <form method="GET" action="<?= htmlspecialchars($currentPath) ?>">
                <?php foreach ($_GET as $key => $value): ?>
                    <?php if ($key !== 'lang' && is_scalar($value)): ?>
                        <input type="hidden"
                               name="<?= htmlspecialchars($key) ?>"
                               value="<?= htmlspecialchars((string)$value) ?>">
                    <?php endif; ?>
                <?php endforeach; ?>

                <select name="lang"
                        class="form-select form-select-sm"
                        onchange="this.form.submit()">
                    <option value="et" <?= !$isEnglish ? 'selected' : '' ?>>ET</option>
                    <option value="en" <?= $isEnglish ? 'selected' : '' ?>>EN</option>
                </select>
            </form>

            <a href="../" class="btn btn-outline-light">
                <?= $isEnglish ? 'Back to website' : 'Tagasi veebilehele' ?>
            </a>

        </div>
    </div>
</nav>

<main class="container py-4">

    <a href="?page=dashboard" class="btn btn-outline-secondary mb-3">
        ← <?= $isEnglish ? 'Back to dashboard' : 'Tagasi juhtpaneelile' ?>
    </a>

    <h1 class="mb-4">
        <?= $isEnglish ? 'Course reviews' : 'Kursuste kommentaarid' ?>
    </h1>

    <?php
    $messages = [
        'updated' => $isEnglish
            ? 'The review has been successfully updated.'
            : 'Kommentaar on edukalt muudetud.',

        'deleted' => $isEnglish
            ? 'The review has been deleted.'
            : 'Kommentaar on kustutatud.',

        'invalid' => $isEnglish
            ? 'Please check the entered data.'
            : 'Kontrolli sisestatud andmeid.'
    ];

    $messageKey = $_GET['message'] ?? '';

    if (isset($messages[$messageKey])):
    ?>
        <div class="alert <?= $messageKey === 'invalid' ? 'alert-danger' : 'alert-success' ?>">
            <?= htmlspecialchars($messages[$messageKey], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($reviews)): ?>

        <div class="alert alert-info">
            <?= $isEnglish ? 'There are no reviews yet.' : 'Kommentaare veel ei ole.' ?>
        </div>

    <?php else: ?>

        <?php foreach ($reviews as $review): ?>

            <div class="card shadow-sm mb-4">

                <div class="card-header d-flex flex-wrap justify-content-between gap-2">

                    <strong>
                        <?= htmlspecialchars($review['course_title'], ENT_QUOTES, 'UTF-8') ?>
                    </strong>

                    <span class="text-muted">
                        <?= htmlspecialchars($review['review_date'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </span>

                </div>

                <div class="card-body">

                    <p>
                        <strong><?= $isEnglish ? 'Author:' : 'Autor:' ?></strong>

                        <?= htmlspecialchars(
                            $review['username'] ?? ($isEnglish ? 'Deleted user' : 'Kustutatud kasutaja'),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </p>

                    <form method="post" action="index.php?page=reviews" class="mb-3">

                        <input type="hidden" name="action" value="update_review">

                        <input type="hidden"
                               name="csrf_token"
                               value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

                        <input type="hidden"
                               name="review_id"
                               value="<?= (int)$review['id'] ?>">

                        <label class="form-label"
                               for="review-<?= (int)$review['id'] ?>">
                            <?= $isEnglish ? 'Review text' : 'Kommentaari tekst' ?>
                        </label>

                        <textarea
                            class="form-control mb-3"
                            id="review-<?= (int)$review['id'] ?>"
                            name="text"
                            rows="4"
                            maxlength="2000"
                            required
                        ><?= htmlspecialchars($review['text'], ENT_QUOTES, 'UTF-8') ?></textarea>

                        <button type="submit" class="btn btn-primary">
                            <?= $isEnglish ? 'Save changes' : 'Salvesta muudatused' ?>
                        </button>

                    </form>

                    <form
                        method="post"
                        action="index.php?page=reviews"
                        onsubmit="return confirm('<?= $isEnglish ? 'Are you sure you want to delete this review?' : 'Kas oled kindel, et soovid selle kommentaari kustutada?' ?>');"
                    >

                        <input type="hidden" name="action" value="delete_review">

                        <input type="hidden"
                               name="csrf_token"
                               value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

                        <input type="hidden"
                               name="review_id"
                               value="<?= (int)$review['id'] ?>">

                        <button type="submit" class="btn btn-danger">
                            <?= $isEnglish ? 'Delete review' : 'Kustuta kommentaar' ?>
                        </button>

                    </form>

                </div>
            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</main>

</body>
</html>
