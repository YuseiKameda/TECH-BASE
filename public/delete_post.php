<?php
session_start();
include '../config/config.php';

// ユーザーがログインしているか確認
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// POSTリクエストが送信された場合のみ処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 削除する投稿のIDを取得
    $post_id = $_POST['id'];
    $user_id = $_SESSION['id'];

    // トランザクションを開始
    $conn->begin_transaction();

    try {
        // 投稿に関連するコメントを削除
        $sql = "DELETE FROM comments WHERE post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $post_id);
        $stmt->execute();

        // 投稿に関連する「いいね」を削除
        $sql = "DELETE FROM likes WHERE post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $post_id);
        $stmt->execute();

        // 投稿を削除
        $sql = "DELETE FROM posts WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $post_id, $user_id);
        $stmt->execute();

        // トランザクションをコミット
        $conn->commit();
    } catch (Exception $e) {
        // エラーが発生した場合、トランザクションをロールバック
        $conn->rollback();
        echo "削除中にエラーが発生しました。";
        exit();
    }

    // ユーザーホームにリダイレクト
    header('Location: user_home.php');
    exit();
} else {
    header('Location: user_home.php');
    exit();
}
