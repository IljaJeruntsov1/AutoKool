
<?php
require_once __DIR__ . '/../inc/language.php';

$lang = $_SESSION['language'] ?? 'et';
$isEnglish = $lang === 'en';

// Фотографии для категорий курсов
$courseImages = [
    1 => 'https://www.une-auto-ecole.com/storage/508369/les-secrets-de-lauto-ecole-pole-position-pour-reussir-votre-permis.jpeg',
    2 => 'https://images.unsplash.com/photo-1685607496071-cba1c322bc15?w=900&q=85',
    3 => 'https://traton.com/.imaging/mte/tab-theme/standardLandscape-L/dam/innovation/images/zusammenarbeit-zukunftsweisend/header.png/jcr:content/story-zusammenarbeit-zukunftsweisend-assets-header.png'
];

ob_start();
?>

<style>
    .home-hero {
        background: linear-gradient(135deg, #14243d, #294a72);
        border-radius: 22px;
        padding: 65px 35px;
        color: white;
        text-align: center;
        margin-bottom: 55px;
    }

    .home-hero h1 {
        font-size: clamp(2rem, 5vw, 3.2rem);
        font-weight: 800;
        margin-bottom: 18px;
    }

    .home-hero p {
        font-size: 1.1rem;
        color: #dce7f5;
        max-width: 620px;
        margin: 0 auto 25px;
        display: block;
    }

    .section-heading {
        font-size: 2rem;
        font-weight: 750;
        margin-bottom: 12px;
    }

    .section-subtitle {
        color: #64748b;
        margin-bottom: 30px;
        display: block;
    }

    .autokool-card {
        border: 1px solid #e8edf2;
        border-radius: 18px;
        overflow: hidden;
        background: white;
        transition: transform 0.3s ease,
                    box-shadow 0.3s ease;
    }

    .autokool-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 18px 40px rgba(0,0,0,0.12) !important;
    }

    .course-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        display: block;
    }

    .course-description {
        color: #64748b;
        line-height: 1.7;
        display: block;
    }

    .course-price {
        color: #1766d1;
        font-size: 1.7rem;
        font-weight: 800;
    }

    .course-duration {
        color: #64748b;
        font-size: 0.95rem;
    }

    .course-button {
        padding: 12px;
        border-radius: 10px;
        font-weight: 600;
        transition: background-color 0.2s ease;
    }

    @media (max-width: 768px) {
        .home-hero {
            padding: 40px 20px;
        }

        .course-image {
            height: 200px;
        }
    }

    .btn {
    min-height: 15px;
    height: auto;
    padding: 5px 20px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    line-height: 1.4;
    text-align: center;

    border-radius: 10px;
    font-weight: 600;
}
</style>

<!-- Hero section -->
<section class="home-hero d-block">
    <h1>
        <?= $isEnglish
            ? 'Your journey starts here'
            : 'Sinu teekond algab siit' ?>
    </h1>

    <p>
        <?= $isEnglish
            ? 'Learn to drive confidently with AutoKool. Explore our driving courses and take the first step towards your driving licence.'
            : 'Õpi sõitma enesekindlalt koos AutoKooliga. Tutvu meie kursustega ja astu esimene samm juhiloa suunas.' ?>
    </p>

    <a href="allcourses" class="btn btn-light btn-lg px-4">
        <?= $isEnglish ? 'Explore courses' : 'Vaata kursuseid' ?>
    </a>
</section>

<!-- Popular courses -->
<section class="d-block p-0">

    <h2 class="section-heading">
        <?= t('popular_courses') ?>
    </h2>

    <p class="section-subtitle">
        <?= $isEnglish
            ? 'Choose the course that suits you best.'
            : 'Vali endale sobivaim sõidukursus.' ?>
    </p>

    <div class="row g-4">

        <?php foreach ($arr as $value): ?>

            <?php
            $title = $isEnglish && !empty($value['title_en'])
                ? $value['title_en']
                : $value['title'];

            $description = $isEnglish && !empty($value['description_en'])
                ? $value['description_en']
                : $value['description'];

            $categoryId = (int)$value['category_id'];

            $image = $courseImages[$categoryId]
                ?? $courseImages[1];

            $duration = $value['duration'];

            // Перевод длительности из базы
            if ($isEnglish) {
                $duration = str_replace(
                    ['kuud', 'kuu'],
                    ['months', 'month'],
                    $duration
                );
            }
            ?>

            <div class="col-12 col-md-6 col-lg-4">

                <article class="card autokool-card h-100 shadow-sm">

                    <img
                        src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>"
                        class="course-image"
                        alt="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>"
                        loading="lazy"
                    >

                    <div class="card-body p-4 d-flex flex-column">

                        <span class="badge text-bg-primary align-self-start mb-3">
                            <?= $categoryId === 1 ? 'B' : ($categoryId === 2 ? 'A' : 'C') ?>
                            <?= $isEnglish ? 'Category' : 'Kategooria' ?>
                        </span>

                        <h3 class="h5 fw-bold mb-3">
                            <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>
                        </h3>

                        <p class="course-description mb-4">
                            <?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <div class="mt-auto">

                            <div class="d-flex justify-content-between align-items-end mb-3">

                                <div>
                                    <div class="text-muted small mb-1">
                                        <?= t('price') ?>
                                    </div>

                                    <div class="course-price">
                                        <?= number_format((float)$value['price'], 0, ',', ' ') ?> €
                                    </div>
                                </div>

                                <div class="text-end">
                                    <div class="text-muted small mb-1">
                                        <?= t('duration') ?>
                                    </div>

                                    <div class="course-duration">
                                        <?= htmlspecialchars($duration, ENT_QUOTES, 'UTF-8') ?>
                                    </div>
                                </div>

                            </div>

                            <a
                                href="course?id=<?= (int)$value['id'] ?>"
                                class="btn btn-primary course-button w-100"
                            >
                                <?= t('view_course') ?> →
                            </a>

                        </div>

                    </div>

                </article>

            </div>

        <?php endforeach; ?>

    </div>

    <div class="text-center mt-5">
        <a href="allcourses" class="btn btn-outline-primary btn-lg px-4">
            <?= $isEnglish ? 'View all courses' : 'Vaata kõiki kursuseid' ?>
        </a>
    </div>

</section>

<?php
$content = ob_get_clean();

include __DIR__ . '/layout.php';
?>
