<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
include '../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashed_password);

    if ($stmt->num_rows == 1) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: index.php");
        } else {
            $error = "無効なパスワードです。";
        }
    } else {
        $error = "そのユーザー名は存在しません。";
    }
    $stmt->close();
}
$conn->close();
?>

<?php
include '../includes/head.php';
include '../includes/header.php';
?>
<div class="login-form">
    <h2>ログイン</h2>
    <form method="post" action="">
        <label for="username">ユーザー名</label>
        <input type="text" name="username" required>
        <label for="password">パスワード</label>
        <input type="password" name="password" required>
        <button type="submit">ログイン</button>
    </form>
    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
</div>
<?php include '../includes/footer.php'; ?>
