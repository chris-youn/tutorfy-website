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

function validateCardNumber($cardNumber) {
    // Remove spaces and hyphens
    $cardNumber = str_replace([' ', '-'], '', $cardNumber);
    if (!ctype_digit($cardNumber)) {
        return "Card number must contain only digits.";
    }

    // Use Luhn's algorithm to validate the card number
    $sum = 0;
    $numDigits = strlen($cardNumber);
    $parity = $numDigits % 2;

    for ($i = 0; $i < $numDigits; $i++) {
        $digit = $cardNumber[$i];
        if ($i % 2 == $parity) {
            $digit *= 2;
            if ($digit > 9) {
                $digit -= 9;
            }
        }
        $sum += $digit;
    }

    if ($sum % 10 == 0) {
        return true;
    } else {
        return "Invalid card number.";
    }
}
function validatecvc($cvc) {
    if (ctype_digit($cvc) && (strlen($cvc) == 3 || strlen($cvc) == 4)) {
        return true;
    } else {
        return "Invalid CVC. It must be 3 or 4 digits.";
    }
}
function validateCardDate($expDate) {
    // Check if the date is in mm/yy format
    if (!preg_match("/^(0[1-9]|1[0-2])\/\d{2}$/", $expDate)) {
        return "Invalid expiration date format. Use mm/yy.";
    }

    // Split the date into month and year
    list($month, $year) = explode('/', $expDate);

    // Prefix the year with 20 to make it a full year
    $year = '20' . $year;

    // Check if the month is valid
    if ($month < 1 || $month > 12) {
        return "Invalid expiration month.";
    }

    $currentYear = date('Y');
    $currentMonth = date('m');

    if ($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
        return "Card has expired.";
    }

    return true;
}
    


// Example usage
if (isset($_POST['fullName'])){
    $fullName = $_POST['fullName'];
} else {
    $fullName = 0;
}
if (isset($_POST['email'])) {
    $email = $_POST['email'];
} else {
    $email = 0;
}
if (isset($_POST['cardNo'])) {
    $cardNumber = $_POST['cardNo'];
} else {
    $cardNumber = 0;
}
if (isset($_POST['cardDate'])) {
    $cardDate = $_POST['cardDate'];
} else {
    $cardDate = 0;
}
if (isset($_POST['cvc'])) {
    $cvc = $_POST['cvc'];
} else {
    $cvc = 0;
}

$fullNameValidation = validateFullName($fullName);
$emailValidation = validateEmail($email);
$cardNumberValidation = validateCardNumber($cardNumber);
$cardDateValidation = validateCardDate($cardDate);
$cvcValidation = validatecvc($cvc);

if ($fullNameValidation === true && $emailValidation === true && $cardNumberValidation === true) {
    $_SESSION['orderValidated'] = true;
    header("Location: order_finished.php");
    exit;
} else {
    // There are errors, redirect to payment failed page
    $_SESSION['orderValidated'] = false;
    header("Location: order_failed.php");
    exit;
}
?>
