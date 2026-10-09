
<?php
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm border-0 register-card">
            <div class="card-body p-4 p-md-5">
                <h1 class="h2 mb-2">Loo konto</h1>
                <p class="text-muted mb-4">
                    Registreeru AutoKooli õpilaseks.
                </p>

                <form action="registerAnswer" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nimi</label>
                        <input
                            type="text"
                            class="form-control"
                            id="name"
                            name="name"
                            maxlength="100"
                            autocomplete="name"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-post</label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            maxlength="150"
                            autocomplete="email"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Parool</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >
                        <div class="form-text">Vähemalt 8 märki.</div>
                    </div>

                    <div class="mb-4">
                        <label for="confirm" class="form-label">Korda parooli</label>
                        <input
                            type="password"
                            class="form-control"
                            id="confirm"
                            name="confirm"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        Registreeru
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>

