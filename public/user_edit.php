<?php
session_start();
include '../config/config.php';
include '../includes/head.php';
include '../includes/header.php';

// ユーザーがログインしているか確認
if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit();
}

// 投稿IDを取得
$post_id = $_GET['id'];

// 投稿の詳細を取得
$sql = "SELECT id, category, content FROM posts WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $post_id, $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    echo "投稿が見つかりません。";
    exit();
}

// フォームが送信された場合、投稿を更新
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $content = $_POST['content'];

    $sql = "UPDATE posts SET category = ?, content = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $category, $content, $post_id, $_SESSION['id']);
    $stmt->execute();

    header('Location: user_home.php');
    exit();
}
?>

<main class="flex-grow">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">投稿を編集</h2>
            <form method="post">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">テーマ:</label>
                <select name="category" id="category" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="ダジャレ" <?php echo $post['category'] === 'ダジャレ' ? 'selected' : ''; ?>>ダジャレ</option>
                    <option value="雑学" <?php echo $post['category'] === '雑学' ? 'selected' : ''; ?>>雑学</option>
                    <option value="短文" <?php echo $post['category'] === '短文' ? 'selected' : ''; ?>>短文</option>
                    <option value="アメリカンジョーク" <?php echo $post['category'] === 'アメリカンジョーク' ? 'selected' : ''; ?>>アメリカンジョーク</option>
                </select>

                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">内容:</label>
                <textarea name="content" id="content" rows="5" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?php echo htmlspecialchars($post['content']); ?></textarea>

                <button type="submit" class="mt-3 w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">更新</button>
            </form>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
