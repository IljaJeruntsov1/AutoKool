<?php
require_once __DIR__ . '/../../inc/language.php';
$lang = $_SESSION['language'] ?? 'et';
$isEnglish = $lang === 'en';
$esc = static fn($value) => htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
$message = $_GET['message'] ?? '';
$messages = [
  'updated' => $isEnglish ? 'Instructor updated!' : 'Sõiduõpetaja andmed on muudetud!',
  'activated' => $isEnglish ? 'Instructor activated!' : 'Sõiduõpetaja aktiveeritud!',
  'deactivated' => $isEnglish ? 'Instructor deactivated!' : 'Sõiduõpetaja deaktiveeritud!',
  'invalid' => $isEnglish ? 'Invalid data.' : 'Vigased andmed.',
  'error' => $isEnglish ? 'Operation failed.' : 'Toiming ebaõnnestus.'
];
?>
<!doctype html>
<html lang="<?= $isEnglish ? 'en' : 'et' ?>">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>AutoKool — <?= $isEnglish ? 'Manage Instructors' : 'Halda sõiduõpetajaid' ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/JPTV24/AutoKool/responsive.css">
<style>
body {background:#f3f6fb;}
.btn.btn-danger {background:#dc3545;border-color:#dc3545;color:#fff;}
.btn.btn-danger:hover {background:#bb2d3b;border-color:#bb2d3b;}
.inactive-row {opacity:.8;}

.teacher-img {
    width: 72px;
    height: 76px;
    object-fit: cover;
    border-radius: 12px;
}
</style>
</head>
<body>
<nav class="navbar navbar-dark" style="background:#142238"><div class="container"><a class="navbar-brand fw-bold" href="index.php?page=dashboard">AutoKool Admin</a><a class="btn btn-outline-light" href="index.php?page=dashboard"><?= $isEnglish ? 'Back' : 'Tagasi' ?></a></div></nav>
<main class="container py-5" style="max-width:1100px">
<div class="admin-page-header mb-4">
 <h1 class="h2 fw-bold m-0"><?= $isEnglish ? 'Manage Instructors' : 'Halda sõiduõpetajaid' ?></h1>
 <div class="admin-page-actions"><a class="btn btn-success" href="index.php?page=instructorAdd">+ <?= $isEnglish ? 'Add Instructor' : 'Lisa sõiduõpetaja' ?></a><a class="btn btn-secondary" href="index.php?page=dashboard"><?= $isEnglish ? 'Back' : 'Tagasi' ?></a></div>
</div>
<?php if (isset($messages[$message])): ?>
 <div class="alert <?= in_array($message,['updated','activated','deactivated'],true) ? 'alert-success':'alert-danger' ?>"><?= $esc($messages[$message]) ?></div>
<?php endif; ?>
<div class="card admin-list-card shadow-sm"><div class="card-body p-4">
<?php if (empty($instructors)): ?>
 <p class="text-muted mb-0"><?= $isEnglish ? 'No instructors found.' : 'Sõiduõpetajaid ei leitud.' ?></p>
<?php else: ?>
<div class="table-responsive admin-mobile-list">
<table class="table table-striped align-middle"><thead><tr>
<th>ID</th><th><?= $isEnglish ? 'Photo':'Foto' ?></th><th><?= $isEnglish ? 'Name':'Nimi' ?></th><th>Email</th><th><?= $isEnglish ? 'Phone':'Telefon' ?></th><th><?= $isEnglish ? 'Status':'Staatus' ?></th><th><?= $isEnglish ? 'Actions':'Tegevused' ?></th>
</tr></thead><tbody>
<?php foreach ($instructors as $instructor): ?>
<?php
$picture=$instructor['picture'] ?? null;
$info=$picture ? @getimagesizefromstring($picture) : false;
$mime=$info !== false ? $info['mime'] : '';
$hasImage=in_array($mime,['image/jpeg','image/png','image/webp'],true);
$isActive=($instructor['status'] ?? 'active') === 'active';
?>
<tr class="<?= $isActive ? '' : 'inactive-row' ?>">
<td data-label="ID"><?= (int)$instructor['id'] ?></td>
<td data-label="<?= $isEnglish ? 'Photo':'Foto' ?>"><?php if ($hasImage): ?><img class="teacher-img" alt="<?= $esc($instructor['name']) ?>" src="data:<?= $esc($mime) ?>;base64,<?= base64_encode($picture) ?>"><?php else: ?><div class="teacher-placeholder">👨‍🏫</div><?php endif; ?></td>
<td class="fw-semibold" data-label="<?= $isEnglish ? 'Name':'Nimi' ?>"><?= $esc($instructor['name']) ?></td>
<td data-label="Email"><?= $esc($instructor['email'] ?? '') ?></td>
<td data-label="<?= $isEnglish ? 'Phone':'Telefon' ?>"><?= $esc($instructor['phone'] ?? '') ?></td>
<td data-label="<?= $isEnglish ? 'Status':'Staatus' ?>"><span class="badge <?= $isActive ? 'bg-success':'bg-secondary' ?>"><?= $isActive ? ($isEnglish ? 'Active':'Aktiivne') : ($isEnglish ? 'Inactive':'Mitteaktiivne') ?></span></td>
<td data-label="<?= $isEnglish ? 'Actions':'Tegevused' ?>">
<div class="admin-action-buttons">
<a class="btn btn-primary btn-sm" href="index.php?page=instructorEdit&amp;id=<?= (int)$instructor['id'] ?>"><?= $isEnglish ? 'Edit':'Muuda' ?></a>
<form action="index.php" method="POST">
<input type="hidden" name="action" value="change_instructor_status">
<input type="hidden" name="instructor_id" value="<?= (int)$instructor['id'] ?>">
<input type="hidden" name="status" value="<?= $isActive ? 'inactive':'active' ?>">
<input type="hidden" name="csrf_token" value="<?= $esc($csrfToken) ?>">
<button class="btn <?= $isActive ? 'btn-danger':'btn-success' ?> btn-sm" type="submit"><?= $isActive ? ($isEnglish ? 'Deactivate':'Deaktiveeri') : ($isEnglish ? 'Activate':'Aktiveeri') ?></button>
</form>
</div></td></tr>
<?php endforeach; ?>
</tbody></table></div>
<?php endif; ?>
</div></div>
</main>
</body></html>
