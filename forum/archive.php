<?php
session_start();
require '../forum/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../forum/forum_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $threadId = $input['thread_id'];
    $userId = $_SESSION['user_id'];

    // Ensure the user owns the post
    $stmt = $pdo->prepare("UPDATE threads SET archived = 1 WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $threadId, 'user_id' => $userId]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Unable to archive post.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>