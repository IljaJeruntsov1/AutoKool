<?php
$lang = $_SESSION['language'] ?? 'et';
$en = $lang === 'en';
$msg = $_GET['message'] ?? '';
$messages = [
  'updated' => $en ? 'Course updated.' : 'Kursus muudetud.',
  'deleted' => $en ? 'Course deleted.' : 'Kursus kustutatud.',
  'notfound' => $en ? 'Course not found.' : 'Kursust ei leitud.',
  'invalid' => $en ? 'Invalid request.' : 'Vigane päring.',
  'error' => $en ? 'Operation failed.' : 'Toiming ebaõnnestus.',
];
?>
<!doctype html>
<html lang="<?= $en ? 'en' : 'et' ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>AutoKool — <?= $en ? 'Manage courses' : 'Kursuste haldamine' ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/JPTV24/AutoKool/responsive.css">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="admin-page-header mb-4">
    <h1 class="h2 mb-0"><?= $en ? 'Manage courses' : 'Kursuste haldamine' ?></h1>
    <div class="admin-page-actions">
      <a class="btn btn-outline-secondary" href="index.php?page=dashboard"><?= $en ? 'Back' : 'Tagasi' ?></a>
      <a class="btn btn-primary" href="index.php?page=courseAdd"><?= $en ? 'Add course' : 'Lisa kursus' ?></a>
    </div>
  </div>
  <?php if (isset($messages[$msg])): ?>
    <div class="alert <?= in_array($msg,['deleted','updated'],true) ? 'alert-success' : 'alert-danger' ?>">
      <?= htmlspecialchars($messages[$msg], ENT_QUOTES, 'UTF-8') ?>
    </div>
  <?php endif; ?>
  <div class="card admin-list-card shadow-sm"><div class="card-body">
    <div class="table-responsive admin-mobile-list"><table class="table table-striped align-middle">
      <thead><tr>
        <th>ID</th><th><?= $en ? 'Course' : 'Kursus' ?></th><th><?= $en ? 'Category' : 'Kategooria' ?></th>
        <th><?= $en ? 'Instructor' : 'Sõiduõpetaja' ?></th><th><?= $en ? 'Price' : 'Hind' ?></th>
        <th><?= $en ? 'Actions' : 'Tegevused' ?></th>
      </tr></thead><tbody>
      <?php foreach ($courses as $c): ?>
        <tr>
          <td data-label="ID"><?= (int)$c['id'] ?></td>
          <td data-label="<?= $en ? 'Course' : 'Kursus' ?>"><?= htmlspecialchars($en && !empty($c['title_en']) ? $c['title_en'] : $c['title'], ENT_QUOTES, 'UTF-8') ?></td>
          <td data-label="<?= $en ? 'Category' : 'Kategooria' ?>"><?= htmlspecialchars($c['category_name'], ENT_QUOTES, 'UTF-8') ?></td>
          <td data-label="<?= $en ? 'Instructor' : 'Sõiduõpetaja' ?>"><?= htmlspecialchars($c['instructor_name'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
          <td data-label="<?= $en ? 'Price' : 'Hind' ?>"><?= number_format((float)$c['price'], 2, ',', ' ') ?> €</td>
          <td data-label="<?= $en ? 'Actions' : 'Tegevused' ?>">
            <a class="btn btn-sm btn-outline-primary" href="index.php?page=courseEdit&id=<?= (int)$c['id'] ?>">
              <?= $en ? 'Edit / Delete' : 'Muuda / Kustuta' ?>
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody></table></div>
      <?php if (empty($courses)): ?><p class="text-muted"><?= $en ? 'No courses.' : 'Kursuseid ei ole.' ?></p><?php endif; ?>
  </div></div>
</div>
</body></html>
