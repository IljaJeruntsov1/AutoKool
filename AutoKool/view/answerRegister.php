
<?php
ob_start();

$success = $result['success'] ?? false;
$message = $result['message'] ?? 'Tekkis ootamatu viga.';
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 text-center">
                <?php if ($success): ?>
                    <div class="display-5 mb-3">✓</div>
                    <h1 class="h3">Registreerimine õnnestus!</h1>
                    <div class="alert alert-success mt-3">
                        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <a href="allcourses" class="btn btn-primary">
                        Vaata kursuseid
                    </a>
                <?php else: ?>
                    <div class="display-5 mb-3">!</div>
                    <h1 class="h3">Registreerimine ebaõnnestus</h1>
                    <div class="alert alert-warning mt-3">
                        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <a href="registerForm" class="btn btn-primary">
                        Proovi uuesti
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>

