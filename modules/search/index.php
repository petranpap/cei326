<?php
session_start();
require_once '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Flash messages
$flash_success = $_SESSION['flash_success'] ?? '';
$flash_error   = $_SESSION['flash_error']   ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

// Keyword search (GET)
$keyword = trim($_GET['keyword'] ?? '');

if ($keyword !== '') {
    $stmt = $pdo->prepare(
        "SELECT items.*, users.username
         FROM items
         JOIN users ON items.user_id = users.id
         WHERE items.title LIKE :kw OR items.description LIKE :kw
         ORDER BY items.created_at DESC"
    );
    $stmt->execute([':kw' => '%' . $keyword . '%']);
} else {
    $stmt = $pdo->prepare(
        "SELECT items.*, users.username
         FROM items
         JOIN users ON items.user_id = users.id
         WHERE items.user_id = :uid
         ORDER BY items.created_at DESC"
    );
    $stmt->execute([':uid' => $user_id]
    );
}

$items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="el">
<head>
  <title>Λίστα — CEI 326</title>
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
    <a href="../submit/create.php" class="btn btn-success btn-sm">➕ Νέα Εγγραφή</a>
    <a href="../../auth/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
  </div>
</nav>

<div class="container mt-4">

  <h2 class="mb-3">📋 Εγγραφές</h2>

  <?php if ($flash_success !== ''): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flash_success) ?></div>
  <?php endif; ?>
  <?php if ($flash_error !== ''): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($flash_error) ?></div>
  <?php endif; ?>

  <form action="index.php" method="get" class="d-flex gap-2 mb-4">
    <input type="text" class="form-control" name="keyword"
           placeholder="Αναζήτηση..." value="<?= htmlspecialchars($keyword) ?>">
    <button type="submit" class="btn btn-primary">🔍</button>
    <?php if ($keyword !== ''): ?>
      <a href="index.php" class="btn btn-outline-secondary">✕</a>
    <?php endif; ?>
  </form>

  <?php if (empty($items)): ?>
    <div class="alert alert-info">
      Δεν υπάρχουν εγγραφές. <a href="../submit/create.php">Προσθέστε την πρώτη!</a>
    </div>
  <?php else: ?>
    <table class="table table-striped table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Τίτλος</th>
          <th>Κατηγορία</th>
          <th>Χρήστης</th>
          <th>Ημ/νία</th>
          <th>Ενέργειες</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
          <td><?= $item['id'] ?></td>
          <td><?= htmlspecialchars($item['title']) ?></td>
          <td><span class="badge bg-secondary"><?= htmlspecialchars($item['category']) ?></span></td>
          <td><?= htmlspecialchars($item['username']) ?></td>
          <td><small><?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></small></td>
          <td>
            <?php if ((int)$item['user_id'] === (int)$user_id): ?>
              <a href="../submit/edit.php?id=<?= $item['id'] ?>"
                 class="btn btn-warning btn-sm">✏️ Edit</a>
            <?php else: ?>
              <span class="text-muted">—</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>





