<?php
// Veritabanı bağlantısı
$host = 'localhost';
$dbname = 'kahhve';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

session_start();

// Değişkenler
$loginError = $registerError = $registerSuccess = "";
$activeForm = 'buttons'; // İlk durumda butonlar görünsün

// Giriş İşlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $inputUsername]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($inputPassword, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $loginError = "Kullanıcı adı veya şifre yanlış!";
        $activeForm = 'login';
    }
}

// Kayıt İşlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    $adminPassword = $_POST['admin_password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);

    if ($stmt->fetch()) {
        $registerError = "Bu kullanıcı adı zaten kullanılıyor!";
        $activeForm = 'register';
    } else {
        if (($role === 'admin' || $role === 'employee') && $adminPassword !== 'emo24') {
            $registerError = "Yönetici şifresi yanlış veya girilmedi!";
            $activeForm = 'register';
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role, created_at) VALUES (:username, :password, :role, NOW())");
            $stmt->execute(['username' => $username, 'password' => $password, 'role' => $role]);
            $registerSuccess = "Kayıt başarılı! Giriş yapmak için lütfen giriş sayfasına geçin.";
            // Burada `$activeForm` değişkenini değiştirmiyoruz
        }
    }
}


// Çıkış Yap
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş ve Kayıt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            width: 100%;
        }
        .hidden { display: none; }
        .visible { display: block; }
        .form-container {
            margin: 20px auto;
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        button {
            margin-top: 10px;
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        button.back-btn {
            background-color: #6c757d;
        }
        button.back-btn:hover {
            background-color: #5a6268;
        }
        button.gui-btn {
            background-color:rgb(36, 203, 181);
        }
        button.gui-btn:hover {
            background-color: rgb(27, 148, 132);
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!isset($_SESSION['user'])): ?>
            <h1>Hoş Geldiniz</h1>

            <!-- Butonlar -->
            <div id="buttons" class="<?= $activeForm === 'buttons' ? 'visible' : 'hidden'; ?>">
                <button onclick="showForm('login')">Giriş Yap</button>
                <button onclick="showForm('register')">Kayıt Ol</button>
            </div>

            <!-- Giriş Yap Formu -->
            <div id="login" class="form-container <?= $activeForm === 'login' ? 'visible' : 'hidden'; ?>">
                <h2>Giriş Yap</h2>
                <?php if (!empty($loginError)): ?>
                    <p style="color: red;"><?= $loginError; ?></p>
                <?php endif; ?>
                <form method="POST" action="">
                    <label for="username">Kullanıcı Adı:</label>
                    <input type="text" id="username" name="username" required>
                    <br><br>
                    <label for="password">Şifre:</label>
                    <input type="password" id="password" name="password" required>
                    <br><br>
                    <button type="submit" name="login">Giriş Yap</button>
                </form>
                <button class="back-btn" onclick="showForm('buttons')">Geri Dön</button>
            </div>

            <!-- Kayıt Ol Formu -->
            <div id="register" class="form-container <?= $activeForm === 'register' ? 'visible' : 'hidden'; ?>">
                <h2>Kayıt Ol</h2>
                <?php if (!empty($registerError)): ?>
                    <p style="color: red;"><?= $registerError; ?></p>
                <?php elseif (!empty($registerSuccess)): ?>
                    <p style="color: green;"><?= $registerSuccess; ?></p>
                <?php endif; ?>
                <form method="POST" action="">
                    <label for="username">Kullanıcı Adı:</label>
                    <input type="text" id="username" name="username" required>
                    <br><br>
                    <label for="password">Şifre:</label>
                    <input type="password" id="password" name="password" required>
                    <br><br>
                    <label for="role">Rol:</label>
                    <select id="role" name="role" onchange="toggleAdminPasswordField(this.value)">
                        <option value="user">Kullanıcı</option>
                        <option value="admin">Admin</option>
                        <option value="employee">Personel</option>
                    </select>
                    <br><br>
                    <div id="admin-password-field" style="display: none;">
                        <label for="admin_password">Yönetici Şifresi:</label>
                        <input type="password" id="admin_password" name="admin_password">
                        <br><br>
                    </div>
                    <button type="submit" name="register">Kayıt Ol</button>
                </form>
                <button class="back-btn" onclick="showForm('buttons')">Geri Dön</button>
            </div>


        <?php else: ?>
<!------------------------------------------------------------ ADMİN PANELİ BAŞLANGIÇ ------------------------------------------------------------>

            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                <h3>Admin Paneli</h3>
                <button class="gui-btn" onclick="window.location.href='AddCoffe.php'">Kahve Ekle</button>
                <button class="gui-btn" onclick="window.location.href='StockControl.php'">Stok Kontrolü</button>
                <button class="gui-btn" onclick="window.location.href='Siparis.php'">Sipariş Ver</button><br>
                

<!------------------------------------------------------------ ADMİN PANELİ BİTİŞ------------------------------------------------------------------>


<!------------------------------------------------------------ PERSONEL PANELİ BAŞLANGIÇ ------------------------------------------------------------>

            <?php elseif ($_SESSION['user']['role'] === 'employee'): ?>
                <h3>Personel Paneli</h3>
                <button class="gui-btn" onclick="window.location.href='Siparis.php'">Sipariş Ver</button><br>

<!------------------------------------------------------------ ADMİN PANELİ BİTİŞ ------------------------------------------------------------>

<!------------------------------------------------------------ KULLANICI PANELİ BAŞLANGIÇ------------------------------------------------------------------>


            <?php else: ?>
                <h3>Kullanıcı Paneli</h3>
                <button class="gui-btn" onclick="window.location.href='index.php'">Anasayfa</button>
                <button class="gui-btn" onclick="window.location.href='AnaBilgi.php'">Bilgi</button>
                <button class="gui-btn" onclick="window.location.href='Siparis.php'">Sipariş Ver</button><br>


<!------------------------------------------------------------ KULLANICI PANELİ BİTİŞ------------------------------------------------------------------>
            <?php endif; ?>
            <a href="?logout=1"><button>Çıkış Yap</button></a>
        <?php endif; ?>
    </div>
        

        
    </div>

    <script>
        function showForm(formId) {
            document.getElementById('buttons').classList.add('hidden');
            document.getElementById('login').classList.add('hidden');
            document.getElementById('register').classList.add('hidden');
            document.getElementById(formId).classList.remove('hidden');
        }

        function toggleAdminPasswordField(role) {
            const adminPasswordField = document.getElementById('admin-password-field');
            adminPasswordField.style.display = (role === 'admin' || role === 'employee') ? 'block' : 'none';
        }
    </script>
</body>
</html>
