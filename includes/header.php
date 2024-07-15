<header class="bg-gray-800">
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-white">笑いの広場</h1>
            <nav>
                <a href="index.php" class="text-white hover:text-gray-300 ml-4">ホーム</a>
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="user_home.php" class="text-white hover:text-gray-300 ml-4">自分の投稿</a>
                    <a href="logout.php" class="text-white hover:text-gray-300 ml-4">ログアウト</a>
                    <span class="text-white ml-4"><?php echo htmlspecialchars($username); ?></span>
                <?php else: ?>
                    <a href="login.php" class="text-white hover:text-gray-300 ml-4">ログイン</a>
                    <a href="register.php" class="text-white hover:text-gray-300 ml-4">新規登録</a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>
