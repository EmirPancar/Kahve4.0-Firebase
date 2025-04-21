<?php
session_start();

// Oturum kontrolü: Kullanıcı oturumu yoksa giriş sayfasına yönlendirme
if (!isset($_SESSION['user'])) {
    header("Location: Giris.php");
    exit();
}

// Kullanıcı bilgilerini al
$user = $_SESSION['user'];
$role = $user['role'];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Sayfası</title>
    <link rel="stylesheet" href="style.css">
    <style>


      .SipBut{
        background-color: rgb(169, 103, 3);
        cursor: pointer;
        color:rgb(227, 223, 216);
        width:100px;
        height:30px;
      }

      .SipBut:hover{

        background-color:rgb(171, 111, 21);

      }

      .deliver-button{

        background-color: rgb(169, 103, 3);
        cursor: pointer;
        color:rgb(227, 223, 216);
        width:100px;
        height:30px;

      }

      .deliver-button:hover{
        background-color:rgb(171, 111, 21);
      }

      .MasaUst{

        width:1000px;
        height:120px;

        margin-left:100px;

        display:flex;
        align-items:center;
        justify-content:center;

        background-color: aqua;
      }

      .con{
        height:600px;
      }

      h2 {
            text-align: center;
            margin: 20px 0;
            color: black;
        }

        .coffee-table {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: rgb(95, 72, 35);
        }

        .filters {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .filters button {
            padding: 10px 20px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background-color:rgb(169, 103, 3);
            color: white;
            transition: background-color 0.3s ease;
        }

        .filters button:hover {
            background-color: rgb(171, 111, 21);
        }

        #coffeeTable {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            background-color:rgb(192, 166, 124);
        }

        #coffeeTable thead {
            background-color: rgb(193, 159, 105);
        }

        #coffeeTable th, #coffeeTable td {
            padding: 15px;
            border: 1px solid #ddd;
        }

        #coffeeTable th {
            text-align: center;
        }

        #coffeeTable tbody tr {
            transition: background-color 0.3s ease;
        }

        #coffeeTable tbody tr:hover {
            background-color:rgb(194, 173, 140);
        }

        .order-button {
            padding: 8px 12px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background-color: #28a745;
            color: white;
            transition: background-color 0.3s ease;
        }

        .order-button:hover {
            background-color: #218838;
        }

        .image-cell img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }

        .Body1 img {
          width: 100%;
          height: 100%;
          object-fit: cover;
          object-position: cover;
          }

  .Body1 .content {
    position:absolute;
    justify-content:center;
    text-align: center;
    color: black;
    opacity: 1;
    z-index: 4;
  }

  .Body3{
    height:300px;
    width:300px;
    background-color:rgb(193, 159, 105);

    margin-left:50px;
    margin-bottom: 20px;
    margin-right: 50px;

}

  .Body3Title{
    width: 300px;
    height:50px;
    background-color:rgb(77, 51, 9);
    display:flex;

    justify-content:center;
    text-align:center;
    align-items:center;

    border-color:black;
    
    font-size:20px;
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    color: rgb(227, 224, 216);
  }

  .Body3Body{

    width:300px;
    height:250px;

    display:flex;

    justify-content:center;
    align-items:center;

    

  }







    table {
        width: 80%;
        border-collapse: collapse; /* Çizgilerin birleşik görünmesini sağlar */
        
    }
    th, td {
        border: 1px solid black; /* Hücreler arasına çizgi ekler */
        text-align: center; /* İçerikleri ortalar */
        padding: 8px; /* Hücre içi boşluk */
    }
    th {
        background-color:rgb(129, 83, 20); /* Başlık satırına hafif bir arka plan rengi */
        color:rgb(227, 224, 216);
    }






    </style>

</head>


<body>

<div class="Container">

<div class="Header"> 

<input type="radio" name="toggle" id="toggleOpen" value="toggleOpen">
<input type="radio" name="toggle" id="toggleClose" value="toggleClose">
<figure id="welcomeMessage">
  <figcaption>
    <h1> 
      <label for="toggleOpen"></label>
      <label for="toggleClose" title="Click to Close">✖</label>
      <b>
        -
        <a href="index.php" title="Anasayfa">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
            <defs>
              <lineargradient id="svgGradient" x1="0" y1="0" x2="20" y2="0" gradientUnits="userSpaceOnUse">
                <stop offset="0" stop-color="#00ffc3" />
                <stop offset="0.09090909090909091" stop-color="#00fad9" />
                <stop offset="0.18181818181818182" stop-color="#00f4f0" />
                <stop offset="0.2727272727272727" stop-color="#00eeff" />
                <stop offset="0.36363636363636365" stop-color="#00e6ff" />
                <stop offset="0.4545454545454546" stop-color="#00dcff" />
                <stop offset="0.5454545454545454" stop-color="#00d2ff" />
                <stop offset="0.6363636363636364" stop-color="#00c5ff" />
                <stop offset="0.7272727272727273" stop-color="#00b8ff" />
                <stop offset="0.8181818181818182" stop-color="#6da8ff" />
                <stop offset="0.9090909090909092" stop-color="#9f97ff" />
                <stop offset="1" stop-color="#c285ff" />
              </lineargradient>
            </defs>
            <path fill-rule="evenodd" d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z" />
            <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z" />
          </svg>
        </a>
      </b>
      <b>
        k
        <a href="AnaBilgi.php" title="Bilgi">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
            <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.93 4.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM8 5.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
          </svg>
        </a>
      </b>
      <b>
        a

        <a href="javascript:void(0);" title="">
          <svg class="svg-icon" viewBox="0 0 20 20">
            <path d="M19.432,7.157c-0.312-1.113-1.624-1.858-3.496-2.17c0.279,0.331,0.532,0.685,0.754,1.06c1.043,0.299,1.748,0.764,1.911,1.344c0.095,0.335,0.014,0.729-0.24,1.169c-0.274,0.476-0.768,1.007-1.455,1.542c0-0.034,0.005-0.067,0.005-0.101c0-3.816-3.094-6.91-6.91-6.91c-3.816,0-6.91,3.094-6.91,6.91c0,1.169,0.293,2.268,0.805,3.232c-1.366-0.277-2.303-0.805-2.495-1.487c-0.094-0.336-0.013-0.729,0.241-1.169c0.138-0.239,0.35-0.496,0.595-0.756c0.011-0.449,0.055-0.89,0.138-1.317c-1.398,1.144-2.112,2.386-1.805,3.476c0.338,1.205,1.845,1.98,3.968,2.24C5.8,15.854,7.774,16.91,10,16.91c3.389,0,6.201-2.44,6.791-5.659C18.735,9.951,19.795,8.448,19.432,7.157 M10,16.047c-1.651,0-3.147-0.664-4.238-1.738c0.147,0.005,0.295,0.008,0.447,0.008c1.502,0,3.195-0.212,4.941-0.658c1.734-0.443,3.297-1.064,4.595-1.776C14.952,14.299,12.682,16.047,10,16.047 M15.998,10.733c-1.27,0.797-2.973,1.554-5.062,2.088c-1.616,0.414-3.251,0.632-4.727,0.632c-0.427,0-0.827-0.025-1.213-0.061C4.338,12.425,3.954,11.258,3.954,10c0-3.339,2.707-6.046,6.046-6.046c3.34,0,6.047,2.708,6.047,6.046C16.047,10.249,16.027,10.492,15.998,10.733"></path>
          </svg>
          </a>
        
        </a>
      </b>
      <b>
        h
        <a href="Siparis.php" title="Sipariş">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
          </svg>
        </a>
      </b>
      <b>
        v
        <a href="javascript:void(0);" title="">
          <svg class="svg-icon" viewBox="0 0 20 20">
            <path d="M19.432,7.157c-0.312-1.113-1.624-1.858-3.496-2.17c0.279,0.331,0.532,0.685,0.754,1.06c1.043,0.299,1.748,0.764,1.911,1.344c0.095,0.335,0.014,0.729-0.24,1.169c-0.274,0.476-0.768,1.007-1.455,1.542c0-0.034,0.005-0.067,0.005-0.101c0-3.816-3.094-6.91-6.91-6.91c-3.816,0-6.91,3.094-6.91,6.91c0,1.169,0.293,2.268,0.805,3.232c-1.366-0.277-2.303-0.805-2.495-1.487c-0.094-0.336-0.013-0.729,0.241-1.169c0.138-0.239,0.35-0.496,0.595-0.756c0.011-0.449,0.055-0.89,0.138-1.317c-1.398,1.144-2.112,2.386-1.805,3.476c0.338,1.205,1.845,1.98,3.968,2.24C5.8,15.854,7.774,16.91,10,16.91c3.389,0,6.201-2.44,6.791-5.659C18.735,9.951,19.795,8.448,19.432,7.157 M10,16.047c-1.651,0-3.147-0.664-4.238-1.738c0.147,0.005,0.295,0.008,0.447,0.008c1.502,0,3.195-0.212,4.941-0.658c1.734-0.443,3.297-1.064,4.595-1.776C14.952,14.299,12.682,16.047,10,16.047 M15.998,10.733c-1.27,0.797-2.973,1.554-5.062,2.088c-1.616,0.414-3.251,0.632-4.727,0.632c-0.427,0-0.827-0.025-1.213-0.061C4.338,12.425,3.954,11.258,3.954,10c0-3.339,2.707-6.046,6.046-6.046c3.34,0,6.047,2.708,6.047,6.046C16.047,10.249,16.027,10.492,15.998,10.733"></path>
          </svg>
        </a>
       
      </b>
      <b>
        e
        <a href="javascript:void(0);" title="Öneriler">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.026A2 2 0 0 0 2 14h6.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586l-1.239-.757ZM16 4.697v4.974A4.491 4.491 0 0 0 12.5 8a4.49 4.49 0 0 0-1.965.45l-.338-.207L16 4.697Z" />
            <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm-3.5-2a.5.5 0 0 0-.5.5v1h-1a.5.5 0 0 0 0 1h1v1a.5.5 0 0 0 1 0v-1h1a.5.5 0 0 0 0-1h-1v-1a.5.5 0 0 0-.5-.5Z" />
          </svg>
        </a>
      </b>
      <b>
        -
        <a href="Giris.php" title="Giriş">
          <svg class="svg-icon" viewBox="0 0 20 20">
            <path 
              d="M12.075,10.812c1.358-0.853,2.242-2.507,2.242-4.037c0-2.181-1.795-4.618-4.198-4.618S5.921,4.594,5.921,6.775c0,1.53,0.884,3.185,2.242,4.037c-3.222,0.865-5.6,3.807-5.6,7.298c0,0.23,0.189,0.42,0.42,0.42h14.273c0.23,0,0.42-0.189,0.42-0.42C17.676,14.619,15.297,11.677,12.075,10.812 M6.761,6.775c0-2.162,1.773-3.778,3.358-3.778s3.359,1.616,3.359,3.778c0,2.162-1.774,3.778-3.359,3.778S6.761,8.937,6.761,6.775 M3.415,17.69c0.218-3.51,3.142-6.297,6.704-6.297c3.562,0,6.486,2.787,6.705,6.297H3.415z" 
            ></path>
          </svg>
        </a>
        </a>
      </b>
    </h1>
  </figcaption>
</figure>
    </div>

   <!-- <h1>Hoş Geldiniz, <?php echo htmlspecialchars($user['username']); ?>!</h1> -->



<!------------------------------------------------------ ADMİN KISMI BAŞLANGIÇ ----------------------------------------------------->


    <?php if ($role === 'admin'): ?>
        <!-- Admin Paneli -->

        <div class="Body3" data-table="1">
    <div class="Body3Title">MASA 1</div>
    <div class="Body3Body">Siparişler yükleniyor...</div>
</div>

<div class="Body3" data-table="2">
    <div class="Body3Title">MASA 2</div>
    <div class="Body3Body">Siparişler yükleniyor...</div>
</div>

<div class="Body3" data-table="3">
    <div class="Body3Title">MASA 3</div>
    <div class="Body3Body">Siparişler yükleniyor...</div>
</div>

<div class="Body3" data-table="4">
    <div class="Body3Title">MASA 4</div>
    <div class="Body3Body">Siparişler yükleniyor...</div>
</div>

<div class="Body3" data-table="5">
    <div class="Body3Title">MASA 5</div>
    <div class="Body3Body">Siparişler yükleniyor...</div>
</div>

<div class="Body3" data-table="6">
    <div class="Body3Title">MASA 6</div>
    <div class="Body3Body">Siparişler yükleniyor...</div>
</div>
        

<!------------------------------------------------------ ADMİN KISMI SON ----------------------------------------------------->

<!------------------------------------------------------ PERSONEL KISMI BAŞLANGIÇ ----------------------------------------------------->

    <?php elseif ($role === 'employee'): ?>
        <!-- Personel Paneli -->
        

<div class="Body3" data-table="1">
    <div class="Body3Title">MASA 1</div>
    <div class="Body3Body">Siparişler yükleniyor...</div>
</div>

<div class="Body3" data-table="2">
    <div class="Body3Title">MASA 2</div>
    <div class="Body3Body">Siparişler yükleniyor...</div>
</div>

<div class="Body3" data-table="3">
    <div class="Body3Title">MASA 3</div>
    <div class="Body3Body">Siparişler yükleniyor...</div>
</div>

<div class="Body3" data-table="4">
    <div class="Body3Title">MASA 4</div>
    <div class="Body3Body">Siparişler yükleniyor...</div>
</div>

<div class="Body3" data-table="5">
    <div class="Body3Title">MASA 5</div>
    <div class="Body3Body">Siparişler yükleniyor...</div>
</div>

<div class="Body3" data-table="6">
    <div class="Body3Title">MASA 6</div>
    <div class="Body3Body">Siparişler yükleniyor...</div>
</div>
<!------------------------------------------------------ PERSONEL KISMI SON ----------------------------------------------------->       

<!------------------------------------------------------ KULLANICI KISMI BAŞLANGIÇ ----------------------------------------------------->

    <?php elseif ($role === 'user'): ?>
        <!-- Kullanıcı Paneli -->

        <button id="goBackButton" class="hidden">Geri Dön</button>
   
        <div class="Body1" data-table="1"><img src="Gorsel/YanMasa.png"><div class="content">MASA1</div></div>
        <div class="Body1" data-table="2"><img src="Gorsel/YanMasa.png"><div class="content">MASA2</div></div>
        <div class="Body1" data-table="3"><img src="Gorsel/YanMasa.png"><div class="content">MASA3</div></div>
        <div class="Body1" data-table="4"><img src="Gorsel/YanMasa.png"><div class="content">MASA4</div></div>
        <div class="Body1" data-table="5"><img src="Gorsel/YanMasa.png"><div class="content">MASA5</div></div>
        <div class="Body1" data-table="6"><img src="Gorsel/YanMasa.png"><div class="content">MASA6</div></div>
        
        <div class="order-form" id="orderForm">


        <h2>SİPARİŞ PANELİ</h2>
<div class="coffee-table">
    <div class="filters"> 
        <button onclick="loadCoffees('Sıcak Kahve')">Sıcak Kahve</button>
        <button onclick="loadCoffees('Soğuk Kahve')">Soğuk Kahve</button>
        <button onclick="loadCoffees('Sıcak Çay')">Sıcak Çay</button>
        <button onclick="loadCoffees('Soğuk Çay')">Soğuk Çay</button>
        <button onclick="loadCoffees('Diğer')">Diğer</button>
    </div>
    <table id="coffeeTable">
        <thead>
            <tr>
                <th>Resim</th>
                <th>İsim</th>
                <th>Fiyat</th>
                <th>Sipariş Ver</th>
            </tr>
        </thead>
        <tbody>
            <!-- Daha fazla ürün buraya eklenebilir -->
        </tbody>
    </table>
</div>



    </div>



        
    <?php else: ?>

<!------------------------------------------------------ KULLANICI KISMI SON ----------------------------------------------------->


        <!-- Geçersiz Role -->
        <h2>Yetkisiz Erişim</h2>
        <p>Bu sayfayı görüntüleme yetkiniz yok.</p>
    <?php endif; ?>



<!-- Çıkış Yap 
<a href="Giris.php?logout=1">Çıkış Yap</a> -->

</div>


<script>
  document.addEventListener("DOMContentLoaded", () => {
    const tables = document.querySelectorAll(".Body1");
    const orderForm = document.getElementById("orderForm");
    const goBackButton = document.getElementById("goBackButton");

    let activeTable = null;

    tables.forEach(table => {
        table.addEventListener("click", (event) => {
            event.preventDefault();
            activeTable = table;

            // Masa bilgilerini gönder
            const masaId = table.getAttribute("data-table");
            fetch('add_table.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ masa_id: masaId })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      console.log("Masa bilgisi eklendi.");
                  } else {
                      console.error("Hata:", data.error);
                  }
              });

            // Masaları gizle ve formu göster
            tables.forEach(t => t.classList.add("hidden"));
            orderForm.classList.add("active");
            goBackButton.classList.add("visible");
        });
    });

    goBackButton.addEventListener("click", () => {
        // Kullanıcı bilgisini tablodan sil
        fetch('remove_table.php', { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("Masa bilgisi silindi.");
                } else {
                    console.error("Hata:", data.error);
                }
            });

        // Masaları ve formu sıfırla
        tables.forEach(t => t.classList.remove("hidden"));
        orderForm.classList.remove("active");
        goBackButton.classList.remove("visible");
    });
});

    // Geri dön butonu
    goBackButton.addEventListener("click", () => {
        // Tüm masaları tekrar görünür yap
        tables.forEach(t => t.classList.remove("hidden"));

        // Tıklanan masanın animasyon durumunu sıfırla
        if (activeTable) {
            activeTable.classList.remove("active");
            activeTable = null;
        }

        // Sipariş formunu ve geri dön butonunu gizle
        orderForm.classList.remove("active");
        goBackButton.classList.remove("visible");
    });

</script>






<script>


function loadCoffees(page) {
    fetch(`fetch_cards.php?page=${encodeURIComponent(page)}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector("#coffeeTable tbody");
            tbody.innerHTML = ""; // Önceki verileri temizle

            data.forEach(coffee => {
                const row = document.createElement("tr");

                row.innerHTML = `
                    <td><img src="${coffee.image_url}" alt="${coffee.name}" width="50"></td>
                    <td>${coffee.name}</td>
                    <td>${coffee.fiyat} TL</td>
                    <td><button class="SipBut" onclick="orderCoffee(${coffee.id}, '${coffee.name}')">Sipariş Ver</button></td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(error => console.error('Kahveler yüklenirken hata oluştu:', error));
}

function orderCoffee(coffeeId, coffeeName) {
    fetch('add_order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ coffee_id: coffeeId, coffee_name: coffeeName })
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              alert("Sipariş başarılı.");
          } else {
              alert("Sipariş sırasında hata oluştu: " + data.error);
          }
      });
}
</script>





<script>

document.addEventListener("DOMContentLoaded", () => {
    const loadOrders = () => {
        fetch('fetch_orders.php')
            .then(response => response.json())
            .then(data => {
                const containers = document.querySelectorAll('.Body3');

                containers.forEach(container => {
                    const tableId = container.getAttribute('data-table');
                    const ordersForTable = data.filter(order => order.masa_id === tableId);

                    const bodyDiv = container.querySelector('.Body3Body');
                    bodyDiv.innerHTML = ''; // Önceki içeriği temizle

                    if (ordersForTable.length > 0) {
                        const table = document.createElement('table');
                        table.innerHTML = `
                            <tr>
                                <th>Müşteri</th>
                                <th>Sipariş</th>
                                <th>İşlem</th>
                            </tr>
                        `;

                        ordersForTable.forEach(order => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${order.customer}</td>
                                <td>${order.coffee}</td>
                                <td>
                                    <button class="deliver-button" data-order-id="${order.id}">Teslim Edildi</button>
                                </td>
                            `;
                            table.appendChild(row);
                        });

                        bodyDiv.appendChild(table);
                    } else {
                        bodyDiv.textContent = "Sipariş yok.";
                    }
                });

                // Teslim Edildi butonlarına olay bağlayıcı ekle
                document.querySelectorAll('.deliver-button').forEach(button => {
                    button.addEventListener('click', () => {
                        const orderId = button.getAttribute('data-order-id');
                        console.log(`Silinecek id: ${orderId}`); // Debugging için

                        fetch('delete_order.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ order_id: orderId })
                        })
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    alert("Sipariş teslim edildi.");
                                    loadOrders(); // Siparişleri tekrar yükle
                                } else {
                                    alert("Hata: " + result.error);
                                }
                            })
                            .catch(error => console.error('Silme hatası:', error));
                    });
                });
            })
            .catch(error => console.error('Hata:', error));
    };

    loadOrders();
});




</script>


</body>
</html>
