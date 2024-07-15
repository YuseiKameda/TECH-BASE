<?php
session_start();
include '../config/config.php';
function get_joke_from_api() {
    $apiEndpoint = 'https://official-joke-api.appspot.com/random_joke';
    $response = file_get_contents($apiEndpoint);
    $jokeData = json_decode($response, true);
    return $jokeData['setup'] . " - " . $jokeData['punchline'];
}

function get_random_post($category, $conn) {
    $sql = "SELECT content FROM posts WHERE category = ? ORDER BY RAND() LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $stmt->close();
    return $post['content'];
}
$category = $_POST['category'];

// APIによる自動生成
if ($category === 'アメリカンジョーク') {
    $content = get_joke_from_api();
} else {
    $content = get_random_post($category, $conn);
}

$conn->close();
?>

<?php
include '../includes/head.php';
include '../includes/header.php';
?>
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">生成されたコンテンツ</h2>
        <p class="text-gray-700"><?php echo htmlspecialchars($content); ?></p>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
