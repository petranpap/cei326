<?php
session_start();
require_once '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
    exit;
}
?>
<html>
<head>
    <title>Create Item</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid p-5 bg-primary text-white text-center">
    <h1>Create Item</h1>
</div>
<div class="container mt-5">
    <form action="create_item.php" method="POST">
        <div class="form-group">
            <label for="title">Item Name:</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
        <div class="form-group">  
            <label for="price">Category:</label>
            <input type="text" class="form-control" id="category" name="category" required> 

        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category    = trim($_POST['category'] ?? '');  
    $user_id     = $_SESSION['user_id'];
    $_SESSION['flash_message'] = 'Item created successfully.';
    $_SESSION['title'] = $title;
    $_SESSION['description'] = $description;
    $_SESSION['category'] = $category;

    $stmt = $pdo->prepare('INSERT INTO items (title, description, category, user_id) VALUES (:title, :description, :category, :user_id)');
    $stmt->execute([
        ':title'       => $title,
        ':description' => $description, 
        ':category'    => $category,
        ':user_id'     => $user_id
    ]);
    $new_id = $pdo->lastInsertId();
    $_SESSION['new_item_id'] = $new_id;
    header('Location: dashboard.php');
    exit;
}
?>