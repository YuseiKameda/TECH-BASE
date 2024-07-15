<?php
session_start();
include '../config/config.php';
include '../includes/head.php';
include '../includes/header.php';

// 人気の投稿を取得
$sql = "SELECT posts.id, users.username, posts.category, posts.content, posts.likes FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.likes DESC LIMIT 5";
$result = $conn->query($sql);
?>

<main class="flex-grow">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">笑いの広場へようこそ！</h2>
            <p class="mb-4">このサイトでは毎日クスッと笑えるようなコンテンツを投稿したり、閲覧することができます！ <br>
                何か面白い文章を見たい人や、みんなに知ってもらいたい人はどんどん使ってください！
            </p>
            <h2 class="text-2xl font-semibold mb-4">ランダム生成</h2>
            <form method="post" action="generate_content.php" class="mb-6">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">テーマを選んでください</label>
                <select name="category" id="category" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="ダジャレ">ダジャレ</option>
                    <option value="雑学">雑学</option>
                    <option value="短文">短文</option>
                    <option value="アメリカンジョーク">アメリカンジョーク</option>
                </select>
                <!-- <button type="submit" class="mx-auto mt-3 w-1/2 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">生成</button> -->
                <button type="submit" class="mt-3 w-1/2 sm:w-full mx-auto py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">生成</button>
            </form>
            <h2 class="text-2xl font-semibold mb-4">コンテンツを投稿する</h2>
            <a href="post.php" class="text-center block w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mb-6">新しい投稿を作成</a>
            <div>
            <h2 class="text-2xl font-semibold mb-4">検索</h2>
            <form action="category.php" method="get">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">カテゴリを選んでください</label>
                <select name="category" id="category">
                    <option value="ダジャレ">ダジャレ</option>
                    <option value="雑学">雑学</option>
                    <option value="短文">短文</option>
                    <!-- <option value="アメリカンジョーク">アメリカンジョーク</option> -->
                </select>
                <button type="submit">検索</button>
            </form>
            </div>
            <h2 class="text-2xl font-semibold mb-4">人気の投稿</h2>
            <div class="grid gap-6">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                    // ユーザーがいいねしているか確認
                    $sql_like = "SELECT * FROM likes WHERE post_id = ? AND user_id = ?";
                    $stmt_like = $conn->prepare($sql_like);
                    $stmt_like->bind_param("ii", $row['id'], $_SESSION['id']);
                    $stmt_like->execute();
                    $result_like = $stmt_like->get_result();
                    $is_liked = $result_like->num_rows > 0;
                    ?>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($row['category']); ?></h3>
                        <p class="text-gray-700 mb-4"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                        <p class="text-gray-500">投稿者: <?php echo htmlspecialchars($row['username']); ?></p>
                        <p class="text-gray-600 mb-4">いいね: <span class="likes-count"><?php echo $row['likes']; ?></span></p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="flex items-center text-gray-500"><i class="fa fa-thumbs-up mr-2"></i><?php echo $row['likes']; ?></span>
                            <a href="post_detail.php?id=<?php echo $row['id']; ?>" class="text-indigo-600 hover:text-indigo-900">詳細を見る</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../js/script.js"></script>
