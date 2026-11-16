
<?php
require_once __DIR__ . '/../inc/language.php';

$lang = $_SESSION['language'] ?? 'et';

ob_start();
?>

<h1 class="mb-2">
    <?= $lang === 'en' ? 'Category courses' : 'Kategooria kursused' ?>
</h1>

<p class="text-muted mb-4">
    <?= $lang === 'en'
        ? 'Explore course prices, duration and content.'
        : 'Tutvu kursuste hinna, kestuse ja sisuga.' ?>
</p>

<?php if (empty($arr)): ?>

    <div class="alert alert-info">
        <?= $lang === 'en'
            ? 'There are currently no courses in this category.'
            : 'Selles kategoorias ei ole praegu kursuseid.' ?>
    </div>

    <a href="allcourses" class="btn btn-outline-primary">
        <?= $lang === 'en' ? 'All courses' : 'Kõik kursused' ?>
    </a>

<?php else: ?>

    <div class="row g-4">
ы
        <?php foreach ($arr as $course): ?>

            <?php
            $title = ($lang === 'en' && !empty($course['title_en']))
                ? $course['title_en']
                : $course['title'];

            $description = ($lang === 'en' && !empty($course['description_en']))
                ? $course['description_en']
                : $course['description'];
            ?>

            <div class="col-12 col-md-6 col-lg-4">

                <div class="card course-card h-100 shadow-sm">

                    <div class="card-body d-flex flex-column">

                        <h2 class="h4 card-title">
                            <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>
                        </h2>

                        <p class="card-text">
                            <?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <div class="mt-auto">

                            <p class="mb-1">
                                <strong>
                                    <?= $lang === 'en' ? 'Price:' : 'Hind:' ?>
                                </strong>

                                <?= htmlspecialchars((string)$course['price'], ENT_QUOTES, 'UTF-8') ?> €
                            </p>

                            <p>
                                <strong>
                                    <?= $lang === 'en' ? 'Duration:' : 'Kestus:' ?>
                                </strong>

                                <?= htmlspecialchars($course['duration'], ENT_QUOTES, 'UTF-8') ?>
                            </p>

                            <a
                                href="course?id=<?= (int)$course['id'] ?>"
                                class="btn btn-primary w-100"
                            >
                                <?= $lang === 'en' ? 'View course' : 'Vaata kursust' ?>
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

<?php
$content = ob_get_clean();

include __DIR__ . '/layout.php';
?>
