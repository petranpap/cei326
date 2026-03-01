<?php
/**
 * get-demo.php
 * Λαμβάνει παραμέτρους μέσω GET (query string) και τις εμφανίζει.
 *
 * Παράδειγμα κλήσης:
 * get-demo.php?keyword=teacher&year=2025&role=admin
 *
 * Χρησιμοποιείται για: αναζήτηση, φίλτρα, pagination
 */

// Φορτώνουμε το header (HTML head, Bootstrap κτλ) από κοινό αρχείο
require_once "includes/header.php";
?>

<body>

<!-- Header bar -->
<div class="container-fluid p-5 bg-primary text-white text-center">
    <h1>Search Results</h1>
</div>

<div class="container-fluid">

<?php
// Ενεργοποιούμε εμφάνιση errors — ΜΟΝΟ για development, ΟΧΙ σε production
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// --- ΔΙΑΒΑΣΜΑ GET ΠΑΡΑΜΕΤΡΩΝ ---

// Διαβάζουμε το keyword ως string
// ΠΡΟΣΟΧΗ: δεν χρησιμοποιούμε ?? εδώ — αν λείπει η παράμετρος θα πάρουμε warning
// (Καλύτερο pattern: $keyword = trim($_GET['keyword'] ?? ''); )
$keyword = $_GET['keyword'];

// Κάνουμε type casting σε integer για ασφάλεια
// Ακόμα κι αν ο χρήστης στείλει ?year=abc → (int)"abc" = 0
$year = (int)$_GET['year'];

// Διαβάζουμε το role ως string
$role = $_GET['role'];

// --- VALIDATION ---
// Ελέγχουμε αν κάποια παράμετρος είναι κενή ή μηδέν
// Αν ναι, σταματάμε εκτέλεση και εμφανίζουμε μήνυμα λάθους
if ($keyword === '' || $year === 0 || $role === '') {
    echo "<h1>Invalid input. Please provide all parameters.</h1>";
    exit; // σταματάμε εδώ — ο,τιδήποτε μετά δεν εκτελείται
}

// --- OUTPUT ---
// Εμφανίζουμε τα αποτελέσματα με Bootstrap grid
// TODO: στην πράξη θα κάναμε htmlspecialchars() για αποφυγή XSS
echo '<div class="row"><div class="col-sm"><p>Keyword: ' . $keyword . '</p></div></div>';
echo "<p>Year: $year</p>";
echo "<p>Role: $role</p>";
?>

</div>
</body>
</html>