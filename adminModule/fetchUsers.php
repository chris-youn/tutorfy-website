<?php
include('../adminModule/configuration.php');

function fetchUsers($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    echo "<table border=\"1\"><tr>
        <th>ID</th><th>Email</th><th>Userame</th><th>Created At</th><th>Theme</th><th>Archived</th><th>Administrator</th><th>Tutor</th><th>Actions</th>
    </tr>";
    foreach ($users as $user) {
        // Array keys `id`, `name`, `population` are IDENTICAL TO table (countries) column names
        echo "<tr>
        <td>".$user["id"]."</td>
        <td>".$user["email"]."</td><td>"
        .$user["username"]."</td><td>"
        .$user["created_at"]."</td>
        <td>".$user["theme"]."</td>
        <td>".($user["archived"] == 1 ? 'Yes' : 'No')."</td>
        <td>".($user["isAdmin"] == 1 ? 'Yes' : 'No')."</td>
        <td>".($user["isTutor"] == 1 ? 'Yes' : 'No')."</td>
        <td>
            <form method='post' action='toggleArchive.php'>
                    <!--Added button, not yet functional-->
                    <button type='submit'>".($user["archived"] == 1 ? 'Unlock' : 'Lock')."</button>
            </form>
        </td>
        </tr>";
    }
    echo "</table>";
}
?>
