<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=kahhve', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    $coffeeId = $data['coffee_id'];
    $coffeeName = $data['coffee_name'];

    // Kullanıcının oturduğu masanın order_id'sini al
    $userId = $_SESSION['user']['id'];
    $stmt = $pdo->prepare("SELECT order_id FROM masa WHERE customer_id = ?");
    $stmt->execute([$userId]);
    $orderId = $stmt->fetchColumn();

    if ($orderId) {
        $stmt = $pdo->prepare("INSERT INTO orders (order_id, coffee_id, coffee) VALUES (?, ?, ?)");
        $stmt->execute([$orderId, $coffeeId, $coffeeName]);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Masa bulunamadı.']);
    }
}
?>