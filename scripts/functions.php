<?php
session_start();

if (isset($_COOKIE['userConsent']) && $_COOKIE['userConsent'] === 'true') {
    echo '<script src="additionalScript.js" defer></script>';
}

function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserTheme() {
    if (isUserLoggedIn()) {
        require '../forum/config.php';
        $user_id = $_SESSION['user_id'];

        $stmt = $pdo->prepare("SELECT theme FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $theme = $stmt->fetchColumn();

        return $theme ? $theme : 'light';
    }
    return 'light';
}

function getProfileOptions() {
    if (isUserLoggedIn()) {
        return '
                <a href="../login/profile.php">View Profile</a>
                <a href="../login/logout.php">Sign Out</a>
                ';
    } else {
        return '
                <a href="../login/login.php">Sign In</a>
                <a href="../login/register.php">Sign Up</a>
                ';
    }
}

function getProfileFooter() {
    if (isUserLoggedIn()) {
        return '
                <a href="../login/profile.php">Profile</a>
                <a href="../cart/cart.php">Cart</a>
                ';
    } else {
        return '
                <a href="../login/login.php">Sign In</a>
                <a href="../login/register.php">Sign Up</a>
                <a href="../cart/cart.php">Cart</a>
                ';
    }
}
?>