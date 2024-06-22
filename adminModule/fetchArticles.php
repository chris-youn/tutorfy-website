<?php
include('../adminModule/configuration.php');

function fetchArticles($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM articles");
    $stmt->execute();
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);


    echo "<table border=\"1\"><tr>
        <th>ID</th><th>Tutor ID</th><th>Title</th><th>Content</th><th>Subject</th><th>Created At</th><th>Archived</th><th>Action</th>
    </tr>";
    foreach ($articles as $article) {
        $charLimitContent = strlen($article["content"]) > 500 ? substr($article["content"], 0, 500) . '...&nbsp&nbsp&nbsp&nbsp(CONTINUED)' : $article["content"];

        echo "<tr>
            <td>".$article["id"]."</td>
            <td>".$article["user_id"]."</td>
            <td>".$article["title"]."</td>
            <td>".$charLimitContent."</td>
            <td>".$article["subject"]."</td>
            <td>".$article["created_at"]."</td>
            <td>".($article["archived"] == 1 ? 'Yes' : 'No')."</td>
            <td>
                <form method='post' action='toggleArticle.php'>
                    <input type='hidden' name='id' value='".$article["id"]."'>
                    <input type='hidden' name='action' value='".($article["archived"] == 1 ? 'unlock' : 'lock')."'>
                    <button type='submit' class='archive-button'>".($article["archived"] == 1 ? 'Unlock' : 'Lock')."</button>
                </form>
            </td>
        </tr>";
    }
    echo "</table>";
}
?>