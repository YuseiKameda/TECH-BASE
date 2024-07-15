<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['id'];
    $category = $_POST['category'];
    $content = $_POST['content'];

    $sql = "INSERT INTO posts (user_id, category, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $category, $content);

    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        $error = "投稿に失敗しました。再度お試しください。";
    }
    $stmt->close();
}
$conn->close();
?>

<?php
include '../includes/head.php';
include '../includes/header.php';
?>
<div class="post-form bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-3xl">
    <h2>新しい投稿</h2>
    <form method="post" action="">
        <label for="category" class="block text-gray-700">テーマ</label>
        <select name="category" id="category" class="w-full p-2 border border-gray-300 rounded-md">
            <option value="ダジャレ">ダジャレ</option>
            <option value="短文">短文</option>
            <option value="雑学">雑学</option>
            <!-- <option value="アメリカンジョーク">アメリカンジョーク</option> -->
        </select>
        <label for="content">コンテンツ</label>
        <textarea name="content" id="content" class="w-full h-48 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" required></textarea>
        <button type="submit">投稿</button>
    </form>
    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
</div>
<?php include '../includes/footer.php'; ?>
