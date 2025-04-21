<?php
$pdo = new PDO('mysql:host=localhost;dbname=kahhve', 'root', '');

// Siparişleri çek
$stmt = $pdo->query("
    SELECT masa.masa_id, masa.customer, orders.coffee, orders.id 
    FROM masa 
    LEFT JOIN orders ON masa.order_id = orders.order_id
    ORDER BY masa.masa_id
");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($orders);
?>
