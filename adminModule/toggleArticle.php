<?php
include('../adminModule/configuration.php');

$articleId = $_POST['id'];
$action = $_POST['action'];

if ($action == 'lock') {
    $sql = "UPDATE articles SET archived = 1 WHERE id = :id";
} else if ($action == 'unlock') {
    $sql = "UPDATE articles SET archived = 0 WHERE id = :id";
}

$stmt = $pdo->prepare($sql);

$stmt->bindParam(':id', $articleId, PDO::PARAM_INT);

$stmt->execute();

header("Location: adminModule.php#article-management");
?>