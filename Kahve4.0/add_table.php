<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=kahhve', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $masaId = $data['masa_id'] ?? null;

    if ($masaId && isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        $customerId = $user['id'];
        $customer = $user['username'];

        $stmt = $pdo->prepare("INSERT INTO masa (masa_id, customer_id, customer) VALUES (?, ?, ?)");
        $stmt->execute([$masaId, $customerId, $customer]);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Geçersiz istek.']);
    }
} 
?>