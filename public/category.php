<?php
session_start();
include '../config/config.php';
include '../includes/head.php';
include '../includes/header.php';

// カテゴリフィルター
$category = isset($_GET['category']) ? $_GET['category'] : '';
if (!$category) {
    header('Location: index.php');
    exit();
}

// ページネーション
$limit = 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// 投稿を取得する
$query = 'SELECT posts.id, users.username, posts.category, posts.content, posts.likes FROM posts JOIN users ON posts.user_id = users.id WHERE posts.category = ? ORDER BY posts.created_at DESC LIMIT ? OFFSET ?';
$params = [$category, $limit, $offset];

$stmt = $conn->prepare($query);
$stmt->bind_param('sii', ...$params);
$stmt->execute();
$result = $stmt->get_result();
$posts = $result->fetch_all(MYSQLI_ASSOC);

// 投稿の総数を取得する
$count_query = 'SELECT COUNT(*) FROM posts WHERE category = ?';
$count_stmt = $conn->prepare($count_query);
$count_stmt->bind_param('s', $category);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_posts = $count_result->fetch_array()[0];
$total_pages = ceil($total_posts / $limit);
?>

<main class="flex-grow">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">カテゴリ: <?php echo htmlspecialchars($category, ENT_QUOTES); ?></h2>
            <div class="grid gap-6">
                <?php foreach ($posts as $post): ?>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($post['category']); ?></h3>
                        <p class="text-gray-700 mb-4"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                        <p class="text-gray-500">投稿者: <?php echo htmlspecialchars($post['username']); ?></p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="flex items-center text-gray-500"><i class="fa fa-thumbs-up mr-2"></i><?php echo $post['likes']; ?></span>
                            <a href="post_detail.php?id=<?php echo $post['id']; ?>" class="text-indigo-600 hover:text-indigo-900">詳細を見る</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-6">
                <?php if ($page > 1): ?>
                    <a href="category.php?category=<?php echo htmlspecialchars($category, ENT_QUOTES); ?>&page=<?php echo $page - 1; ?>" class="text-indigo-600 hover:text-indigo-900">前のページ</a>
                <?php endif; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="category.php?category=<?php echo htmlspecialchars($category, ENT_QUOTES); ?>&page=<?php echo $page + 1; ?>" class="text-indigo-600 hover:text-indigo-900">次のページ</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
