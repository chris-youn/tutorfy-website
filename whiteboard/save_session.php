<?php
$servername = "talsprddb02.int.its.rmit.edu.au";
$username = "COSC3046_2402_G3";
$password = "CfG6UwtdUhaC";
$dbname = "COSC3046_2402_G3";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);
$sessionData = $data['sessionData'];
$sql = "INSERT INTO whiteboard_sessions (session_data) VALUES ('$sessionData')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}

$conn->close();
?>