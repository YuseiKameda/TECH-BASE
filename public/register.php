<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
include '../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // usernameの重複チェック
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // usernameが既に存在する場合の処理
        $error = "そのユーザー名は既に使用されています。";
    } else {
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            $_SESSION['id'] = $stmt->insert_id; // ユーザーIDをセッションに保存
            $_SESSION['username'] = $username; // ユーザー名をセッションに保存
            header("Location: index.php"); // ホーム画面にリダイレクト
            exit();
        } else {
            $error = "登録に失敗しました。再度お試しください。";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<?php
include '../includes/head.php';
include '../includes/header.php';
?>
<div class="register-form">
    <h2>新規登録</h2>
    <form method="post" action="register.php">
        <label for="username">ユーザー名</label>
        <input type="text" name="username" required>
        <label for="password">パスワード</label>
        <input type="password" name="password" required>
        <button type="submit">登録</button>
    </form>
    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
</div>
<?php include '../includes/footer.php'; ?>
