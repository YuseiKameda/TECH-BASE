<?php
session_start();
include '../config/config.php';
include '../includes/head.php';
include '../includes/header.php';


// ユーザーがログインしているか確認
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// ログインユーザーのIDを取得
$user_id = $_SESSION['id'];

// ログインユーザーの投稿を取得
$sql = "SELECT id, category, content, likes FROM posts WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

</body>
</html>

main class="flex-grow">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">自分の投稿</h2>
            <div class="grid gap-6">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                    // いいねされているかチェック
                    $sql_like = "SELECT * FROM likes WHERE post_id = ? AND user_id = ?";
                    $stmt_like = $conn->prepare($sql_like);
                    $stmt_like->bind_param("ii", $row['id'], $user_id);
                    $stmt_like->execute();
                    $result_like = $stmt_like->get_result();
                    $is_liked = $result_like->num_rows > 0;
                    ?>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($row['category']); ?></h3>
                        <p class="text-gray-700 mb-4"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="flex items-center text-gray-500 like-button <?php echo $is_liked ? 'liked' : ''; ?>" data-post-id="<?php echo $row['id']; ?>">
                                <i class="fa fa-thumbs-up mr-2"></i><span class="likes-count"><?php echo $row['likes']; ?></span>
                            </span>
                            <div class="flex space-x-4">
                                <a href="user_edit.php?id=<?php echo $row['id']; ?>" class="text-indigo-600 hover:text-indigo-900">編集</a>
                                <form method="post" action="delete_post.php" class="inline">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="text-white bg-red-600 hover:bg-red-700 rounded-md px-4 py-2">削除</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
