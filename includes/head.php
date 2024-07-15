<?php
session_start();
include '../config/config.php'; // 必要に応じてベースURLなどを含む設定ファイルをインクルード

// ユーザー名を取得
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <?php
    // CSSファイルの相対パスを動的に設定
    $base_path = '';
    if (basename(dirname(__FILE__)) == 'includes') {
        $base_path = '../css/style.css';
    } else {
        $base_path = 'css/style.css';
    }
    ?>
    <link rel="stylesheet" href="<?php echo $base_path; ?>">
    <title>笑いの広場</title>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
