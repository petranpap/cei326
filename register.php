<?php
require_once 'includes/db.php';
$errors = [];
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password']      ?? '';
    $confirm  = $_POST['confirm']       ?? '';
 
    if ($username === '')  $errors[] = 'Username υποχρεωτικό.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Μη έγκυρο email.';
    if (strlen($password) < 8) $errors[] = 'Κωδικός τουλάχιστον 8 χαρακτήρες.';
    if ($password !== $confirm)  $errors[] = 'Οι κωδικοί δεν ταιριάζουν.';
 
    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :e');
        $stmt->execute([':e' => $email]);
        if ($stmt->fetch()) $errors[] = 'Το email χρησιμοποιείται ήδη.';
    }
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (username,email,password_hash) VALUES (:u,:e,:h)');
        $stmt->execute([':u'=>$username, ':e'=>$email, ':h'=>$hash]);
        header('Location: index.php'); exit;
    }

    echo '<div class="container mt-3"><div class="alert alert-danger"><ul>';
    foreach ($errors as $error) {
        echo "<li>$error</li>"; 
    }
    echo '</ul></div></div>';
}
?>
<?php
require_once "includes/header.php";
?>                               
<body>      

<div class="container-fluid p-5 bg-primary text-white text-center">
    <h1>Register</h1>
</div>
<form class="container mt-5" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" id="username" placeholder="Enter username" name="username">
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
    </div>

    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
    </div>

    <div class="form-group">
        <label for="confirm">Confirm Password:</label>
        <input type="password" class="form-control" id="confirm" placeholder="Confirm password" name="confirm">
    </div>

    <button type="submit" class="btn btn-primary">Register</button>
</form>
<a href="index.php" class="container mt-3">Already have an account? Login here.</a>
</body>
</html>

