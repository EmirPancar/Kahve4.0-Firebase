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
    </style>
</head>
<body>

<?php if ($role === 'admin'): ?>
    <h1 style="text-align:center;">Admin Panel</h1>

    <!-- Kart Ekleme Formu -->
    <form action="process.php" method="POST">
        <h2>Yeni Kart Ekle</h2>
        <input type="hidden" name="action" value="add">

        <label>İsim:</label>
        <input type="text" name="name" required>

        <label>Açıklama:</label>
        <textarea name="description" required></textarea>

        <label>Resim URL:</label>
        <input type="text" name="image_url" required>

        <label>Sayfa:</label>
    <select name="page" id="pageSelect" required>
        <option value="Sıcak Kahve">Sıcak Kahveler</option>
        <option value="Sıcak Çay">Sıcak Çaylar</option>
        <option value="Diğer">Diğer İçecekler</option>
        <option value="Soğuk Çay">Soğuk Çaylar</option>
        <option value="Soğuk Kahve">Soğuk Kahveler</option>
    </select>
    
    <div id="SıcakKahveOptions" class="optionsDiv">
        <label>Kategori (Sıcak Kahve):</label>
        <select name="category">
            <option value="Espresso">Espresso Çeşitleri</option>
            <option value="Filtre">Filtre Kahve Çeşitleri</option>
            <option value="Sütlü">Sütlü ve Aromalı Kahve</option>
        </select>
    </div>

    <div id="SıcakÇayOptions" class="optionsDiv" style="display: none;">
        <label>Kategori (Sıcak Çay):</label>
        <select name="category">
            <option value="Klasik">Klasik Çay Çeşitleri</option>
            <option value="Bitki">Bitki Çayı Çeşitleri</option>
            <option value="Aromalı">Aromalı Çay Çeşitleri</option>
        </select>
    </div>

    <div id="DiğerOptions" class="optionsDiv" style="display: none;">
        <label>Kategori (Diğer):</label>
        <select name="category">
            <option value="Soğuk">Soğuk İçecek Çeşitleri</option>
            <option value="Sıcak">Sıcak İçecek Çeşitleri</option>           
        </select>
    </div>

    <div id="SoğukÇayOptions" class="optionsDiv" style="display: none;">
        <label>Kategori (Soğuk Çay):</label>
        <select name="category">
            <option value="Soğuk Klasik">Klasik Çay Çeşitleri</option>
            <option value="Soğuk Meyveli">Meyveli Çay Çeşitleri</option>
            <option value="Soğuk Bitkisel">Bitkisel Çay Çeşitleri</option>
        </select>
    </div>

    <div id="SoğukKahveOptions" class="optionsDiv" style="display: none;">
        <label>Kategori (Soğuk Kahve):</label>
        <select name="category">
            <option value="Brew">Brew Çeşitleri</option>
            <option value="Buzlu">Buzlu Kahve Çeşitler</option>
            <option value="Köpüklü">Köpüklü Kahve Çeşitleri</option>
            <option value="Soğuk Diğer">Diğer Kahve Çeşitleri</option>
        </select>
    </div>


        <label>Stok:</label>
        <input type="text" name="stok" required>

        <label>Fiyat:</label>
        <input type="text" name="fiyat" required>




        <button type="submit">Ekle</button>
    </form>

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
            <th>Sayfa</th>
            <th>Kategori</th>
            <th>Açıklama</th>
            <th>Stok</th>
            <th>Resim</th>
            <th>İsim</th>
            <th>fiyat</th>
            <th>İşlem</th> 
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cards as $card): ?>
        <tr data-page="<?= htmlspecialchars($card['page']) ?>" data-category="<?= htmlspecialchars($card['category']) ?>">
            <td><?= htmlspecialchars($card['page']) ?></td>
            <td><?= htmlspecialchars($card['category']) ?></td>
            <td><?= htmlspecialchars($card['description']) ?></td>
            <td><?= htmlspecialchars($card['stok']) ?></td>
            <td><img src="<?= htmlspecialchars($card['image_url']) ?>" alt="Resim" style="width:100px;"></td>
            <td><?= htmlspecialchars($card['name']) ?></td>
            <td><?= htmlspecialchars($card['fiyat']) ?></td>
            <td>
                <form action="process.php" method="POST">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $card['id'] ?>">
                    <button class="delete" type="submit">Sil</button>
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




    
<!-- Option Seçenekleri-->
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const pageSelect = document.getElementById('pageSelect');
    const optionsDivs = document.querySelectorAll('.optionsDiv');
    const categorySelects = document.querySelectorAll('.optionsDiv select');

    // Varsayılan olarak "Sıcak Kahve"yi seç
    const defaultPage = 'Sıcak Kahve';
    pageSelect.value = defaultPage;

    // Tüm seçenek divlerini gizle ve select'leri devre dışı bırak
    optionsDivs.forEach(div => div.style.display = 'none');
    categorySelects.forEach(select => select.disabled = true);

    // Varsayılan div'i göster ve ilgili select'i etkinleştir
    const selectedDiv = document.getElementById(defaultPage.replace(/ /g, '') + 'Options');
    if (selectedDiv) {
        selectedDiv.style.display = 'block';
        selectedDiv.querySelector('select').disabled = false;
    }

    // Seçim değişikliğini dinle
    pageSelect.addEventListener('change', function () {
        const selectedPage = pageSelect.value;

        // Tüm seçenek divlerini gizle ve select'leri devre dışı bırak
        optionsDivs.forEach(div => div.style.display = 'none');
        categorySelects.forEach(select => select.disabled = true);

        // Seçilen sayfaya ait div'i göster ve aktif select'i etkinleştir
        const selectedDiv = document.getElementById(selectedPage.replace(/ /g, '') + 'Options');
        if (selectedDiv) {
            selectedDiv.style.display = 'block';
            selectedDiv.querySelector('select').disabled = false;
        }
    });
});
</script>




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
