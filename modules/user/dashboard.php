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
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body> 
<div class="container-fluid p-5 bg-primary text-white text-center">
    <h1>Dashboard</h1>
</div>
<div class="container mt-5">
    <h2> <?php echo $_SESSION['flash_message'] ?? ''; ?></h2>
    <?php unset($_SESSION['flash_message']); ?>

    <?php 
    if (isset($_SESSION['title'])) {
        echo "<h3>Last Created Item:". $_SESSION['new_item_id'] ."</h3>";
        echo "<p><strong>Title:</strong> " . htmlspecialchars($_SESSION['title']) . "</p>";
        echo "<p><strong>Description:</strong> " . htmlspecialchars($_SESSION['description']) . "</p>";
        echo "<p><strong>Category:</strong> " . htmlspecialchars($_SESSION['category']) . "</p>";
        unset($_SESSION['title'], $_SESSION['description'], $_SESSION['category']);
    }
    ?>

    <a href="../../logout.php" class="btn btn-primary">Logout</a>
</div>
</body>
</html>