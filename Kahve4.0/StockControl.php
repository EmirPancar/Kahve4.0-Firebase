<?php
$pdo = new PDO('mysql:host=localhost;dbname=kahhve', 'root', ''); // DB bağlantısı
$cards = $pdo->query("SELECT * FROM coffee_cards")->fetchAll(PDO::FETCH_ASSOC); // Kartları getir


session_start();

// Oturum kontrolü: Kullanıcı oturumu yoksa giriş sayfasına yönlendirme
if (!isset($_SESSION['user'])) {
    header("Location: Giris.php");
    exit();
}

//kullanıcı bilgilerini al
$user = $_SESSION['user'];
$role = $user['role'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form, table {
            margin: 20px auto;
            width: 80%;
        }
        table {
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
        }
        input, textarea, select {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
        }
        button {
            padding: 10px 20px;
            background-color: #5cb85c;
            color: white;
            border: none;
            cursor: pointer;
        }
        button.delete {
            background-color: #d9534f;
        }
        .edit-form {
            display: flex;
            align-items: center;
            gap: 10px; 
        }

        .edit-form label {
            margin: 0;
            font-size: 14px;
            line-height: 36px; 
        }

        .edit-form input {
            width: 120px; 
            height: 36px; 
            padding: 5px 10px; 
            font-size: 14px; 
            box-sizing: border-box; 
        }

        .edit-form button {
            height: 36px; 
            padding: 0 15px; 
            background-color: #0275d8;
            color: white;
            border: none;
            font-size: 14px;
            cursor: pointer;
            box-sizing: border-box; 
            vertical-align: middle; 
        }

        .edit-form button:hover {
            background-color: #0256a3; 
        }
    </style>
</head>

<body>
    
<?php if($role === 'admin'): ?>


        <!-- Mevcut Kartlar -->
<h2 style="text-align:center;">Mevcut Kartlar</h2>


<!-- Sayfa Filtreleme Butonları -->
<div style="text-align:center; margin-top:20px;">
    <button class="filterButton" data-page="Sıcak Kahve">Sıcak Kahveler</button>
    <button class="filterButton" data-page="Sıcak Çay">Sıcak Çaylar</button>
    <button class="filterButton" data-page="Diğer">Diğer İçecekler</button>
    <button class="filterButton" data-page="Soğuk Çay">Soğuk Çaylar</button>
    <button class="filterButton" data-page="Soğuk Kahve">Soğuk Kahveler</button>
</div>



<!-- Mevcut Kartlar -->
<table id="cardTable">
    <thead>
        <tr>
            <th>Kategori</th> 
            <th>İsim</th>
            <th>Stok</th>         
            <th>İşlem</th> 
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cards as $card): ?>
        <tr data-page="<?= htmlspecialchars($card['page']) ?>" data-category="<?= htmlspecialchars($card['category']) ?>">
            
            <td><?= htmlspecialchars($card['category']) ?></td>

            <td><?= htmlspecialchars($card['name']) ?></td>
            
            <td><?= htmlspecialchars($card['stok']) ?></td>
            
            <td>
                <form action="process.php" method="POST" class="edit-form">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?= $card['id'] ?>">

                    <!-- Stok Artırma/Çıkarma -->
                    <div>
                        <label for="stokAdd">Ekle:</label>
                        <input type="text" name="stokAdd" min="0">
                    </div>
                    <div>
                        <label for="stokRemove">Çıkar:</label>
                        <input type="text" name="stokRemove" min="0">
                    </div>
                    <div>
                        <label for="stok">Yeni Stok:</label>
                        <input type="text" name="stok" min="0">
                    </div>

                    <div class="button-container">
                        <button type="submit">Güncelle</button>
                    </div>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>




    <?php else: ?>

<!-- Geçersiz Role -->
<h2>Yetkisiz Erişim</h2>
<p>Bu sayfayı görüntüleme yetkiniz yok.</p>
    <?php endif; ?>








    <!-- JavaScript Filtreleme -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.filterButton');
    const cardRows = document.querySelectorAll('#cardTable tbody tr');

    // Butonlara tıklama olaylarını ekle
    filterButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Varsayılan davranışı engelle

            const selectedPage = button.getAttribute('data-page');
            const tableBody = document.querySelector('#cardTable tbody');

            // Tüm satırları gizle
            cardRows.forEach(row => row.style.display = 'none');

            // Seçilen sayfa için satırları gruplama
            const filteredRows = Array.from(cardRows).filter(row => row.getAttribute('data-page') === selectedPage);
            const groupedByCategory = {};

            // Satırları kategoriye göre gruplama
            filteredRows.forEach(row => {
                const category = row.getAttribute('data-category');
                if (!groupedByCategory[category]) groupedByCategory[category] = [];
                groupedByCategory[category].push(row);
            });

            // Tabloyu temizleyip grupları ekleme
            tableBody.innerHTML = '';
            Object.keys(groupedByCategory).forEach(category => {
                // Kategori başlığını ekle
                const categoryRow = document.createElement('tr');
                categoryRow.innerHTML = `<td colspan="7" style="font-weight: bold; text-align: center; background-color: #f9f9f9;">${category}</td>`;
                tableBody.appendChild(categoryRow);

                // Kategoriye ait satırları ekle
                groupedByCategory[category].forEach(row => {
                    row.style.display = 'table-row';
                    tableBody.appendChild(row);
                });
            });
        });
    });
});
</script>



</body>
</html>