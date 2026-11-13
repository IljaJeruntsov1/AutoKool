
<?php
require_once __DIR__ . '/../inc/language.php';

$lang = $_SESSION['language'] ?? 'et';

ob_start();
?>

<h1><?= $lang === 'en' ? 'All courses' : 'Kõik kursused' ?></h1>
<br>

<?php foreach ($arr as $value): ?>

    <?php
    $title = ($lang === 'en' && !empty($value['title_en']))
        ? $value['title_en']
        : $value['title'];

    $description = ($lang === 'en' && !empty($value['description_en']))
        ? $value['description_en']
        : $value['description'];
    ?>

    <h2><?= htmlspecialchars($title) ?></h2>

    <p><?= htmlspecialchars($description) ?></p>

    <b><?= $lang === 'en' ? 'Price' : 'Hind' ?>:
        <?= htmlspecialchars($value['price']) ?> €
    </b><br>

    <a href="course?id=<?= (int)$value['id'] ?>">
        <?= $lang === 'en' ? 'View course' : 'Vaata kursust' ?>
    </a>

    <hr>

<?php endforeach; ?>

<?php
$content = ob_get_clean();

include_once __DIR__ . '/layout.php';
?>
