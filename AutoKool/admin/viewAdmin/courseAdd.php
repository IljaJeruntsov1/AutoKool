
<?php
require_once __DIR__ . '/../../inc/language.php';

$lang = $_SESSION['language'] ?? 'et';
$isEnglish = $lang === 'en';

$message = $_GET['message'] ?? '';
?>

<!DOCTYPE html>
<html lang="<?= $isEnglish ? 'en' : 'et' ?>">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>AutoKool - Add Course</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between
                align-items-center mb-4">

        <h1 class="h2">
            <?= $isEnglish
                ? 'Add New Course'
                : 'Lisa uus kursus' ?>
        </h1>

        <a href="index.php?page=dashboard"
           class="btn btn-outline-secondary">
            <?= $isEnglish ? 'Back' : 'Tagasi' ?>
        </a>

    </div>

    <?php if ($message === 'success'): ?>
        <div class="alert alert-success">
            <?= $isEnglish
                ? 'Course created successfully!'
                : 'Kursus on edukalt lisatud!' ?>
        </div>

    <?php elseif ($message === 'invalid'): ?>
        <div class="alert alert-danger">
            <?= $isEnglish
                ? 'Please check the entered data.'
                : 'Kontrolli sisestatud andmeid.' ?>
        </div>

    <?php elseif ($message === 'error'): ?>
        <div class="alert alert-danger">
            <?= $isEnglish
                ? 'Could not save the course.'
                : 'Kursuse salvestamine ebaõnnestus.' ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 p-md-5">

            <form action="index.php?page=courseAdd"
                  method="POST">

                <input type="hidden"
                       name="action"
                       value="create_course">

                <input type="hidden"
                       name="csrf_token"
                       value="<?= htmlspecialchars(
                           $csrfToken,
                           ENT_QUOTES,
                           'UTF-8'
                       ) ?>">

                <!-- Category -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        <?= $isEnglish
                            ? 'Course category'
                            : 'Kursuse kategooria' ?>
                    </label>

                    <select name="category_id"
                            class="form-select"
                            required>

                        <option value="">
                            <?= $isEnglish
                                ? 'Select category'
                                : 'Vali kategooria' ?>
                        </option>

                        <?php foreach ($categories as $category): ?>

                            <option value="<?= (int)$category['id'] ?>">
                                <?= htmlspecialchars(
                                    $category['name'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        <?= $isEnglish
                            ? 'Instructor'
                            : 'Sõiduõpetaja' ?>
                    </label>

                    <select name="instructor_id"
                            class="form-select"
                            required>

                        <option value="">
                            <?= $isEnglish
                                ? 'Select instructor'
                                : 'Vali sõiduõpetaja' ?>
                        </option>

                        <?php foreach ($instructors as $instructor): ?>
                            <option value="<?= (int)$instructor['id'] ?>">
                                <?= htmlspecialchars(
                                    $instructor['name'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>


                <!-- Estonian -->
                <h4 class="mb-3">Eesti keel 🇪🇪</h4>

                <div class="mb-3">
                    <label class="form-label">
                        Kursuse nimi
                    </label>

                    <input type="text"
                           name="title"
                           maxlength="200"
                           class="form-control"
                           required>
                </div>

                <div class="mb-4">
                    <label class="form-label">
                        Kursuse kirjeldus
                    </label>

                    <textarea name="description"
                              rows="4"
                              class="form-control"
                              required></textarea>
                </div>

                <hr class="my-4">

                <!-- English -->
                <h4 class="mb-3">English 🇬🇧</h4>

                <div class="mb-3">
                    <label class="form-label">
                        Course title (English)
                    </label>

                    <input type="text"
                           name="title_en"
                           maxlength="255"
                           class="form-control">
                </div>

                <div class="mb-4">
                    <label class="form-label">
                        Course description (English)
                    </label>

                    <textarea name="description_en"
                              rows="4"
                              class="form-control"></textarea>
                </div>

                <hr class="my-4">

                <!-- Price & Duration -->
                <div class="row g-3 mb-4">

                    <div class="col-md-6">
                        <label class="form-label">
                            <?= $isEnglish
                                ? 'Price (€)'
                                : 'Hind (€)' ?>
                        </label>

                        <input type="number"
                               name="price"
                               min="0"
                               max="99999999.99"
                               step="0.01"
                               class="form-control"
                               placeholder="350.00"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <?= $isEnglish
                                ? 'Duration'
                                : 'Kestus' ?>
                        </label>

                        <input type="text"
                               name="duration"
                               maxlength="100"
                               class="form-control"
                               placeholder="2 kuud"
                               required>
                    </div>

                </div>

                <button type="submit"
                        class="btn btn-success btn-lg w-100">

                    <?= $isEnglish
                        ? 'Create Course'
                        : 'Lisa kursus' ?>

                </button>

            </form>

        </div>
    </div>

</div>

</body>
</html>
