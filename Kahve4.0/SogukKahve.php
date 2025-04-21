<?php
$pdo = new PDO('mysql:host=localhost;dbname=kahhve', 'root', ''); // Veritabanı bağlantısı
$cards = $pdo->query("SELECT * FROM coffee_cards")->fetchAll(PDO::FETCH_ASSOC); // Tüm kartları al
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soğuk Kahveler</title>
    <link rel="stylesheet" href="style.css"> 
    <link rel="stylesheet" href="BilgiStyle.css"> 


    <style>
    body,html{
      background-image: none;
    }
    .Container{
      background-color: rgba(52, 49, 49, 0.6); 
    }
    </style>



</head>
<body>




<video id="background-video" muted>
    <source src="Gorsel/Coffe Beans Slowmotion (online-video-cutter.com).mp4" type="video/mp4">
  </video>







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

<!-----------------------------------------------Kahve Yazısı Bitiş------------------------------------------------------------->



















    <div class="Baslik">BREW ÇEŞİTLERİ</div>

        <?php
            $espressoCards = array_filter($cards, function ($card) {
                return $card['category'] === 'Brew' && $card['page'] === 'Soğuk Kahve';
            });

                $totalCards = count($espressoCards);
                $chunkedCards = array_chunk($espressoCards, 3); // Her 3 kart için bir grup oluştur.

        foreach ($chunkedCards as $chunk): ?>
            <div class="BilgiSatiri">
                <?php foreach ($chunk as $card): ?>
                    <div class="Bcard">
                        <img src="<?= htmlspecialchars($card['image_url']) ?>" alt="<?= htmlspecialchars($card['name']) ?>">
                        <div class="content">
                            <h1><?= htmlspecialchars($card['name']) ?></h1>
                            <p><?= htmlspecialchars($card['description']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="SatirArasi">
                <!-- Tüm kartlar için FakeCard isimlerini listele -->
                <?php foreach ($chunk as $card): ?>
                    <div class="FakeCard"><?= htmlspecialchars($card['name']) ?></div>
                <?php endforeach; ?>
                </div>
        <?php endforeach; ?>



        
    <div class="Baslik">BUZLU KAHVE ÇEŞİTLERİ</div>

        <?php
            $filterCards = array_filter($cards, function ($card) {
                return $card['category'] === 'Buzlu' && $card['page'] === 'Soğuk Kahve';
            });

                $chunkedFilterCards = array_chunk($filterCards, 3);

foreach ($chunkedFilterCards as $chunk): ?>
    <div class="BilgiSatiri">
        <?php foreach ($chunk as $card): ?>
            <div class="Bcard">
                <img src="<?= htmlspecialchars($card['image_url']) ?>" alt="<?= htmlspecialchars($card['name']) ?>">
                <div class="content">
                    <h1><?= htmlspecialchars($card['name']) ?></h1>
                    <p><?= htmlspecialchars($card['description']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="SatirArasi">
        <?php foreach ($chunk as $card): ?>
            <div class="FakeCard"><?= htmlspecialchars($card['name']) ?></div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>



<div class="Baslik">KÖPÜKLÜ KAHVE ÇEŞİTLERİ</div>

<?php
$flavoredCards = array_filter($cards, function ($card) {
    return $card['category'] === 'Köpüklü' && $card['page'] === 'Soğuk Kahve';
});

$chunkedFlavoredCards = array_chunk($flavoredCards, 3);

foreach ($chunkedFlavoredCards as $chunk): ?>
    <div class="BilgiSatiri">
        <?php foreach ($chunk as $card): ?>
            <div class="Bcard">
                <img src="<?= htmlspecialchars($card['image_url']) ?>" alt="<?= htmlspecialchars($card['name']) ?>">
                <div class="content">
                    <h1><?= htmlspecialchars($card['name']) ?></h1>
                    <p><?= htmlspecialchars($card['description']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="SatirArasi">
        <?php foreach ($chunk as $card): ?>
            <div class="FakeCard"><?= htmlspecialchars($card['name']) ?></div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>



<div class="Baslik">DİĞER ÇEŞİTLER</div>

<?php
$flavoredCards = array_filter($cards, function ($card) {
    return $card['category'] === 'Soğuk Diğer' && $card['page'] === 'Soğuk Kahve';
});

$chunkedFlavoredCards = array_chunk($flavoredCards, 3);

foreach ($chunkedFlavoredCards as $chunk): ?>
    <div class="BilgiSatiri">
        <?php foreach ($chunk as $card): ?>
            <div class="Bcard">
                <img src="<?= htmlspecialchars($card['image_url']) ?>" alt="<?= htmlspecialchars($card['name']) ?>">
                <div class="content">
                    <h1><?= htmlspecialchars($card['name']) ?></h1>
                    <p><?= htmlspecialchars($card['description']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="SatirArasi">
        <?php foreach ($chunk as $card): ?>
            <div class="FakeCard"><?= htmlspecialchars($card['name']) ?></div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>








<!--------------------------------------- Alt Butonlar Başlangıç --------------------------------------------------------->
  
<div class="SatirSonu">
    <div class="container-eg-btn-2">

      <a class="button button-6" href="SogukCay.php">Soğuk Çay</a>
      <a class="button button-6" href="Diger.php">Diğer</a>
      <a class="button button-6" href="SicakCay.php">Sıcak Çay</a>
      <a class="button button-6" href="SicakKahve.php">Sıcak Kahve</a>
      
    </div>


   </div>


   <script>
    
    const video = document.getElementById('background-video');
    let lastScrollY = window.scrollY;

   
    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;

        
        const scrollDelta = scrollY - lastScrollY;

        
        if (video.readyState >= 2) { 
            video.currentTime += scrollDelta * 0.01; 
        }

        
        lastScrollY = scrollY;
    });
</script>



        </div>
    </body>
</html>