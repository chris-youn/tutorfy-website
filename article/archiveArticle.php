<?php
include('../adminModule/configuration.php');
require '../forum/config.php';

$articleId = $_POST['id'];
$action = $_POST['action'];

if ($action == 'archive') {
    $stmt = $pdo->prepare("UPDATE articles SET archived = 1 WHERE id = :id");
} elseif ($action == 'unarchive') {
    $stmt = $pdo->prepare("UPDATE articles SET archived = 0 WHERE id = :id");
} else {
    header("Location: article.php?error=unknown_action");
    exit();
}

$stmt->bindParam(':id', $articleId, PDO::PARAM_INT);

if ($stmt->execute()) {
    header("Location: article.php");
    exit();
} else {
    header("Location: article.php?error=query_failed");
    exit();
}
?>
