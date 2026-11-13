
<?php
require_once __DIR__ . '/../inc/language.php';

$lang = $_SESSION['language'] ?? 'et';

ob_start();
?>

<?php if (empty($n)): ?>

    <div class="alert alert-warning">
        <?= $lang === 'en' ? 'Course not found.' : 'Kursust ei leitud.' ?>
    </div>

    <a href="allcourses" class="btn btn-primary">
        <?= $lang === 'en' ? 'Back to courses' : 'Tagasi kursustele' ?>
    </a>

<?php else: ?>

    <?php
    $title = ($lang === 'en' && !empty($n['title_en']))
        ? $n['title_en']
        : $n['title'];

    $description = ($lang === 'en' && !empty($n['description_en']))
        ? $n['description_en']
        : $n['description'];
    ?>

    <div class="mb-4">
        <a href="allcourses" class="text-decoration-none">
            ← <?= $lang === 'en' ? 'All courses' : 'Kõik kursused' ?>
        </a>
    </div>

    <article class="card shadow-sm border-0 course-detail">
        <div class="card-body p-4 p-md-5">

            <span class="badge text-bg-primary mb-3">AutoKool</span>

            <h1 class="mb-3">
                <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>
            </h1>

            <p class="fs-5">
                <?= nl2br(htmlspecialchars($description, ENT_QUOTES, 'UTF-8')) ?>
            </p>

            <div class="row g-3 my-4">

                <div class="col-12 col-sm-6">
                    <div class="info-box">
                        <span class="text-muted d-block">
                            <?= $lang === 'en' ? 'Course price' : 'Kursuse hind' ?>
                        </span>

                        <strong class="fs-4">
                            <?= htmlspecialchars((string)$n['price'], ENT_QUOTES, 'UTF-8') ?> €
                        </strong>
                    </div>
                </div>

                <div class="col-12 col-sm-6">
                    <div class="info-box">
                        <span class="text-muted d-block">
                            <?= $lang === 'en' ? 'Course duration' : 'Kursuse kestus' ?>
                        </span>

                        <strong class="fs-4">
                            <?= htmlspecialchars($n['duration'], ENT_QUOTES, 'UTF-8') ?>
                        </strong>
                    </div>
                </div>

            </div>

            <form action="enroll" method="post" class="mt-3">
                <input
                    type="hidden"
                    name="course_id"
                    value="<?= (int)$n['id'] ?>"
                >

                <button type="submit" class="btn btn-primary btn-lg">
                    <?= $lang === 'en' ? 'Enroll as a student' : 'Registreeru õpilaseks' ?>
                </button>
            </form>

            <div id="enrollment" class="mt-3">

                <?php if (isset($_GET['enrollment'])): ?>

                    <?php
                    $enrollmentMessage = $_SESSION['enrollment_message'] ?? '';
                    unset($_SESSION['enrollment_message']);

                    $isSuccess = $_GET['enrollment'] === 'success';
                    ?>

                    <?php if ($enrollmentMessage !== ''): ?>
                        <div class="alert <?= $isSuccess ? 'alert-success' : 'alert-warning' ?>">
                            <?= htmlspecialchars($enrollmentMessage, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                <?php endif; ?>

            </div>

        </div>
    </article>

    <section class="mt-5" id="reviewtable">

        <h2 class="mb-3">
            <?= $lang === 'en' ? 'Student reviews' : 'Õpilaste tagasiside' ?>
        </h2>

        <?php if (empty($reviews)): ?>

            <p class="text-muted">
                <?= $lang === 'en'
                    ? 'There are no reviews for this course yet.'
                    : 'Selle kursuse kohta pole veel tagasisidet.' ?>
            </p>

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
                                    <?= htmlspecialchars(
                                        $review['username'] ?? ($lang === 'en' ? 'Student' : 'Õpilane'),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
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

                <h3 class="h5">
                    <?= $lang === 'en' ? 'Leave a review' : 'Jäta tagasiside' ?>
                </h3>

                <form action="insertreview" method="post">

                    <input
                        type="hidden"
                        name="id"
                        value="<?= (int)$n['id'] ?>"
                    >

                    <div class="mb-3">

                        <label for="review" class="form-label">
                            <?= $lang === 'en' ? 'Your review' : 'Sinu tagasiside' ?>
                        </label>

                        <textarea
                            class="form-control"
                            id="review"
                            name="review"
                            rows="4"
                            maxlength="2000"
                            required
                            placeholder="<?= $lang === 'en'
                                ? 'Write about your experience...'
                                : 'Kirjuta oma kogemusest...' ?>"
                        ></textarea>

                    </div>

                    <button type="submit" class="btn btn-primary">
                        <?= $lang === 'en' ? 'Submit review' : 'Saada tagasiside' ?>
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
