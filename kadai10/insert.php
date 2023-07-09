<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


require_once('funcs.php');

// 1. POSTデータ取得
$divingNumber = $_POST['divingNumber'];
$date = $_POST['date'];
$location = $_POST['location'];
$diveSite = $_POST['diveSite'];
$rating = $_POST['rating'];
$comment = $_POST['comment'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
var_dump($date);

// 2. 画像アップロード処理
$targetDir = "uploads/";
$fileName = basename($_FILES["photo"]["name"]);
$targetFile = $targetDir . $fileName;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Check if the file was successfully uploaded
if ($_FILES["photo"]["error"] !== UPLOAD_ERR_OK) {
    echo "File upload failed. Error code: " . $_FILES["photo"]["error"];
    exit;
}

// Move the uploaded file to the target directory
if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
    echo "The file " . $fileName . " has been uploaded.";
} else {
    echo "File upload failed. An error occurred during the file move process.";
    exit;
}

// 3. DB接続
$pdo = db_conn();

// 4. データ登録SQL作成
$stmt = $pdo->prepare("INSERT INTO gs_dive_table (id, divingNumber, date, location, diveSite, rating, comment, photo, latitude, longitude) VALUES (NULL, :divingNumber, :date, :location, :diveSite, :rating, :comment, :photo, :latitude, :longitude)");

$stmt->bindValue(':divingNumber', $divingNumber, PDO::PARAM_INT);
$stmt->bindValue(':date', $date, PDO::PARAM_STR);
$stmt->bindValue(':location', $location, PDO::PARAM_STR);
$stmt->bindValue(':diveSite', $diveSite, PDO::PARAM_STR);
$stmt->bindValue(':rating', $rating, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
$stmt->bindValue(':photo', $fileName, PDO::PARAM_STR);
$stmt->bindValue(':latitude', $latitude, PDO::PARAM_INT);
$stmt->bindValue(':longitude', $longitude, PDO::PARAM_INT);

$status = $stmt->execute();

if ($status === false) {
    sql_error($stmt);
} else {
    // データベースへの挿入が成功した場合
    $lastInsertId = $pdo->lastInsertId();
    header('Location: select.php');
    exit();
}
