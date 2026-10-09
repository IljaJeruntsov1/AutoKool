
<?php
ob_start();
?>

<h1 class="mb-2">Kategooria kursused</h1>
<p class="text-muted mb-4">Tutvu kursuste hinna, kestuse ja sisuga.</p>

<?php if (empty($arr)): ?>
    <div class="alert alert-info">
        Selles kategoorias ei ole praegu kursuseid.
    </div>
    <a href="allcourses" class="btn btn-outline-primary">Kõik kursused</a>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($arr as $course): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card course-card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h2 class="h4 card-title">
                            <?= htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8') ?>
                        </h2>

                        <p class="card-text">
                            <?= htmlspecialchars($course['description'], ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <div class="mt-auto">
                            <p class="mb-1">
                                <strong>Hind:</strong>
                                <?= htmlspecialchars((string)$course['price'], ENT_QUOTES, 'UTF-8') ?> €
                            </p>

                            <p>
                                <strong>Kestus:</strong>
                                <?= htmlspecialchars($course['duration'], ENT_QUOTES, 'UTF-8') ?>
                            </p>

                            <a
                                href="course?id=<?= (int)$course['id'] ?>"
                                class="btn btn-primary w-100"
                            >
                                Vaata kursust
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

