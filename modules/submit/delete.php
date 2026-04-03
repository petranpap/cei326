<?php
session_start();
require_once '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    http_response_code(404);
    die();
}

$stmt = $pdo->prepare('DELETE FROM items WHERE id = :id AND user_id = :uid');
$stmt->execute([':id' => $id, ':uid' => $user_id]);

$_SESSION['flash_success'] = '✅ Η εγγραφή διαγράφηκε!';
header('Location: ../search/index.php');
exit;