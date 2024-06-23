<?php
include('../adminModule/configuration.php');

function fetchThreads($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM threads");
    $stmt->execute();
    $threads = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<table border=\"1\"><tr>
        <th>Thread ID</th><th>User ID</th><th>Title</th><th>Content</th><th>Created At</th><th>Archived</th><th>Actions</th>
    </tr>";
    foreach ($threads as $thread) {
        $charLimitContent = strlen($thread["content"]) > 500 ? substr($thread["content"], 0, 500) . '...&nbsp&nbsp&nbsp&nbsp(CONTINUED)' : $thread["content"];

        echo "<tr>
            <td>".$thread["id"]."</td>
            <td>".$thread["user_id"]."</td>
            <td>".$thread["title"]."</td>
            <td>".$charLimitContent."</td>
            <td>".$thread["created_at"]."</td>
            <td>".($thread["archived"] == 1 ? 'Yes' : 'No')."</td>
            <td>
                <form method='post' action='toggleThread.php'>
                    <input type='hidden' name='id' value='".$thread["id"]."'>
                    <input type='hidden' name='action' value='".($thread["archived"] == 1 ? 'unlock' : 'lock')."'>
                    <button type='submit' class='archive-button'>".($thread["archived"] == 1 ? 'Unlock' : 'Lock')."</button>
                </form>
            </td>
        </tr>";
    }
    echo "</table>";
}
?>