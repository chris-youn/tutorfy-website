<?php
include('../adminModule/configuration.php');

$userId = $_POST['id'];
$action = $_POST['action'];
//Retrieves action and id from the fetchUser form

if ($action == 'lock') {
    $sql = "UPDATE users SET archived = 1 WHERE id = :id";
} else if ($action == 'unlock') {
    $sql = "UPDATE users SET archived = 0 WHERE id = :id";
}
//If the action is lock, changes archived status to true, and vice versa with lock = 0

$stmt = $pdo->prepare($sql); //Prepare the query

$stmt->bindParam(':id', $userId, PDO::PARAM_INT); // Assigns the ID to the userId

$stmt->execute(); //Execute the query

header("Location: adminModule.php#user-management"); //Redirect to the Admin module user-management. 
//A similar logic applies to all toggle scripts
?>