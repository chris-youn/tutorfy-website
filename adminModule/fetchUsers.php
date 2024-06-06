<?php
include('../adminModule/configuration.php');

function fetchUsers($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    echo "<table border=\"1\"><tr>
        <th>ID</th><th>Email</th><th>Userame</th><th>Created At</th><th>Theme</th><th>Archived</th><th>Admin</th><th>Tutor</th><th>Actions</th>
    </tr>";
    foreach ($users as $user) {
        // Array keys `id`, `name`, `population` are IDENTICAL TO table (countries) column names
        echo "<tr><td>".$user["id"]."</td><td>".$user["email"]."</td><td>".$user["username"]."</td><td>".$user["created_at"]."</td>
        <td>".$user["theme"]."</td><td>".$user["archived"]."</td><td>".$user["isAdmin"]."</td><td>".$user["isTutor"]."</td><td>Actions</td></tr>";
    }
    echo "</table>";
}
?>
