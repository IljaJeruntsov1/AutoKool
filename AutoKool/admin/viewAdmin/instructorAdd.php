<?php
require_once __DIR__ . '/../../inc/language.php';
$lang = $_SESSION['language'] ?? 'et';
$isEnglish = $lang === 'en';
function instructorEscape($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
$pageTitle = $isEnglish ? 'Add Instructor' : 'Lisa sõiduõpetaja';
$message = $_GET['message'] ?? '';
$formAction = 'create_instructor';
$instructor = [];
?>
<!DOCTYPE html>
<html lang="<?= $isEnglish ? 'en' : 'et' ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>AutoKool — <?= instructorEscape($pageTitle) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/JPTV24/AutoKool/responsive.css">
<style>
    
body {background:#f3f6fb;} .form-card {border:0;border-radius:18px;} .btn {min-height:40px;height:auto;line-height:1.4;} .btn.btn-danger {background:#dc3545;border-color:#dc3545;color:white;} .btn.btn-danger:hover {background:#bb2d3b;border-color:#bb2d3b;} .teacher-img {width:72px;height:76px;object-fit:cover;border-radius:12px;} .teacher-placeholder {width:72px;height:76px;background:#e2e8f0;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:28px;}
</style>
</head>
<body>
<nav class="navbar navbar-dark" style="background:#142238"><div class="container"><a class="navbar-brand fw-bold" href="index.php?page=dashboard">AutoKool Admin</a><a class="btn btn-outline-light" href="index.php?page=instructors"><?= $isEnglish ? 'Instructors' : 'Sõiduõpetajad' ?></a></div></nav>
<main class="container py-5" style="max-width:1050px">
<div class="d-flex justify-content-between align-items-center mb-4"><h1 class="h2 fw-bold"><?= instructorEscape($pageTitle) ?></h1><a href="index.php?page=instructors" class="btn btn-secondary"><?= $isEnglish ? 'Back' : 'Tagasi' ?></a></div>
<?php if ($message !== ''): ?>
<div class="alert <?= $message === 'success' ? 'alert-success' : 'alert-danger' ?>">
<?= instructorEscape($message === 'success' ? ($isEnglish ? 'Instructor added!' : 'Sõiduõpetaja lisatud!') : ($isEnglish ? 'Check the details and photo.' : 'Kontrolli andmeid ja fotot.')) ?>
</div><?php endif; ?>
<div class="card form-card shadow-sm"><div class="card-body p-4 p-md-5">
<form method="POST" action="index.php" enctype="multipart/form-data">
<input type="hidden" name="action" value="<?= instructorEscape($formAction) ?>">
<input type="hidden" name="csrf_token" value="<?= instructorEscape($csrfToken) ?>">
<?php if ($formAction === 'update_instructor'): ?><input type="hidden" name="instructor_id" value="<?= (int)$instructor['id'] ?>"><?php endif; ?>
<div class="mb-3"><label class="form-label fw-semibold" for="teacher_name"><?= $isEnglish ? 'Full name' : 'Ees- ja perekonnanimi' ?></label><input id="teacher_name" class="form-control" type="text" name="name" maxlength="150" value="<?= instructorEscape($instructor['name'] ?? '') ?>" required></div>
<div class="row g-3 mb-3"><div class="col-md-6"><label class="form-label fw-semibold" for="teacher_email">Email</label><input id="teacher_email" type="email" class="form-control" name="email" maxlength="150" value="<?= instructorEscape($instructor['email'] ?? '') ?>"></div>
<div class="col-md-6"><label class="form-label fw-semibold" for="teacher_phone"><?= $isEnglish ? 'Phone' : 'Telefon' ?></label><input id="teacher_phone" type="tel" class="form-control" name="phone" maxlength="30" value="<?= instructorEscape($instructor['phone'] ?? '') ?>"></div></div>
<div class="mb-3"><label class="form-label fw-semibold" for="teacher_description"><?= $isEnglish ? 'Description' : 'Kirjeldus' ?></label><textarea id="teacher_description" name="description" class="form-control" rows="5"><?= instructorEscape($instructor['description'] ?? '') ?></textarea></div>
<div class="mb-4"><label class="form-label fw-semibold" for="teacher_picture"><?= $isEnglish ? 'Instructor photo (JPG, PNG, WebP; max 2 MB)' : 'Sõiduõpetaja foto (JPG, PNG, WebP; kuni 2 MB)' ?></label><input id="teacher_picture" type="file" name="picture" accept="image/jpeg,image/png,image/webp" class="form-control"><img id="photoPreview" class="teacher-img mt-3" alt="Preview" style="display:none"></div>
<div class="d-flex gap-2"><button type="submit" class="btn btn-success"><?= $isEnglish ? 'Save Instructor' : 'Salvesta sõiduõpetaja' ?></button><a class="btn btn-outline-secondary" href="index.php?page=instructors"><?= $isEnglish ? 'Cancel' : 'Tühista' ?></a></div>
</form></div></div>
<script>
const fileInput = document.getElementById('teacher_picture');
const preview = document.getElementById('photoPreview');
fileInput.addEventListener('change', () => {
  const file = fileInput.files[0];
  if (!file || file.size > 2*1024*1024 || !['image/jpeg','image/png','image/webp'].includes(file.type)) {
    preview.style.display='none'; preview.removeAttribute('src');
    if (file) { alert('JPG / PNG / WebP — max 2 MB'); fileInput.value=''; }
    return;
  }
  const reader = new FileReader();
  reader.onload = () => { preview.src = reader.result; preview.style.display='block'; };
  reader.readAsDataURL(file);
});
</script>
</main>
</body>
</html>
