<?php
$pdo = new PDO('mysql:host=localhost;dbname=kahhve', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $orderId = $data['order_id'] ?? null; // Frontend'den gönderilen id

    if ($orderId) {
        // orders tablosundan id'ye göre sil
        $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
        $result = $stmt->execute([$orderId]);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Silme işlemi başarısız.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Geçersiz id.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Geçersiz istek yöntemi.']);
}
?>
