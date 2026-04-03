<?php
session_start();
require_once '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Παίρνουμε το id από το URL (?id=3)
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
   ?? filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    http_response_code(404);
    die();
}

// SELECT + ownership check — και τα δύο μαζί ΠΑΝΤΑ
$stmt = $pdo->prepare(
    'SELECT * FROM items WHERE id = :id AND user_id = :uid'
);
$stmt->execute([':id' => $id, ':uid' => $user_id]);
$item = $stmt->fetch();

if (!$item) {
    http_response_code(403);
    die('Δεν έχετε πρόσβαση σε αυτή την εγγραφή.');
}

// Pre-fill από τη βάση (GET) ή από $_POST αν υπήρξε error
$errors      = [];
$title       = $item['title'];
$category    = $item['category'];
$description = $item['description'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title       = trim($_POST['title']       ?? '');
    $category    = trim($_POST['category']    ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($title === '')        $errors[] = 'Ο τίτλος είναι υποχρεωτικός.';
    if (strlen($title) < 3)  $errors[] = 'Τίτλος τουλάχιστον 3 χαρακτήρες.';

    $valid = ['tech','science','art','other'];
    if (!in_array($category, $valid)) $errors[] = 'Άκυρη κατηγορία.';

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            'UPDATE items
             SET title = :title, category = :cat, description = :desc
             WHERE id = :id AND user_id = :uid'
        );
        $stmt->execute([
            ':title' => $title,
            ':cat'   => $category,
            ':desc'  => $description,
            ':id'    => $id,
            ':uid'   => $user_id,
        ]);

        $_SESSION['flash_success'] = '✅ Η εγγραφή ενημερώθηκε!';
        header('Location: ../search/index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
  <title>Επεξεργασία — CEI 326</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-primary px-3">
  <span class="navbar-brand">CEI 326 Lab</span>
  <div class="d-flex gap-2">
    <span class="text-white me-3 align-self-center">
      👤 <?= htmlspecialchars($_SESSION['username']) ?>
    </span>
    <a href="../search/index.php" class="btn btn-outline-light btn-sm">📋 Λίστα</a>
    <a href="../../auth/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
  </div>
</nav>

<div class="container mt-4" style="max-width: 640px;">

  <h2 class="mb-4">✏️ Επεξεργασία <small class="text-muted fs-5">#<?= $id ?></small></h2>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="edit.php?id=<?= $id ?>" method="post">

    <div class="mb-3">
      <label class="form-label fw-bold">Τίτλος *</label>
      <input type="text" class="form-control" name="title"
             value="<?= htmlspecialchars($title) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Κατηγορία *</label>
      <select class="form-select" name="category">
        <option value="tech"    <?= $category==='tech'    ? 'selected':'' ?>>Τεχνολογία</option>
        <option value="science" <?= $category==='science' ? 'selected':'' ?>>Επιστήμη</option>
        <option value="art"     <?= $category==='art'     ? 'selected':'' ?>>Τέχνη</option>
        <option value="other"   <?= $category==='other'   ? 'selected':'' ?>>Άλλο</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Περιγραφή</label>
      <textarea class="form-control" name="description" rows="4"><?= htmlspecialchars($description) ?></textarea>
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-warning">💾 Ενημέρωση</button>
      <a href="../search/index.php" class="btn btn-outline-secondary">Ακύρωση</a>
      <button type="button" class="btn btn-outline-danger ms-auto"
              data-bs-toggle="modal" data-bs-target="#deleteModal">
        🗑 Διαγραφή
      </button>
    </div>

  </form>
</div>

<!-- Confirm Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">⚠️ Επιβεβαίωση Διαγραφής</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Διαγραφή της εγγραφής:</p>
        <p class="fw-bold">"<?= htmlspecialchars($item['title']) ?>"</p>
        <p class="text-danger small">Η ενέργεια δεν αναιρείται.</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Ακύρωση</button>
        <form action="delete.php" method="post" class="d-inline">
          <input type="hidden" name="id" value="<?= $id ?>">
          <button type="submit" class="btn btn-danger">🗑 Ναι, Διαγραφή</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>