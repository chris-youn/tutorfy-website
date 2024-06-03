<?php
include('../adminModule/configuration.php');

function fetchUsers($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($users);
    exit;
}

fetchUsers($pdo);
?>
