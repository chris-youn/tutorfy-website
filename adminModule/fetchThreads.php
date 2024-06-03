<?php
include('../adminModule/configuration.php');

function fetchThreads($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM threads");
    $stmt->execute();
    $threads = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($threads);
    exit;
}

fetchThreads($pdo);
?>
