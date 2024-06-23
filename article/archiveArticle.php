<?php
include('../adminModule/configuration.php');
require '../forum/config.php';

session_start();

$articleId = $_POST['id'];
$action = $_POST['action'];

if ($action == 'archive') {
    $stmt = $pdo->prepare("UPDATE articles SET archived = 1 WHERE id = :id");
    $stmt->execute([$article_id]);
    $_SESSION['message'] = 'Article has been archived successfully!';
} elseif ($action == 'unarchive') {
    $stmt = $pdo->prepare("UPDATE articles SET archived = 0 WHERE id = :id");
    $stmt->execute([$article_id]);
    $_SESSION['message'] = 'Article has been unarchived successfully!';
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
