<?php
$pdo = new PDO('mysql:host=localhost;dbname=kahhve', 'root', '');

$page = $_GET['page'] ?? null;

if ($page) {
    $stmt = $pdo->prepare("SELECT id, name, image_url, fiyat FROM coffee_cards WHERE page = ?");
    $stmt->execute([$page]);
    $coffees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($coffees);
} else {
    echo json_encode([]);
}
?>
