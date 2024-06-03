<?php
include('../adminModule/configuration.php');

function fetchReplies($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM replies");
    $stmt->execute();
    $replies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($replies);
    exit;
}

fetchReplies($pdo);
?>
