<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$post_id = $_GET['id'];
$user_id = $_SESSION['id'];

// すでにいいねをしているか確認
$sql_check = "SELECT id FROM likes WHERE post_id = ? AND user_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $post_id, $user_id);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows == 0) {
    // いいねを追加
    $sql_insert = "INSERT INTO likes (post_id, user_id) VALUES (?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ii", $post_id, $user_id);
    $stmt_insert->execute();
    $stmt_insert->close();

    // 投稿のいいね数を更新
    $sql_update = "UPDATE posts SET likes = likes + 1 WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("i", $post_id);
    $stmt_update->execute();
    $stmt_update->close();
} else {
    // いいねを取り消し
    $sql_delete = "DELETE FROM likes WHERE post_id = ? AND user_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("ii", $post_id, $user_id);
    $stmt_delete->execute();
    $stmt_delete->close();

    // 投稿のいいね数を更新
    $sql_update = "UPDATE posts SET likes = likes - 1 WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("i", $post_id);
    $stmt_update->execute();
    $stmt_update->close();
}

$stmt_check->close();
$conn->close();

header("Location: post_detail.php?id=$post_id");
exit();
