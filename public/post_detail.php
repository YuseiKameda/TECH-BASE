<?php
session_start();
include '../config/config.php';

$post_id = $_GET['id'];

// 投稿詳細を取得
$sql = "SELECT posts.id, users.username, posts.category, posts.content, posts.likes FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

// コメントを取得
$sql_comments = "SELECT comments.id, users.username, comments.content, comments.created_at FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = ? ORDER BY comments.created_at DESC";
$stmt_comments = $conn->prepare($sql_comments);
$stmt_comments->bind_param("i", $post_id);
$stmt_comments->execute();
$result_comments = $stmt_comments->get_result();

// コメントの投稿
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    $content = $_POST['content'];
    $sql_insert = "INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iis", $post_id, $user_id, $content);
    if ($stmt_insert->execute()) {
        header("Location: post_detail.php?id=$post_id");
    } else {
        $error = "コメントの投稿に失敗しました。再度お試しください。";
    }
    $stmt_insert->close();
}

$conn->close();
?>

<?php
include '../includes/head.php';
include '../includes/header.php';
?>
<main class="flex-grow">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <div class="post-detail mb-6 ">
                <h2 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($post['category']); ?></h2>
                <p class="mb-4 text-gray-700"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                <p class="text-gray-600">投稿者: <?php echo htmlspecialchars($post['username']); ?></p>
                <p class="text-gray-600 mb-4">いいね: <?php echo $post['likes']; ?></p>
                <?php if (isset($_SESSION['id'])): ?>
                    <a href="like.php?id=<?php echo $post_id; ?>" class="text-blue-500 hover:text-blue-700">いいね</a>
                <?php endif; ?>
            </div>

            <div class="comments-section">
                <h3 class="text-2xl font-semibold mb-4">コメント</h3>
                <?php if (isset($_SESSION['id'])): ?>
                    <form method="post" action="" class="mb-6">
                        <textarea name="content" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none mb-2" required></textarea>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">コメントを投稿</button>
                    </form>
                <?php else: ?>
                    <p class="text-red-500">コメントを投稿するには<a href="login.php" class="text-blue-500 hover:text-blue-700">ログイン</a>してください。</p>
                <?php endif; ?>

                <?php while ($comment = $result_comments->fetch_assoc()): ?>
                    <div class="comment mb-4 p-4 bg-gray-100 rounded-md shadow-sm">
                        <p class="font-semibold"><?php echo htmlspecialchars($comment['username']); ?>:</p>
                        <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                        <p class="text-gray-500 text-sm mt-2">投稿日: <?php echo $comment['created_at']; ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</main>


