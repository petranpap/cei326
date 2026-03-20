<?php
session_start();
require_once 'includes/db.php';
$error = ''; $email = '';
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password =      $_POST['password'] ?? '';

 
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();
 
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['role']     = $user['role'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email']    = $user['email'];
        $_SESSION['flash_message'] = 'Login successful. Welcome, ' . htmlspecialchars($user['username']) . '!';
        header('Location: modules/'.$user['role'].'/dashboard.php');
        exit;
    } else {
        $error = 'Λανθασμένα στοιχεία σύνδεσης.';
        echo $error;
        $hash = password_hash($password, PASSWORD_DEFAULT);
        echo "<br> Password hash: $hash <br>";
        echo $user['password_hash'];

    }
}else{

?>
<?php
require_once "includes/header.php";
?>

<body>
<!-- ===== HEADER ===== -->
<div class="container-fluid p-5 bg-primary text-white text-center">
    <h1>Login</h1>
</div>    

<h1 class="container mt-5">Login</h1>
<form class="container mt-3" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

    <div class="form-group">
        <label for="name2">Email:</label>
        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
    </div>

    <div class="form-group">
        <label for="email2">Password:</label>
        <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<a href="register.php" class="container mt-3">Don't have an account? Register here.</a>

</body>
</html>

<?php
} // τέλος else — κλείνουμε το PHP block που άνοιξε πριν την HTML
?>

