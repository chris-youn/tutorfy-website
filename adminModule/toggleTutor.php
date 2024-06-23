<?php
include('../adminModule/configuration.php');

$userId = $_POST['id'];
$action = $_POST['action'];

if ($action == 'lock') {
    $sql = "UPDATE users SET isTutor = 1 WHERE id = :id";
} else if ($action == 'unlock') {
    $sql = "UPDATE users SET isTutor = 0 WHERE id = :id";
}

$stmt = $pdo->prepare($sql);

$stmt->bindParam(':id', $userId, PDO::PARAM_INT);

$stmt->execute();

header("Location: adminModule.php#user-management");
?>