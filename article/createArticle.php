<?php
session_start();
require '../forum/config.php';

header('Content-Type: application/json'); // Ensure the response is JSON

// Start output buffering to catch any unexpected output
ob_start();

$response = ['success' => false];

if (!isset($_SESSION['user_id'])) {
    $response['error'] = 'User not logged in';
    echo json_encode($response);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['article-title'];
    $content = $_POST['article-content'];
    $user_id = $_SESSION['user_id'];
    $image_path = null;

    // Handle image upload
    if (isset($_FILES['article-image']) && $_FILES['article-image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['article-image'];
        $image_name = time() . '_' . basename($image['name']);
        $image_path = '../uploads/' . $image_name;

        if (!move_uploaded_file($image['tmp_name'], $image_path)) {
            $response['error'] = 'Image upload failed';
            ob_end_clean(); // Clean the output buffer
            echo json_encode($response);
            exit();
        }
    }

    // Generate a unique ID for the new article
    $stmt = $pdo->prepare("SELECT MAX(id) AS max_id FROM articles");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $newId = $result['max_id'] + 1;

    $stmt = $pdo->prepare("INSERT INTO articles (id, user_id, title, content, image_path) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$newId, $user_id, $title, $content, $image_path])) {
        $article_id = $pdo->lastInsertId();
        $stmt = $pdo->prepare("SELECT articles.*, users.username FROM articles JOIN users ON articles.user_id = users.id WHERE articles.id = ?");
        $stmt->execute([$article_id]);
        $article = $stmt->fetch();

        $response['success'] = true;
        $response['article'] = $article;
        header("Location: tutorModule.php");
    } else {
        $response['error'] = 'Could not create article';
    }
}

ob_end_clean(); // Clean the output buffer
echo json_encode($response); // Echo the JSON response
?>
