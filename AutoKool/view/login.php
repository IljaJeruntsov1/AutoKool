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

                <?php if ($loginLockRemaining > 0): ?>
                    <div class="alert alert-warning" id="login-lock-countdown" data-remaining="<?= (int)$loginLockRemaining ?>" role="status">
                        <?= ($_SESSION['language'] ?? 'et') === 'en' ? 'You can try again in' : 'Uuesti saad proovida:' ?>
                        <span id="login-lock-time"></span>
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
                            <?= !empty($result['locked']) ? 'disabled' : '' ?>
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
                            <?= !empty($result['locked']) ? 'disabled' : '' ?>
                        >
                    </div>

                    <button type="submit" class="btn btn-primary w-100" <?= !empty($result['locked']) ? 'disabled' : '' ?>>
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

<?php if ($loginLockRemaining > 0): ?>
<script>
(() => {
    const notice = document.getElementById('login-lock-countdown');
    const clock = document.getElementById('login-lock-time');
    const form = document.querySelector('form[action="login"]');
    const controls = form.querySelectorAll('input, button');
    const unlockAt = Date.now() + Number(notice.dataset.remaining) * 1000;

    const updateCountdown = () => {
        const secondsLeft = Math.max(0, Math.ceil((unlockAt - Date.now()) / 1000));
        clock.textContent = `${Math.floor(secondsLeft / 60)}:${String(secondsLeft % 60).padStart(2, '0')}`;
        if (secondsLeft === 0) {
            controls.forEach(control => { control.disabled = false; });
            document.querySelector('.alert-danger')?.remove();
            notice.textContent = <?= json_encode(($_SESSION['language'] ?? 'et') === 'en' ? 'You can sign in again now.' : 'Nüüd saad uuesti sisse logida.') ?>;
            return;
        }
        window.setTimeout(updateCountdown, 1000);
    };

    updateCountdown();
})();
</script>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
