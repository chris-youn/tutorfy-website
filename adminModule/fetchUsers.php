<?php
include('../adminModule/configuration.php');

function fetchUsers($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);


    echo "<table border=\"1\"><tr>
        <th>ID</th><th>Email</th><th>Username</th><th>Created At</th><th>Theme</th><th>Archived</th><th>Admin</th><th>Tutor</th><th>Actions</th>
    </tr>";
    foreach ($users as $user) {

        echo "<tr>
            <td>".$user["id"]."</td>
            <td>".$user["email"]."</td>
            <td>".$user["username"]."</td>
            <td>".$user["created_at"]."</td>
            <td>".$user["theme"]."</td>
            <td>".($user["archived"] == 1 ? 'Yes' : 'No')."</td>
            <td>".($user["isAdmin"] == 1 ? 'Yes' : 'No')."</td>
            <td>".($user["isTutor"] == 1 ? 'Yes' : 'No')."</td>
            <td>
                <form method='post' action='toggleArchive.php'>
                    <input type='hidden' name='id' value='".$user["id"]."'>
                    <input type='hidden' name='action' value='".($user["archived"] == 1 ? 'unlock' : 'lock')."'>
                    <button type='submit' class='archive-button'>".($user["archived"] == 1 ? 'Unlock' : 'Lock')."</button>
                </form>

                <form method='post' action='toggleTutor.php'>
                    <input type='hidden' name='id' value='".$user["id"]."'>
                    <input type='hidden' name='action' value='".($user["isTutor"] == 1 ? 'unlock' : 'lock')."'>
                    <button type='submit' class='archive-button'>".($user["isTutor"] == 1 ? 'Remove as Tutor' : 'Add as Tutor')."</button>
                </form>

                <form method='post' action='toggleAdmin.php'>
                    <input type='hidden' name='id' value='".$user["id"]."'>
                    <input type='hidden' name='action' value='".($user["isAdmin"] == 1 ? 'unlock' : 'lock')."'>
                    <button type='submit' class='archive-button'>".($user["isAdmin"] == 1 ? 'Remove as Admin' : 'Add as Admin')."</button>
                </form>
            </td>
        </tr>";
    }
    echo "</table>";
}
?>
