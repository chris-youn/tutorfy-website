<?php
$servername = "talsprddb02.int.its.rmit.edu.au";
$username = "COSC3046_2402_G3";
$password = "CfG6UwtdUhaC";
$dbname = "COSC3046_2402_G3";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
