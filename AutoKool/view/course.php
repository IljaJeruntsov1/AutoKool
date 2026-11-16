
<?php
require_once __DIR__ . '/../inc/language.php';

$lang = $_SESSION['language'] ?? 'et';
$isEnglish = $lang === 'en';

ob_start();
?>

<style>
    .course-detail {
        border-radius: 18px;
        overflow: hidden;
    }

    .info-box {
        background: #f4f7fb;
        border-radius: 12px;
        padding: 20px;
        height: 100%;
    }

    .instructor-card {
        background: #f5f8fc;
        border: 1px solid #e5ebf2;
        border-radius: 18px;
        padding: 25px;
        margin: 30px 0;
    }

    .instructor-photo {
        width: 160px;
        height: 180px;
        object-fit: cover;
        border-radius: 14px;
        flex-shrink: 0;
    }

    .instructor-placeholder {
        width: 160px;
        height: 180px;
        background: #e1e8f0;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 55px;
        flex-shrink: 0;
    }

    .instructor-name {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .instructor-description {
        color: #64748b;
        line-height: 1.7;
        margin-bottom: 15px;
    }

    .course-action {
        min-height: 48px;
        height: auto;
        padding: 12px 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1.4;
        border-radius: 10px;
        font-weight: 600;
    }

    .review-card {
        border-radius: 14px;
    }

    @media (max-width: 576px) {
        .instructor-photo,
        .instructor-placeholder {
            width: 100%;
            max-width: 220px;
            height: 220px;
        }

        .instructor-card {
            padding: 18px;
        }
    }

    .btn.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.btn.btn-danger:hover {
    background-color: #bb2d3b;
    border-color: #b02a37;
    color: white;
}
</style>

<?php if (empty($n)): ?>

    <div class="alert alert-warning">
        <?= $isEnglish
            ? 'Course not found.'
            : 'Kursust ei leitud.' ?>
    </div>

    <a href="allcourses" class="btn btn-primary">
        <?= $isEnglish
            ? 'Back to courses'
            : 'Tagasi kursustele' ?>
    </a>

<?php else: ?>

    <?php
    $title = $isEnglish && !empty($n['title_en'])
        ? $n['title_en']
        : $n['title'];

    $description = $isEnglish && !empty($n['description_en'])
        ? $n['description_en']
        : $n['description'];

    $duration = $n['duration'];

    if ($isEnglish) {
        $duration = str_replace(
            ['kuud', 'kuu'],
            ['months', 'month'],
            $duration
        );
    }

    // Данные преподавателя
    $instructorName = $n['instructor_name'] ?? null;
    $instructorDescription = $n['instructor_description'] ?? '';
    $instructorEmail = $n['instructor_email'] ?? '';
    $instructorPhone = $n['instructor_phone'] ?? '';
    $instructorPicture = $n['instructor_picture'] ?? null;

    // Определяем формат фотографии
    $imageMime = null;

    if (!empty($instructorPicture)) {
        $imageInfo = @getimagesizefromstring(
            $instructorPicture
        );

        if ($imageInfo !== false) {
            $imageMime = $imageInfo['mime'];
        }
    }

    $allowedImageTypes = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];
    ?>

    <!-- Back button -->
    <div class="mb-4">
        <a href="allcourses" class="text-decoration-none">
            ← <?= $isEnglish
                ? 'All courses'
                : 'Kõik kursused' ?>
        </a>
    </div>

    <!-- Course details -->
    <article class="card shadow-sm border-0 course-detail">

        <div class="card-body p-4 p-md-5">

            <span class="badge text-bg-primary mb-3">
                AutoKool
            </span>

            <h1 class="mb-3 fw-bold">
                <?= htmlspecialchars(
                    $title,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </h1>

            <p class="fs-5 text-muted">
                <?= nl2br(htmlspecialchars(
                    $description,
                    ENT_QUOTES,
                    'UTF-8'
                )) ?>
            </p>

            <!-- Price and duration -->
            <div class="row g-3 my-4">

                <div class="col-12 col-sm-6">
                    <div class="info-box">

                        <span class="text-muted d-block mb-2">
                            <?= $isEnglish
                                ? 'Course price'
                                : 'Kursuse hind' ?>
                        </span>

                        <strong class="fs-3 text-primary">
                            <?= number_format(
                                (float)$n['price'],
                                2,
                                ',',
                                ' '
                            ) ?> €
                        </strong>

                    </div>
                </div>

                <div class="col-12 col-sm-6">
                    <div class="info-box">

                        <span class="text-muted d-block mb-2">
                            <?= $isEnglish
                                ? 'Course duration'
                                : 'Kursuse kestus' ?>
                        </span>

                        <strong class="fs-3">
                            <?= htmlspecialchars(
                                $duration,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </strong>

                    </div>
                </div>

            </div>

            <!-- Instructor -->
            <?php if (!empty($instructorName)): ?>

                <div class="instructor-card">

                    <h2 class="h4 fw-bold mb-4">
                        <?= $isEnglish
                            ? 'Your instructor'
                            : 'Sinu sõiduõpetaja' ?>
                    </h2>

                    <div class="d-flex flex-column flex-sm-row gap-4">

                        <!-- Instructor photo -->
                        <?php if (
                            !empty($instructorPicture) &&
                            in_array(
                                $imageMime,
                                $allowedImageTypes,
                                true
                            )
                        ): ?>

                            <img
                                class="instructor-photo"
                                src="data:<?= $imageMime ?>;base64,<?= base64_encode($instructorPicture) ?>"
                                alt="<?= htmlspecialchars(
                                    $instructorName,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >

                        <?php else: ?>

                            <div class="instructor-placeholder">
                                👨‍🏫
                            </div>

                        <?php endif; ?>

                        <!-- Instructor information -->
                        <div class="flex-grow-1">

                            <h3 class="instructor-name">
                                <?= htmlspecialchars(
                                    $instructorName,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </h3>

                            <span class="badge bg-primary mb-3">
                                <?= $isEnglish
                                    ? 'Driving instructor'
                                    : 'Sõiduõpetaja' ?>
                            </span>

                            <?php if (!empty($instructorDescription)): ?>

                                <p class="instructor-description">
                                    <?= nl2br(htmlspecialchars(
                                        $instructorDescription,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    )) ?>
                                </p>

                            <?php endif; ?>

                            <?php if (!empty($instructorEmail)): ?>

                                <p class="mb-2">
                                    <strong>Email:</strong>

                                    <a href="mailto:<?= htmlspecialchars(
                                        $instructorEmail,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>">
                                        <?= htmlspecialchars(
                                            $instructorEmail,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </a>
                                </p>

                            <?php endif; ?>

                            <?php if (!empty($instructorPhone)): ?>

                                <p class="mb-0">
                                    <strong>
                                        <?= $isEnglish
                                            ? 'Phone:'
                                            : 'Telefon:' ?>
                                    </strong>

                                    <?= htmlspecialchars(
                                        $instructorPhone,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </p>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            <?php endif; ?>

            <!-- Enrollment -->
           
<!-- Enrollment -->

<div class="mt-4">

    <?php if (
        !empty($myRegistration) &&
        $myRegistration['status'] === 'pending'
    ): ?>

        <div class="alert alert-info">
            <?= $isEnglish
                ? 'Your application is pending approval.'
                : 'Sinu registreering ootab kinnitamist.' ?>
        </div>

        <form action="cancelenroll" method="POST">

            <input
                type="hidden"
                name="course_id"
                value="<?= (int)$n['id'] ?>"
            >

            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars(
                    $_SESSION['enrollment_csrf_token'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >

            <button
                type="submit"
                class="btn btn-danger course-action" >

                <?= $isEnglish
                    ? 'Cancel application'
                    : 'Tühista registreering' ?>

            </button>

        </form>

    <?php elseif (
        !empty($myRegistration) &&
        $myRegistration['status'] === 'confirmed'
    ): ?>

        <div class="alert alert-success">
            <?= $isEnglish
                ? 'Your registration has been confirmed.'
                : 'Sinu registreering on kinnitatud.' ?>
        </div>


<?php elseif (
    !empty($myRegistration) &&
    $myRegistration['status'] === 'cancelled'
): ?>


    <form action="restoreenroll" method="POST">

        <input
            type="hidden"
            name="course_id"
            value="<?= (int)$n['id'] ?>"
        >

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars(
                $_SESSION['enrollment_csrf_token'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

        <button
            type="submit"
            class="btn btn-success course-action">

            <?= $isEnglish
                ? 'Re-enroll in course'
                : 'Registreeru uuesti kursusele' ?>

        </button>

    </form>






    <?php else: ?>

        <form action="enroll" method="POST">

            <input
                type="hidden"
                name="course_id"
                value="<?= (int)$n['id'] ?>"
            >

            <button
                type="submit"
                class="btn btn-primary btn-lg course-action">

                <?= $isEnglish
                    ? 'Enroll as a student'
                    : 'Registreeru õpilaseks' ?>

            </button>

        </form>

    <?php endif; ?>

</div>


            <!-- Enrollment messages -->
            <div id="enrollment" class="mt-3">

                <?php if (isset($_GET['enrollment'])): ?>

                    <?php
                    $enrollmentMessage =
                        $_SESSION['enrollment_message'] ?? '';

                    unset($_SESSION['enrollment_message']);

                $isSuccess = in_array(
                    $_GET['enrollment'],
                    ['success', 'cancelled'],
                    true
                );
                ?>

                    <?php if ($enrollmentMessage !== ''): ?>

                        <div class="alert <?= $isSuccess
                            ? 'alert-success'
                            : 'alert-warning' ?>">

                            <?= htmlspecialchars(
                                $enrollmentMessage,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </div>

                    <?php endif; ?>

                <?php endif; ?>

            </div>

        </div>
    </article>

    <!-- Reviews -->
    <section class="mt-5 d-block" id="reviewtable">

        <h2 class="mb-4 fw-bold">
            <?= $isEnglish
                ? 'Student reviews'
                : 'Õpilaste tagasiside' ?>
        </h2>

        <?php if (empty($reviews)): ?>

            <p class="text-muted">
                <?= $isEnglish
                    ? 'There are no reviews for this course yet.'
                    : 'Selle kursuse kohta pole veel tagasisidet.' ?>
            </p>

        <?php else: ?>

            <div class="row g-3">

                <?php foreach ($reviews as $review): ?>

                    <div class="col-12">

                        <div class="card border-0 shadow-sm review-card">

                            <div class="card-body p-4">

                                <p class="mb-3">
                                    <?= nl2br(htmlspecialchars(
                                        $review['text'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    )) ?>
                                </p>

                                <small class="text-muted">

                                    <?= htmlspecialchars(
                                        $review['username']
                                            ?? ($isEnglish
                                                ? 'Student'
                                                : 'Õpilane'),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                    ·

                                    <?= htmlspecialchars(
                                        $review['date'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </small>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

        <!-- Add review -->
        <div class="card shadow-sm border-0 mt-4 review-card">

            <div class="card-body p-4">

                <h3 class="h5 fw-bold mb-3">
                    <?= $isEnglish
                        ? 'Leave a review'
                        : 'Jäta tagasiside' ?>
                </h3>

                <form action="insertreview" method="post">

                    <input
                        type="hidden"
                        name="id"
                        value="<?= (int)$n['id'] ?>"
                    >

                    <div class="mb-3">

                        <label for="review" class="form-label">
                            <?= $isEnglish
                                ? 'Your review'
                                : 'Sinu tagasiside' ?>
                        </label>

                        <textarea
                            class="form-control"
                            id="review"
                            name="review"
                            rows="4"
                            maxlength="2000"
                            required
                            placeholder="<?= $isEnglish
                                ? 'Write about your experience...'
                                : 'Kirjuta oma kogemusest...' ?>"
                        ></textarea>

                    </div>

                    <button type="submit"
                            class="btn btn-primary course-action">

                        <?= $isEnglish
                            ? 'Submit review'
                            : 'Saada tagasiside' ?>

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
