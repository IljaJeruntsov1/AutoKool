
<?php
ob_start();
?>

<section class="mb-4">
    <h1 class="mb-2">Õppekategooriad</h1>
    <p class="text-muted">Vali juhiloa kategooria ja tutvu sobivate kursustega.</p>
</section>

<div class="row g-4">
    <?php foreach ($arr as $category): ?>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card course-card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h2 class="h4 card-title">
                        <?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?>
                    </h2>

                    <p class="card-text">
                        <?= htmlspecialchars($category['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </p>

                    <a
                        class="btn btn-primary mt-auto"
                        href="category?id=<?= (int)$category['id'] ?>"
                    >
                        Vaata kursuseid
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>

