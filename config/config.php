<?php
$servername = "サーバー名";
$username = "ユーザー名";
$password = "パスワード";
$dbname = "データベース名";

// 接続を作成
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続の確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
