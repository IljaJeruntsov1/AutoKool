<?php
$lang = $_SESSION['language'] ?? 'et';
$isEnglish = $lang === 'en';
$e = static fn($value) => htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
$myCourses = array_values(array_filter($courses, static fn($course) => $course['registration_status'] !== null));
$labels = [
    'pending' => $isEnglish ? 'Pending' : 'Ootel',
    'confirmed' => $isEnglish ? 'Confirmed' : 'Kinnitatud',
    'cancelled' => $isEnglish ? 'Cancelled' : 'Tühistatud',
];
ob_start();
?>
<style>
.profile-wrap {max-width:1050px;margin:auto}
.profile-card {border:0;border-radius:18px;overflow:hidden}
.profile-info {background:#f4f7fb;border-radius:14px;padding:20px}
.profile-course {display:flex;gap:14px;align-items:center;justify-content:space-between;border:1px solid #e5eaf2;border-radius:14px;padding:16px;margin-bottom:12px;flex-wrap:wrap}
.profile-course-title {font-size:17px;font-weight:700;margin-bottom:4px}
.profile-danger {border:1px solid #fecaca;background:#fff8f8;border-radius:16px;padding:24px}
.profile-wrap .btn {height:auto;min-height:42px;padding:8px 18px;line-height:1.4}
.profile-wrap .btn-danger {background:#dc3545;border-color:#dc3545;color:#fff}
.profile-wrap .btn-danger:hover {background:#bb2d3b;color:#fff}
@media(max-width:575px) {.profile-course {align-items:stretch}.profile-course .btn {width:100%}.profile-danger {padding:16px}}
</style>
<div class="profile-wrap">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <h1 class="fw-bold mb-0"><?= $isEnglish ? 'My profile' : 'Minu profiil' ?></h1>
        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-outline-primary" href="allcourses"><?= $isEnglish ? 'Browse courses' : 'Vaata kursuseid' ?></a>
            <a class="btn btn-danger" href="#delete-profile"><?= $isEnglish ? 'Delete profile' : 'Kustuta profiil' ?></a>
        </div>
    </div>
    <div class="card shadow-sm profile-card mb-4"><div class="card-body p-4">
        <h2 class="h5 fw-bold mb-3"><?= $isEnglish ? 'Personal information' : 'Isikuandmed' ?></h2>
        <div class="profile-info">
            <div class="mb-2"><strong><?= $isEnglish ? 'Name' : 'Nimi' ?>:</strong> <?= $e($user['username']) ?></div>
            <div><strong>Email:</strong> <?= $e($user['email']) ?></div>
        </div>
    </div></div>
    <div class="card shadow-sm profile-card mb-4"><div class="card-body p-4">
        <h2 class="h4 fw-bold mb-3"><?= $isEnglish ? 'My course applications' : 'Minu kursustele registreerumised' ?></h2>
        <?php if (!$myCourses): ?>
            <p class="text-muted mb-0"><?= $isEnglish ? 'You have not applied for any courses yet.' : 'Sa ei ole veel ühelegi kursusele registreerunud.' ?></p>
        <?php else: ?>
            <?php foreach ($myCourses as $course): ?>
                <?php $name = $isEnglish && !empty($course['title_en']) ? $course['title_en'] : $course['title'];
                $status = $course['registration_status']; ?>
                <div class="profile-course">
                    <div style="min-width:0">
                        <div class="profile-course-title"><?= $e($name) ?></div>
                        <div class="text-muted small"><?= $e($course['price']) ?> € · <?= $e($course['duration']) ?></div>
                        <span class="badge mt-2 <?= $status === 'confirmed' ? 'bg-success' : ($status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') ?>"><?= $e($labels[$status] ?? $status) ?></span>
                    </div>
                    <a class="btn btn-outline-primary" href="course?id=<?= (int)$course['id'] ?>"><?= $isEnglish ? 'Open course' : 'Ava kursus' ?></a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div></div>
    <div class="card shadow-sm profile-card mb-4"><div class="card-body p-4">
        <h2 class="h4 fw-bold mb-3"><?= $isEnglish ? 'All available courses' : 'Kõik kursused' ?></h2>
        <?php if (!$courses): ?>
            <p class="text-muted"><?= $isEnglish ? 'No courses available.' : 'Kursuseid ei leitud.' ?></p>
        <?php endif; ?>
        <?php foreach ($courses as $course): ?>
            <?php $name = $isEnglish && !empty($course['title_en']) ? $course['title_en'] : $course['title']; ?>
            <div class="profile-course">
                <div style="min-width:0">
                    <div class="profile-course-title"><?= $e($name) ?></div>
                    <div class="text-muted small"><?= $e($course['price']) ?> € · <?= $e($course['duration']) ?></div>
                    <?php if ($course['registration_status'] !== null): ?>
                        <span class="badge bg-secondary mt-2"><?= $e($labels[$course['registration_status']] ?? $course['registration_status']) ?></span>
                    <?php endif; ?>
                </div>
                <a class="btn btn-primary" href="course?id=<?= (int)$course['id'] ?>"><?= $isEnglish ? 'View course' : 'Vaata kursust' ?></a>
            </div>
        <?php endforeach; ?>
    </div></div>
    <div id="delete-profile" class="profile-danger mt-5">
        <h2 class="h4 fw-bold text-danger"><?= $isEnglish ? 'Delete profile' : 'Kustuta profiil' ?></h2>
        <p><?= $isEnglish
            ? 'Your applications and linked reviews will be erased. Email, password and registration date will be removed. Only your name, technical ID and deleted status will remain. This cannot be undone.'
            : 'Sinu registreeringud ja kontoga seotud tagasiside kustutatakse. E-post, parool ja registreerimise kuupäev eemaldatakse. Alles jäävad ainult nimi, tehniline ID ja kustutatud staatus. Toimingut ei saa tagasi võtta.' ?></p>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error === 'password'
                ? ($isEnglish ? 'Incorrect or missing password.' : 'Vale või puuduv parool.')
                : ($isEnglish ? 'Could not delete profile. Try again.' : 'Profiili kustutamine ebaõnnestus.') ?></div>
        <?php endif; ?>
        <form action="profile.php" method="POST" onsubmit="return confirm(<?= $e(json_encode($isEnglish ? 'Delete your profile permanently?' : 'Kas soovid oma profiili jäädavalt kustutada?', JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP)) ?>);">
            <input type="hidden" name="action" value="delete_profile">
            <input type="hidden" name="csrf_token" value="<?= $e($_SESSION['profile_csrf_token']) ?>">
            <label for="delete-password" class="form-label fw-semibold"><?= $isEnglish ? 'Confirm with password' : 'Kinnita parooliga' ?></label>
            <input id="delete-password" name="password" type="password" class="form-control mb-3" autocomplete="current-password" required style="max-width:400px">
            <button type="submit" class="btn btn-danger"><?= $isEnglish ? 'Delete my profile' : 'Kustuta minu profiil' ?></button>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
