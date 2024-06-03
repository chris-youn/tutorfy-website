<?php
include('../login/config.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $theme = trim($_POST['theme']);
    $profile_image = $_FILES['profile_image'];

    // Handle profile image upload
    if ($profile_image['error'] == 0) {
        $target_dir = "../forum/uploads/";
        $target_file = $target_dir . basename($profile_image["name"]);
        move_uploaded_file($profile_image["tmp_name"], $target_file);
    } else {
        $target_file = NULL;
    }

    // Prepare the SQL statement for updating user profile
    if ($target_file) {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, theme = ?, profile_image = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $username, $email, $theme, $target_file, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, theme = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $email, $theme, $user_id);
    }

    if ($stmt->execute()) {
        echo "Profile updated!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
