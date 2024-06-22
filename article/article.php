<?php
include('../adminModule/configuration.php');
include('../scripts/functions.php');
require '../forum/config.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$isAdmin = false;
$isTutor = false;

if ($user_id) {
    // Fetch the isAdmin status if the user is logged in
    $stmt = $pdo->prepare("SELECT isAdmin FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $isAdmin = $stmt->fetchColumn();
}

if ($user_id) {
    // Fetch the isTutor status if the user is logged in
    $stmt = $pdo->prepare("SELECT isTutor FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $isTutor = $stmt->fetchColumn();
}

$theme = getUserTheme(); // Fetch the user's theme


function displayArticles($pdo, $isAdmin, $isTutor){
    $stmt = $pdo->query("SELECT articles.*, users.username FROM articles JOIN users ON articles.user_id = users.id WHERE articles.archived = 0 ORDER BY articles.created_at DESC");
            $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($articles as $article) {
                echo "<div class='article'>";
                echo "<h2>{$article['title']}</h2>";
                echo "<p>By {$article['username']} <strong>(TUTOR)</strong> on {$article['created_at']} &nbsp&nbsp&nbsp&nbsp Subject: {$article['subject']}</p>";
                echo "<details>";
                echo "<summary><em>Click here to read the article...</em></summary>";
                if ($article['image_path']) {
                    echo "<img src='{$article['image_path']}' alt='Article Image' class='article-image'>";
                    echo "<br>";
                }
                echo "<p style='white-space: pre-line'>".($article['content'])."</p>";
                echo "</details>";

                if ($isAdmin || $isTutor) {
                    echo '<form method="POST" action="archiveArticle.php" class="archive-form">';
                    echo '<input type="hidden" name="id" value="' . $article['id'] . '">';
                    echo '<input type="hidden" name="action" value="archive">';
                    echo '<button type="submit" class="archive-button">Archive</button>';
                    echo '</form>';
                }

                echo "</div>";
            }
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Tutorfy | Articles</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="article.css">
    <?php if ($theme == 'dark'): ?>
        <link rel="stylesheet" type="text/css" href="../global-dark.css">
    <?php else: ?>
        <link rel="stylesheet" type="text/css" href="../global.css">
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <script src="article.js" defer></script>
    <script src="../global.js" defer></script>
</head>

<body>
    <header class="topnav">
        <a href="../homepage/homepage.php">
            <div class="logo">
                <?php if ($theme == 'dark'): ?>
                    <img src="../assets/img/tutorfy-logo-white.png" alt="Tutorfy Logo">
                <?php else: ?> 
                    <img src="../assets/img/tutorfy-logo.png" alt="Tutorfy Logo">
                <?php endif; ?>
                <span>Tutorfy</span>
            </div>
        </a>
        <nav class="nav-links">
            <a href="../homepage/homepage.php" class="nav-link">Home</a>
            <a href="../article/article.php" class="nav-link active">Articles</a>
            <a href="../store/store.php" class="nav-link">Store</a>
            <a href="../forum/forum.php" class="nav-link">Forums</a>
            <a href="../quiz/quiz.php" class="nav-link">Quiz</a>
            <?php if ($isAdmin): ?>
                <a href="../adminModule/adminModule.php" class="nav-link">Administration Module</a>
            <?php endif; ?>
        </nav>
        <div class="icons">
            <div class="container">
                <span class="icon" id="cartIcon">🛒<span id="cartBadge" class="badge">0</span></span>
                <div id="shopping-cart" class="shopping-cart" style="display:none;">
                    <div class="shopping-cart-header">
                        <div class="shopping-cart-total">
                            <span id="totalText" class="light-text">Total: $0</span>
                        </div>
                    </div>
                    <ul class="shopping-cart-items" id="items">
                        <li id="tutorSessionListItem">
                            <div id='tutorSessionCartShort'></div>
                            <button id="tutorSessionClear">X</button>
                            <button id="tutorSessionRemove">-</button>
                            <button id="tutorSessionAdd">+</button>
                        </li>
                        <li id="tutorSessionLongListItem">
                            <div id='tutorSessionCartLong'></div>
                            <button id="tutorSessionLongClear">X</button>
                            <button id="tutorSessionLongRemove">-</button>
                            <button id="tutorSessionLongAdd">+</button>
                        </li>
                        <li id="tutorSessionShortBulkListItem">
                            <div id='tutorSessionCartShortBulk'></div>
                            <button id="tutorSessionShortBulkClear">X</button>
                            <button id="tutorSessionShortBulkRemove">-</button>
                            <button id="tutorSessionShortBulkAdd">+</button>
                        </li>
                        <li id="tutorSessionLongBulkListItem">
                            <div id='tutorSessionCartLongBulk'></div>
                            <button id="tutorSessionLongBulkClear">X</button>
                            <button id="tutorSessionLongBulkRemove">-</button>
                            <button id="tutorSessionLongBulkAdd">+</button>
                        </li>
                    </ul>
                    <form action="../cart/cart.php" method="post">
                        <div class="checkout">
                            <input id="cartCheckout" type="submit" value="Checkout"></input>
                        </div>
                    </form>
                </div>
            </div>
            <div class="profileMenu">
                <span class="profileIcon">👤</span>
                <div class="profileMenuContent">
                    <?php echo getProfileOptions() ?>
                </div>
            </div>
        </div>
    </header>

    <main class="content">
        <div class="articles-section">
            <h1>Articles</h1>
            <div class="articles">
                <?php displayArticles($pdo, $isAdmin, $isTutor); ?>
            </div>
        </div>
   

        <div class="sidebar">
            <div class="filter">
                <h3>Filter by Keyword</h3>
                <input type="text" id="search" name="search" placeholder="Search...">
                <button type="submit" onclick="search()">Search</button>
            </div>
            <div class="filter">
                <h3>Filter by Subject</h3>
                <select id="subject" name="subject">
                    <option value="math">Math</option>
                    <option value="science">Science</option>
                    <option value="english">English</option>
                    <option value="geography">Geography</option>
                </select>
                <button type="submit" onclick="filter()">Filter</button>
            </div>
        </div>
    <button class="scroll-to-top" onclick="scrollToTop()">&#x290A;</button>
    </main>

    <div class="cookie-consent-overlay" id="cookieConsent">
        <div class="cookie-consent-box">
            <p>We use cookies to ensure you get the best experience on our website. <a href="../policy/policy.php" target="_blank">Learn more</a></p>
            <button class="cookie-accept-btn">Accept</button>
            <button class="cookie-decline-btn">Decline</button>
        </div>
    </div>  

    <footer>
        <div class="sec-links">
            <div class="tutorfy">
                <h4>Tutorfy</h4>
                <a href="../homepage/homepage.php" class="sec-nav">Home</a>
                <a href="../article/article.php" class="sec-nav">Articles</a>
                <a href="../store/store.php" class="sec-nav">Store</a>
                <a href="../forum/forum.php" class="sec-nav">Forums</a>
            </div>

            <div class="about">
                <h4>About</h4>
                <a href="../policy/policy.php" class="sec-nav">Cookie and Privacy Policy</a>
                <a href="../contact/contact.php" class="sec-nav">Contact us</a>
            </div>

            <div class="account">
                <h4>Account</h4>
                <?php echo getProfileFooter() ?>
            </div>
        </div>
        <h4>&copy Tutorfy | Web Programming Studio 2024</h4>
    </footer>
</body>
</html>
