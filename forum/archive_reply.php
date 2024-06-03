<?php
session_start();
require '../forum/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $replyId = $input['reply_id'];
    $userId = $_SESSION['user_id'];

    // Ensure the user owns the reply
    $stmt = $pdo->prepare("UPDATE replies SET archived = 1 WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $replyId, 'user_id' => $userId]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Unable to archive reply']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
