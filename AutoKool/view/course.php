
<?php
ob_start();
?>

<?php if (empty($n)): ?>
    <div class="alert alert-warning">Kursust ei leitud.</div>
    <a href="allcourses" class="btn btn-primary">Tagasi kursustele</a>
<?php else: ?>

    <div class="mb-4">
        <a href="allcourses" class="text-decoration-none">
            ← Kõik kursused
        </a>
    </div>

    <article class="card shadow-sm border-0 course-detail">
        <div class="card-body p-4 p-md-5">
            <span class="badge text-bg-primary mb-3">AutoKool</span>

            <h1 class="mb-3">
                <?= htmlspecialchars($n['title'], ENT_QUOTES, 'UTF-8') ?>
            </h1>

            <p class="fs-5">
                <?= nl2br(htmlspecialchars($n['description'], ENT_QUOTES, 'UTF-8')) ?>
            </p>

            <div class="row g-3 my-4">
                <div class="col-12 col-sm-6">
                    <div class="info-box">
                        <span class="text-muted d-block">Kursuse hind</span>
                        <strong class="fs-4">
                            <?= htmlspecialchars((string)$n['price'], ENT_QUOTES, 'UTF-8') ?> €
                        </strong>
                    </div>
                </div>

                <div class="col-12 col-sm-6">
                    <div class="info-box">
                        <span class="text-muted d-block">Kursuse kestus</span>
                        <strong class="fs-4">
                            <?= htmlspecialchars($n['duration'], ENT_QUOTES, 'UTF-8') ?>
                        </strong>
                    </div>
                </div>
            </div>

            <a href="registerForm" class="btn btn-primary btn-lg">
                Registreeru õpilaseks
            </a>
        </div>
    </article>

    <section class="mt-5" id="reviewtable">
        <h2 class="mb-3">Õpilaste tagasiside</h2>

        <?php if (empty($reviews)): ?>
            <p class="text-muted">Selle kursuse kohta pole veel tagasisidet.</p>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($reviews as $review): ?>
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <p class="mb-2">
                                    <?= nl2br(htmlspecialchars($review['text'], ENT_QUOTES, 'UTF-8')) ?>
                                </p>
                                <small class="text-muted">
                                    <?= htmlspecialchars($review['username'] ?? 'Õpilane', ENT_QUOTES, 'UTF-8') ?>
                                    ·
                                    <?= htmlspecialchars($review['date'], ENT_QUOTES, 'UTF-8') ?>
                                </small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0 mt-4">
            <div class="card-body">
                <h3 class="h5">Jäta tagasiside</h3>

                <form action="insertreview" method="post">
                    <input
                        type="hidden"
                        name="id"
                        value="<?= (int)$n['id'] ?>"
                    >

                    <div class="mb-3">
                        <label for="review" class="form-label">Sinu tagasiside</label>
                        <textarea
                            class="form-control"
                            id="review"
                            name="review"
                            rows="4"
                            maxlength="2000"
                            required
                            placeholder="Kirjuta oma kogemusest..."
                        ></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Saada tagasiside
                    </button>
                </form>
            </div>
        </div>
    </section>

<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
