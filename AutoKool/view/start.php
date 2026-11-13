
<?php
require_once __DIR__ . '/../inc/language.php';

ob_start();
?>

<h1><?= t('popular_courses') ?></h1>
<br>

<?php foreach ($arr as $value): ?>

    <?php
    $title = t('language') === 'English'
        ? ($value['title_en'] ?? $value['title'])
        : $value['title'];
    ?>

    <h2>
        <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>
    </h2>

    <?php
    $description = $value['description'];

    if (($_SESSION['language'] ?? 'et') === 'en') {
        $description = !empty($value['description_en'])
            ? $value['description_en']
            : $value['description'];
    }
    ?>

    <p>
        <?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?>
    </p>

    <p>
        <b><?= t('price') ?>:</b>
        <?= htmlspecialchars($value['price'], ENT_QUOTES, 'UTF-8') ?> €
    </p>

    <p>
        <b><?= t('duration') ?>:</b>
        <?= htmlspecialchars($value['duration'], ENT_QUOTES, 'UTF-8') ?>
    </p>

    <a href="course?id=<?= (int)$value['id'] ?>">
        <?= t('view_course') ?>
    </a>

    <hr>

<?php endforeach; ?>

<?php
$content = ob_get_clean();

include_once 'view/layout.php';
?>
