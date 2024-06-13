<?php
$servername = "talsprddb02.int.its.rmit.edu.au";
$username = "COSC3046_2402_G3";
$password = "CfG6UwtdUhaC";
$dbname = "COSC3046_2402_G3";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT session_data FROM whiteboard_sessions WHERE id = 'some_session_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(["status" => "success", "sessionData" => $row['session_data']]);
} else {
    echo json_encode(["status" => "error", "message" => "Session not found"]);
}

$conn->close();
?>