<?php
/**
 * form_demo.php
 * Παράδειγμα HTML Form με δύο τρόπους submission:
 * 1) Αποστολή σε εξωτερικό αρχείο (post-demo.php)
 * 2) Αποστολή στον εαυτό της (same-page form handling)
 */

// Ελέγχουμε αν η σελίδα κλήθηκε μέσω POST (same-page submit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Διαβάζουμε τα πεδία με null coalescing (??) για ασφάλεια
    // αν δεν υπάρχει το key, επιστρέφει '' αντί για warning
    $name  = $_POST['name']  ?? '';
    $email = $_POST['email'] ?? '';

    // TODO (lab): εδώ κανονικά θα κάναμε validation πριν το echo
    // π.χ. trim(), htmlspecialchars() για αποφυγή XSS
    echo $name;
    echo $email;

} else {
    // Αν η μέθοδος είναι GET (πρώτη φόρτωση σελίδας), εμφανίζουμε τη φόρμα
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <title>Form Demo</title>
    <!-- Bootstrap 4 για γρήγορο styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

<!-- ===== HEADER ===== -->
<div class="container-fluid p-5 bg-primary text-white text-center">
    <h1>Form Demo</h1>
</div>

<!-- 
    ΦΟΡΜΑ 1: Στέλνει δεδομένα σε ΕΞΩΤΕΡΙΚΟ αρχείο (post-demo.php)
    action = πού στέλνονται τα δεδομένα
    method = πώς στέλνονται (POST = στο body, κρυφά από URL)
-->
<form class="container mt-5" action="post-demo.php" method="POST">

    <div class="form-group">
        <label for="name">Name:</label>
        <!-- το attribute "name" είναι κρίσιμο: χωρίς αυτό το πεδίο δεν αποστέλλεται -->
        <input type="text" class="form-control" id="name" placeholder="Enter name" name="name">
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <!-- type="email" κάνει client-side validation μορφής email (δεν αρκεί από μόνο του) -->
        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
    </div>

    <div class="form-group">
        <label for="password">Password:</label>
        <!-- type="password" κρύβει τους χαρακτήρες στην οθόνη -->
        <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<!-- 
    ΦΟΡΜΑ 2: Στέλνει δεδομένα ΣΤΟΝ ΕΑΥΤΟ ΤΗΣ (same-page handling)
    $_SERVER['PHP_SELF'] επιστρέφει το path του τρέχοντος αρχείου
    π.χ. /form_demo.php
    Έτσι το form υποβάλλεται στην ίδια σελίδα που το εμφανίζει
-->
<h1 class="container mt-5">Form Demo Same Page</h1>
<form class="container mt-3" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

    <div class="form-group">
        <label for="name2">Name:</label>
        <input type="text" class="form-control" id="name2" placeholder="Enter name" name="name">
    </div>

    <div class="form-group">
        <label for="email2">Email:</label>
        <input type="email" class="form-control" id="email2" placeholder="Enter email" name="email">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

</body>
</html>

<?php
} // τέλος else — κλείνουμε το PHP block που άνοιξε πριν την HTML
?>