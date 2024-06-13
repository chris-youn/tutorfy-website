<?php
require '../forum/config.php';
include('../scripts/functions.php');

if (!isset($_SESSION['user_id'])) {
    $referrer = urlencode($_SERVER['REQUEST_URI']);
    header("Location: ../login/login.php?referrer=$referrer");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the user is an admin or a tutor
$stmt = $pdo->prepare("SELECT isAdmin, isTutor FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || (!$user['isAdmin'] && !$user['isTutor'])) {
    header("Location: ../homepage/homepage.php");
    exit();
}

$isAdmin = $user['isAdmin'];
$isTutor = $user['isTutor'];

$order = $_GET['order'] ?? 'newest';
$search = $_GET['search'] ?? '';

$orderBy = ($order == 'oldest') ? "ORDER BY created_at ASC" : "ORDER BY created_at DESC";

$searchQuery = '';
$searchParam = '';

if (!empty($search)) {
    $searchQuery = "AND MATCH(title) AGAINST(:search IN BOOLEAN MODE)";
    $searchParam = $search;
}

$sql = "SELECT articles.*, users.profile_image, users.username 
        FROM articles 
        JOIN users ON articles.user_id = users.id 
        WHERE articles.archived = 0 $searchQuery $orderBy";

$stmt = $pdo->prepare($sql);

if (!empty($search)) {
    $stmt->bindParam(':search', $searchParam);
}

$stmt->execute();
$articles = $stmt->fetchAll();

// Fetching the next most relevant title if no exact matches found
$nextRelevantArticle = null;
if (empty($articles) && !empty($search)) {
    $nextSql = "SELECT articles.*, users.profile_image, users.username 
                FROM articles 
                JOIN users ON articles.user_id = users.id 
                WHERE articles.archived = 0 
                ORDER BY MATCH(title) AGAINST(:search IN BOOLEAN MODE) DESC, created_at DESC
                LIMIT 1";
    $nextStmt = $pdo->prepare($nextSql);
    $nextStmt->bindParam(':search', $searchParam);
    $nextStmt->execute();
    $nextRelevantArticle = $nextStmt->fetch();
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Tutorfy | Forums</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="forum_styles.css">
    <link rel="stylesheet" href="../global.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <script src="forum_script.js" defer></script>
    <link rel="stylesheet" type="text/css" href="../global.css">
    <script src="../global.js" defer></script>
</head>

<body>
    <header class="topnav">
        <a href="../homepage/homepage.php">
            <div class="logo">
                <img src="../assets/img/tutorfy-logo.png" alt="Tutorfy Logo">
                <span>Tutorfy</span>
            </div>
        </a>
        <nav class="nav-links">
            <a href="../homepage/homepage.php" class="nav-link">Home</a>
            <a href="../article/article.php" class="nav-link">Articles</a>
            <a href="../store/store.php" class="nav-link">Store</a>
            <a href="../forum/forum.php" class="nav-link active">Forums</a>
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
        <div class="search">
            <form id="search-form" method="GET" action="tutor.php">
                <input type="text" id="search" name="search" placeholder="Search for an article...">
                <button type="submit">Search</button>
            </form>
        </div>

        <div class="new-article">
            <h2>Create a New Article</h2>
            <form id="new-article-form" action="createArticle.php" method="POST" enctype="multipart/form-data">
                <input type="text" id="article-title" name="article-title" placeholder="Article Title" required>
                <textarea id="article-content" name="article-content" placeholder="Write your article..." required></textarea>
                <input type="file" id="article-image" name="article-image" accept="image/*">
                <button type="submit" id="submit-button">Post</button>
            </form>
        </div>

        <div class="time-posted">
            <button type="button" onclick="window.location.href='tutor.php?order=oldest'">Order by Oldest</button>
            <button type="button" onclick="window.location.href='tutor.php?order=newest'">Order by Newest</button>
        </div>

        <div class="article-posts">
            <?php if (empty($articles) && !empty($search)): ?>
                <div class="no-results">
                    <p>No exact matches found for "<?= htmlspecialchars($search) ?>". Showing the most relevant result:</p>
                    <div class="post-wrapper">
                        <a href="external_article.php?id=<?= $nextRelevantArticle['id'] ?>" class="post-link">
                            <div class="posts">
                                <div class="profile-picture">
                                    <img src="<?= htmlspecialchars($nextRelevantArticle['profile_image'] ?: 'default-profile.png') ?>" alt="Profile Picture">
                                </div>
                                <div class="username">
                                    <p><?= htmlspecialchars($nextRelevantArticle['username']) ?></p>
                                </div>
                                <div class="date-posted">
                                    <p><?= $nextRelevantArticle['created_at'] ?></p>
                                </div>
                                <h2><?= htmlspecialchars($nextRelevantArticle['title']) ?></h2>
                                <div class="content-text">
                                    <p><?= htmlspecialchars($nextRelevantArticle['content']) ?></p>
                                </div>
                                <?php if (!empty($nextRelevantArticle['image_path'])): ?>
                                    <div class="content-image">
                                        <img src="<?= htmlspecialchars($nextRelevantArticle['image_path']) ?>" alt="Post Image" style="max-width: 200px; max-height: 150px; cursor: pointer;">
                                    </div>
                                <?php endif; ?>
                                <?php if ($_SESSION['user_id'] == $nextRelevantArticle['user_id'] || $isAdmin): ?>
                                    <button class="archive-button" data-article-id="<?= $nextRelevantArticle['id'] ?>">Delete</button>
                                <?php endif; ?>
                                <a href="external_article.php?id=<?= $nextRelevantArticle['id'] ?>#reply">
                                    <button type="button" class="reply-button">Reply</button>
                                </a>
                            </div>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                    <div class="post-wrapper">
                        <a href="..article/external_article.php?id=<?= $article['id'] ?>" class="post-link">
                            <div class="posts">
                                <div class="profile-picture">
                                    <img src="<?= htmlspecialchars($article['profile_image'] ?: 'default-profile.png') ?>" alt="Profile Picture">
                                </div>
                                <div class="username">
                                    <p><?= htmlspecialchars($article['username']) ?></p>
                                </div>
                                <div class="date-posted">
                                    <p><?= $article['created_at'] ?></p>
                                </div>
                                <h2><?= htmlspecialchars($article['title']) ?></h2>
                                <div class="content-text">
                                    <p><?= htmlspecialchars($article['content']) ?></p>
                                </div>
                                <?php if (!empty($article['image_path'])): ?>
                                    <div class="content-image">
                                        <img src="<?= htmlspecialchars($article['image_path']) ?>" alt="Post Image" style="max-width: 200px; max-height: 150px; cursor: pointer;">
                                    </div>
                                <?php endif; ?>
                                <?php if ($_SESSION['user_id'] == $article['user_id'] || $isAdmin): ?>
                                    <button class="archive-button" data-article-id="<?= $article['id'] ?>">Delete</button>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

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
