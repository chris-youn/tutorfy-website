<?php
include('../adminModule/configuration.php');

function fetchReplies($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM replies");
    $stmt->execute();
    $replies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<table border=\"1\"><tr>
        <th>Post ID</th><th>User ID</th><th>Parent Thread ID</th><th>Content</th><th>Created At</th><th>Archived</th><th>Actions</th>
    </tr>";
    foreach ($replies as $reply) {
        echo "<tr>
            <td>".$reply["id"]."</td>
            <td>".$reply["thread_id"]."</td>
            <td>".$reply["user_id"]."</td>
            <td>".$reply["content"]."</td>
            <td>".$reply["created_at"]."</td>
            <td>".($reply["archived"] == 1 ? 'Yes' : 'No')."</td>
            <td>Actions</td>
        </tr>";
    }
    echo "</table>";
}
?>
