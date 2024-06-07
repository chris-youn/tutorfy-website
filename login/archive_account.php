<?php
include('../scripts/functions.php');
include('../login/config.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Update the archived status of the user
$stmt = $conn->prepare("UPDATE users SET archived = 1 WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    // Successfully archived the account, now log out the user
    session_unset();
    session_destroy();
    header("Location: ../login/login.php");
    exit;
} else {
    echo "Error archiving account.";
}

$stmt->close();
?>