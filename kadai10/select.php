<?php
// 1. 関数群の読み込み
session_start();
require_once('funcs.php');
loginCheck();

// 2. データ取得SQL作成
$pdo = db_conn();
$stmt = $pdo->prepare('SELECT * FROM gs_dive_table');
$status = $stmt->execute();

$view = "";
if ($status === false) {
    $error = $stmt->errorInfo();
    exit("ErrorQuery:" . $error[2]);
} else {
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $view .= '<div class="dive-log-box">';
        
        $view .= '<div class="dive-log-info">';
        $view .= '<p>' . h($result['divingNumber']) . ' :' . h($result['date']) . '</p>';
        $view .= '<p>' . h($result['location']) . '</p>';
        $view .= '<p>' . h($result['diveSite']) . '</p>';
        $view .= '<p>' . h($result['rating']) . '</p>';
        $view .= '<p>' . h($result['comment']) . '</p>';
        $view .= '</div>';
        
        $view .= '<div class="dive-log-image">';
        $view .= '<img src="uploads/' . $result['photo'] . '" width="200px" height="200px">';
        $view .= '</div>';

        $view .= '<div class="dive-log-actions">';
        $view .= '<a href="detail.php?id=' . $result['id'] . '">Edit</a>';
        $view .= '<a href="delete.php?id=' . $result['id'] . '">Delete</a>';
        $view .= '</div>';

        // マーカーを表示するために必要な情報をJavaScriptに渡す
        $view .= '<input type="hidden" class="dive-log" data-lat="' . h($result['latitude']) . '" data-lng="' . h($result['longitude']) . '">';

        $view .= '</div>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/jquery-2.1.3.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/sample.css">
    <title>Planet Earth & Divers</title>
    <style>
      #map {
        height: 400px;
        width: 100%;
      }

      .dive-log {
        padding:30px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
      }

      .dive-log-box {
        width: calc(30% - 20px);
        background-color: #1c2834;
        opacity: 0.8;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 0px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }

      .dive-log-info {
        margin-bottom: 10px;
        word-wrap: break-word;
      }

      .dive-log-image {
        text-align: center;
        margin-bottom: 10px;
      }

      .dive-log-image img {
        max-width: 100%;
        max-height: 200px;
        border-radius: 5px;
      }

      .dive-log-actions {
        font-size:  50%;
        display: flex;
        justify-content: flex-end;
        margin-top: 10px;
      }

      .dive-log-actions a {
        margin-left: 10px;
        color: #ffffff;
        background-color: aqua;
        padding: 5px 10px;
        border-radius: 5px;
        text-decoration: none;
      }

      .dive-log-actions a:hover {
        background-color: #555;
      }
    </style>
  </head>
  <body>

  <header>
    <nav class="navigation">
      <div class="earth" href="index.php"></div>
      <div class="h1-wapper">
        <h1>PLANET EARTH  & DIVERS</h1>
      </div>
      <ul class="nav-list">
        <li class="nav-item"><a class="a" href="#index.php">About</a></li>
         <li class="nav-item"><a class="a" href="divelog.php">Dive Log</a></li>
         <li class="nav-item"><a class="a" href="#">Project</a></li>
         <li class="nav-item"><a class="a" href="menu.php">Menu</a></li>
      </ul>
     </nav>
  </header>

  <main>
    <h2 id="divelog">Dive Log - Records</h2>
    <div class="map__wrapper">
      <div id="map" class="map"></div>
      <div class="dive-log">
          <?php echo $view; ?>
      </div>
    </div>

  <script>
    function initMap() {
      const map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 0, lng: 0 },
        zoom: 2,
      });

      const markers = document.querySelectorAll(".dive-log");

      markers.forEach((marker) => {
        const lat = parseFloat(marker.dataset.lat);
        const lng = parseFloat(marker.dataset.lng);

        const position = { lat: lat, lng: lng };

        new google.maps.Marker({
          position: position,
          map: map,
        });
      });
    }
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=””&callback=initMap&language=en" async defer></script>
  <script src="js/script.js"></script>
  </body>
  
  <footer class="footer">©︎Chika Iwanaga</footer>

</html>

