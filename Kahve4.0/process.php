<?php
$pdo = new PDO('mysql:host=localhost;dbname=kahhve', 'root', ''); // DB bağlantısı

$action = $_POST['action'] ?? null;

if ($action === 'add') {
    // Yeni kart ekle
    $stmt = $pdo->prepare("INSERT INTO coffee_cards (description, image_url, page, name, category, fiyat, stok) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['description'], $_POST['image_url'], $_POST['page'], $_POST['name'], $_POST['category'], $_POST['fiyat'], $_POST['stok']]);
    header("Location: AddCoffe.php");

} elseif ($action === 'delete') {
    // Kart sil
    $stmt = $pdo->prepare("DELETE FROM coffee_cards WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    header("Location: AddCoffe.php");

} elseif ($action === 'edit') {
    
    // Stok düzenleme işlemi
    $id = $_POST['id'];

    // Değerleri güvenli bir şekilde al ve integer'a dönüştür
    $stokAdd = isset($_POST['stokAdd']) ? (int)$_POST['stokAdd'] : 0;
    $stokRemove = isset($_POST['stokRemove']) ? (int)$_POST['stokRemove'] : 0;
    $newStok = isset($_POST['stok']) && $_POST['stok'] !== '' ? (int)$_POST['stok'] : null;

    // Mevcut stok değeri
    $stmt = $pdo->prepare("SELECT stok FROM coffee_cards WHERE id = ?");
    $stmt->execute([$id]);
    $currentStok = (int)$stmt->fetchColumn(); // Integer olarak al

    if ($newStok !== null) {
        // Manuel stok güncellemesi
        $finalStok = max(0, $newStok);
    } else {
        // Ekle/Çıkar işlemleri
        $finalStok = max(0, $currentStok + $stokAdd - $stokRemove);
    }

    // Stok güncelle
    $stmt = $pdo->prepare("UPDATE coffee_cards SET stok = ? WHERE id = ?");
    $stmt->execute([$finalStok, $id]);

    header("Location: StockControl.php");
}

?>