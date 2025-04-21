<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=kahhve', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $customerId = $user['id'];

    $stmt = $pdo->prepare("DELETE FROM masa WHERE customer_id = ?");
    $stmt->execute([$customerId]);

    echo json_encode(['success' => true]);
}
?>