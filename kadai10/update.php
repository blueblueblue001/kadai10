<?php

//１．関数群の読み込み
session_start();
require_once('funcs.php');
loginCheck();


// 1. POSTデータ取得
$id = $_POST['id'];
$divingNumber = $_POST['divingNumber'];
$date = $_POST['date'];
$location = $_POST['location'];
$diveSite = $_POST['diveSite'];
$rating = $_POST['rating'];
$comment = $_POST['comment'];

// 2. 画像アップロード処理
$targetDir = "uploads/";
$fileName = basename($_FILES["photo"]["name"]);
$targetFile = $targetDir . $fileName;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// 画像ファイルが正しいかチェック
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "Uploaded file is not an image.";
        $uploadOk = 0;
    }
}

// 既存の同名ファイルがあるかチェック
if (file_exists($targetFile)) {
    echo "File already exists.";
    $uploadOk = 0;
}

// ファイルサイズ制限（10MB以下）
if ($_FILES["photo"]["size"] > 10 * 1024 * 1024) {
    echo "File size exceeds the limit (10MB).";
    $uploadOk = 0;
}

// 特定のファイル形式のみ受け入れる
$allowedExtensions = array("jpg", "jpeg", "png", "gif");
if (!in_array($imageFileType, $allowedExtensions)) {
    echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
    $uploadOk = 0;
}

// アップロードが許可されているかどうかをチェック
if ($uploadOk == 0) {
    echo "File upload failed.";
} else {
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
        echo "The file " . $fileName . " has been uploaded.";
    } else {
        echo "File upload failed.";
    }
}

// 3. DB接続
$pdo = db_conn();

// 4. データ更新SQL作成
$stmt = $pdo->prepare("UPDATE gs_dive_table SET divingNumber = :divingNumber, date = :date, location = :location, diveSite = :diveSite, rating = :rating, comment = :comment, photo = :photo WHERE id = :id");

$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':divingNumber', $divingNumber, PDO::PARAM_INT);
$stmt->bindValue(':date', $date, PDO::PARAM_STR);
$stmt->bindValue(':location', $location, PDO::PARAM_STR);
$stmt->bindValue(':diveSite', $diveSite, PDO::PARAM_STR);
$stmt->bindValue(':rating', $rating, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
$stmt->bindValue(':photo', $fileName, PDO::PARAM_STR);

$status = $stmt->execute();

if ($status === false) {
    $error = $stmt->errorInfo();
    echo "Database Error: " . $error[2];
    exit();
} else {
    header('Location: select.php');
    exit();
}

?>
