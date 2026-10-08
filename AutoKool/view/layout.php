<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AutoKool</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="style.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand fw-bold" href="./">
            🚗 AutoKool
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainMenu"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainMenu">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link" href="./">
                        Avaleht
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="allcourses">
                        Kursused
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="category?id=1">
                        B-kategooria
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="category?id=2">
                        A-kategooria
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="category?id=3">
                        C-kategooria
                    </a>
                </li>

            </ul>

            <div class="d-flex">

                <a
                    href="registerForm"
                    class="btn btn-outline-light"
                >
                    Registreeru
                </a>

            </div>

        </div>
    </div>
</nav>


<main class="container py-5">

    <?php

    if (isset($content)) {
        echo $content;
    } else {
        echo '<div class="alert alert-danger">
                Sisu puudub.
              </div>';
    }

    ?>

</main>


<footer class="bg-dark text-white text-center py-4">

    <div class="container">

        <p class="mb-1">
            <strong>AutoKool</strong>
        </p>

        <p class="mb-0">
            Õpi sõitma turvaliselt ja enesekindlalt.
        </p>

        <small>
            &copy; <?php echo date('Y'); ?> AutoKool
        </small>

    </div>

</footer>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>