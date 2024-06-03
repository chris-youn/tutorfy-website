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
    $title = $_POST['thread-title'];
    $content = $_POST['thread-content'];
    $user_id = $_SESSION['user_id'];
    $image_path = null;

    // Handle image upload
    if (isset($_FILES['thread-image']) && $_FILES['thread-image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['thread-image'];
        $image_name = time() . '_' . basename($image['name']);
        $image_path = '../forum/uploads/' . $image_name;

        if (!move_uploaded_file($image['tmp_name'], $image_path)) {
            $response['error'] = 'Image upload failed';
            ob_end_clean(); // Clean the output buffer
            echo json_encode($response);
            exit();
        }
    }

    $stmt = $pdo->prepare("INSERT INTO threads (user_id, title, content, image_path) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$user_id, $title, $content, $image_path])) {
        $thread_id = $pdo->lastInsertId();
        $stmt = $pdo->prepare("SELECT threads.*, users.username FROM threads JOIN users ON threads.user_id = users.id WHERE threads.id = ?");
        $stmt->execute([$thread_id]);
        $thread = $stmt->fetch();

        $response['success'] = true;
        $response['thread'] = $thread;
    } else {
        $response['error'] = 'Could not create thread';
    }
}

ob_end_clean(); // Clean the output buffer
echo json_encode($response); // Echo the JSON response
?>
