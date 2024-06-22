<?php
include('../adminModule/configuration.php');
include('../scripts/functions.php');
require '../forum/config.php';

$_SESSION['orderValidated'] = false;

function validateFullName($name) {
    // Check if the name only contains letters and spaces
    if (preg_match("/^[a-zA-Z\s]+$/", $name)) {
        return true;
    } else {
        return "Full name must contain only letters and spaces.";
    }
}

function validateEmail($email) {
    // Validate email address
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return "Invalid email format.";
    }
}

// Example usage
if (isset($_POST['fullName'])) {
    $fullName = $_POST['fullName'];
} else {
    $fullName = '';
}
if (isset($_POST['email'])) {
    $email = $_POST['email'];
} else {
    $email = '';
}
if (isset($_POST['cart_details'])) {
    $cart_details = $_POST['cart_details'];
} else {
    $cart_details = null;
}

$fullNameValidation = validateFullName($fullName);
$emailValidation = validateEmail($email);

if ($fullNameValidation === true && $emailValidation === true) {
    $_SESSION['orderValidated'] = true;
    $_SESSION['user_email'] = $email;
    $_SESSION['cart_details'] = $cart_details; // Store cart details in session

    // Clear the discount session variable
    unset($_SESSION['discountPercentage']);

    // Optionally, clear other session variables related to the cart
    unset($_SESSION['cart_items']);
    header("Location: order_finished.php");
    exit;
} else {
    // There are errors, redirect to payment failed page
    $_SESSION['orderValidated'] = false;
    header("Location: order_failed.php");
    exit;
}
?>
