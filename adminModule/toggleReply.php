<?php
include('../adminModule/configuration.php');

$replyId = $_POST['id'];
$action = $_POST['action'];

if ($action == 'lock') {
    $sql = "UPDATE replies SET archived = 1 WHERE id = :id";
} else if ($action == 'unlock') {
    $sql = "UPDATE replies SET archived = 0 WHERE id = :id";
}

$stmt = $pdo->prepare($sql);

$stmt->bindParam(':id', $replyId, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->errorInfo()]);
}

header("Location: adminModule.php#reply-management");
?>
