<?php
/**
 * post-demo.php
 * Δέχεται δεδομένα από HTML form μέσω POST method.
 *
 * Κανόνας: αυτό το αρχείο δέχεται ΜΟΝΟ POST requests.
 * Αν κάποιος προσπαθήσει να το ανοίξει απευθείας (GET),
 * επιστρέφουμε HTTP 404 και σταματάμε.
 *
 * Τα δεδομένα έρχονται από: form_demo.php (action="post-demo.php")
 */

// --- METHOD GUARD ---
// Αν η μέθοδος δεν είναι POST, αρνούμαστε να εξυπηρετήσουμε
// http_response_code(405) = Method Not Allowed (σωστότερο από 404)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(404); // ή 405 Method Not Allowed
    exit; // σταματάμε αμέσως — δεν εκτελείται τίποτα παρακάτω
}

// --- ΔΙΑΒΑΣΜΑ POST ΔΕΔΟΜΕΝΩΝ ---
// Τα δεδομένα βρίσκονται στο HTTP body (ΔΕΝ φαίνονται στο URL)
// Το key αντιστοιχεί στο attribute name="..." της φόρμας
$name     = $_POST['name'];
$email    = $_POST['email'];
$password = $_POST['password'];

// TODO (lab): Πριν χρησιμοποιήσουμε τα δεδομένα, πρέπει:
// 1. trim()             → αφαίρεση κενών
// 2. htmlspecialchars() → προστασία από XSS
// 3. filter_var()       → validation email
// 4. password_hash()    → ΠΟΤΕ δεν αποθηκεύουμε plain-text password

// --- OUTPUT ---
// Απλή επιβεβαίωση προς τον χρήστη
// ΠΡΟΣΟΧΗ: Στην παραγωγή ΠΟΤΕ δεν εμφανίζουμε τον κωδικό!
echo "<h1>Thank you, $name!</h1>";
echo "<p>Your email address: $email has been received.</p>";
echo "<p>Your password is: $password</p>"; // ← αυτό υπάρχει μόνο για demo!
?>