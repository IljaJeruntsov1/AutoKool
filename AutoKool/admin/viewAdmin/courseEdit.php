
<?php
$lang = $_SESSION['language'] ?? 'et';
$isEnglish = $lang === 'en';

function courseField($value)
{
    return htmlspecialchars(
        (string)($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<!DOCTYPE html>
<html lang="<?= $isEnglish ? 'en' : 'et' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>AutoKool - Edit Course</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between
                align-items-center mb-4">

        <h1 class="h2">
            <?= $isEnglish
                ? 'Edit Course'
                : 'Muuda kursust' ?>
        </h1>

        <a href="index.php?page=courses"
           class="btn btn-secondary">
            <?= $isEnglish ? 'Back' : 'Tagasi' ?>
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <form method="POST"
                  action="index.php">

                <input type="hidden"
                       name="action"
                       value="update_course">

                <input type="hidden"
                       name="course_id"
                       value="<?= (int)$course['id'] ?>">

                <input type="hidden"
                       name="csrf_token"
                       value="<?= courseField($csrfToken) ?>">

                <!-- Category -->
                <div class="mb-3">

                    <label class="form-label">
                        <?= $isEnglish
                            ? 'Category'
                            : 'Kategooria' ?>
                    </label>

                    <select name="category_id"
                            class="form-select"
                            required>

                        <?php foreach ($categories as $category): ?>

                            <option
                                value="<?= (int)$category['id'] ?>"
                                <?= (int)$category['id'] ===
                                    (int)$course['category_id']
                                    ? 'selected' : '' ?>>

                                <?= courseField($category['name']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <!-- Instructor -->
                <div class="mb-3">

                    <label class="form-label">
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

                            <option
                                value="<?= (int)$instructor['id'] ?>"
                                <?= (int)$instructor['id'] ===
                                    (int)($course['instructor_id'] ?? 0)
                                    ? 'selected' : '' ?>>

                                <?= courseField($instructor['name']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <hr>

                <h4>Eesti keel</h4>

                <!-- Estonian title -->
                <div class="mb-3">
                    <label class="form-label">
                        Kursuse nimi
                    </label>

                    <input
                        type="text"
                        name="title"
                        maxlength="200"
                        class="form-control"
                        value="<?= courseField($course['title']) ?>"
                        required>
                </div>

                <!-- Estonian description -->
                <div class="mb-3">
                    <label class="form-label">
                        Kursuse kirjeldus
                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="4"
                        required><?= courseField($course['description']) ?></textarea>
                </div>

                <hr>

                <h4>English</h4>

                <!-- English title -->
                <div class="mb-3">
                    <label class="form-label">
                        Course title
                    </label>

                    <input
                        type="text"
                        name="title_en"
                        maxlength="255"
                        class="form-control"
                        value="<?= courseField($course['title_en']) ?>">
                </div>

                <!-- English description -->
                <div class="mb-3">
                    <label class="form-label">
                        Course description
                    </label>

                    <textarea
                        name="description_en"
                        class="form-control"
                        rows="4"><?= courseField($course['description_en']) ?></textarea>
                </div>

                <hr>

                <div class="row g-3 mb-4">

                    <div class="col-md-6">
                        <label class="form-label">
                            <?= $isEnglish
                                ? 'Price (€)'
                                : 'Hind (€)' ?>
                        </label>

                        <input
                            type="number"
                            name="price"
                            step="0.01"
                            min="0"
                            max="99999999.99"
                            class="form-control"
                            value="<?= courseField($course['price']) ?>"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <?= $isEnglish
                                ? 'Duration'
                                : 'Kestus' ?>
                        </label>

                        <input
                            type="text"
                            name="duration"
                            maxlength="100"
                            class="form-control"
                            value="<?= courseField($course['duration']) ?>"
                            required>
                    </div>

                </div>

                <button type="submit"
                        class="btn btn-success btn-lg">

                    <?= $isEnglish
                        ? 'Save Changes'
                        : 'Salvesta muudatused' ?>

                </button>

            </form>

        </div>
    </div>

</div>

</body>
</html>
