<?php
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-7 col-lg-5">
        <div class="card shadow-sm border-0 register-card">
            <div class="card-body p-4 p-md-5">
                <h1 class="h2 mb-2">Logi sisse</h1>
                <p class="text-muted mb-4">
                    Sisesta oma konto andmed.
                </p>

                <?php if (!empty($result) && !$result['success']): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($result['message'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <form action="login" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">E-post</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            autocomplete="email"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Parool</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Logi sisse
                    </button>
                </form>

                <p class="text-center mt-4 mb-0">
                    Pole veel kontot?
                    <a href="registerForm">Registreeru</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>