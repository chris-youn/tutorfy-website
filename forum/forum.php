<?php
session_start();
require '../forum/config.php';

function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getProfileOptions() {
    if (isUserLoggedIn()) {
        return '
                <a href="../login/profile.php">View Profile</a>
                <a href="../login/logout.php">Sign Out</a>
                ';
    } else {
        return '
                <a href="../login/login.php">Sign In</a>
                <a href="../login/register.php">Sign Up</a>
                ';
    }
}

if (!isset($_SESSION['user_id'])) {
    $referrer = urlencode($_SERVER['REQUEST_URI']);
    header("Location: ../login/login.php?referrer=$referrer");
    exit();
}

$order = $_GET['order'] ?? 'newest';
$search = $_GET['search'] ?? '';

$orderBy = ($order == 'oldest') ? "ORDER BY created_at ASC" : "ORDER BY created_at DESC";

$searchQuery = '';
$searchParam = '';

if (!empty($search)) {
    $searchQuery = "AND MATCH(title) AGAINST(:search IN BOOLEAN MODE)";
    $searchParam = $search;
}

$sql = "SELECT threads.*, users.profile_image, users.username 
        FROM threads 
        JOIN users ON threads.user_id = users.id 
        WHERE threads.archived = 0 $searchQuery $orderBy";

$stmt = $pdo->prepare($sql);

if (!empty($search)) {
    $stmt->bindParam(':search', $searchParam);
}

$stmt->execute();
$threads = $stmt->fetchAll();

// Fetching the next most relevant title if no exact matches found
$nextRelevantThread = null;
if (empty($threads) && !empty($search)) {
    $nextSql = "SELECT threads.*, users.profile_image, users.username 
                FROM threads 
                JOIN users ON threads.user_id = users.id 
                WHERE threads.archived = 0 
                ORDER BY MATCH(title) AGAINST(:search IN BOOLEAN MODE) DESC, created_at DESC
                LIMIT 1";
    $nextStmt = $pdo->prepare($nextSql);
    $nextStmt->bindParam(':search', $searchParam);
    $nextStmt->execute();
    $nextRelevantThread = $nextStmt->fetch();
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
        <div class="logo">
            <img src="logo.png" alt="Logo">
            <span>Tutorfy</span>
        </div>
        <nav class="nav-links">
            <a href="../homepage/homepage.php" class="nav-link">Home</a>
            <a href="../article/article.php" class="nav-link">Articles</a>
            <a href="../store/store.php" class="nav-link">Store</a>
            <a href="../forum/forum.php" class="nav-link active">Forums</a>
            <a href="../adminModule/adminModule.php" class="nav-link">Administration Module</a>
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
            <form id="search-form" method="GET" action="forum.php">
                <input type="text" id="search" name="search" placeholder="Search for a thread...">
                <button type="submit">Search</button>
            </form>
        </div>

        <div class="new-thread">
            <h2>Create a New Thread</h2>
            <form id="new-thread-form" action="create_thread.php" method="POST" enctype="multipart/form-data">
                <input type="text" id="thread-title" name="thread-title" placeholder="Thread Title" required>
                <textarea id="thread-content" name="thread-content" placeholder="Write your post..." required></textarea>
                <input type="file" id="thread-image" name="thread-image" accept="image/*">
                <button type="submit" id="submit-button">Post</button>
            </form>
        </div>

        <div class="forum-posts">
            <?php if (empty($threads) && !empty($search)): ?>
                <div class="no-results">
                    <p>No exact matches found for "<?= htmlspecialchars($search) ?>". Showing the most relevant result:</p>
                    <div class="post-wrapper">
                        <a href="../forum/external_forum.php?id=<?= $nextRelevantThread['id'] ?>" class="post-link">
                            <div class="posts">
                                <div class="profile-picture">
                                    <img src="<?= htmlspecialchars($nextRelevantThread['profile_image'] ?: 'default-profile.png') ?>" alt="Profile Picture">
                                </div>
                                <div class="username">
                                    <p><?= htmlspecialchars($nextRelevantThread['username']) ?></p>
                                </div>
                                <div class="date-posted">
                                    <p><?= $nextRelevantThread['created_at'] ?></p>
                                </div>
                                <h2><?= htmlspecialchars($nextRelevantThread['title']) ?></h2>
                                <div class="content-text">
                                    <p><?= htmlspecialchars($nextRelevantThread['content']) ?></p>
                                </div>
                                <?php if (!empty($nextRelevantThread['image_path'])): ?>
                                    <div class="content-image">
                                        <img src="<?= htmlspecialchars($nextRelevantThread['image_path']) ?>" alt="Post Image" style="max-width: 200px; max-height: 150px; cursor: pointer;">
                                    </div>
                                <?php endif; ?>
                                <?php if ($_SESSION['user_id'] == $nextRelevantThread['user_id']): ?>
                                    <button class="archive-button" data-thread-id="<?= $nextRelevantThread['id'] ?>">Archive</button>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($threads as $thread): ?>
                    <div class="post-wrapper">
                        <a href="../forum/external_forum.php?id=<?= $thread['id'] ?>" class="post-link">
                            <div class="posts">
                                <div class="profile-picture">
                                    <img src="<?= htmlspecialchars($thread['profile_image'] ?: 'default-profile.png') ?>" alt="Profile Picture">
                                </div>
                                <div class="username">
                                    <p><?= htmlspecialchars($thread['username']) ?></p>
                                </div>
                                <div class="date-posted">
                                    <p><?= $thread['created_at'] ?></p>
                                </div>
                                <h2><?= htmlspecialchars($thread['title']) ?></h2>
                                <div class="content-text">
                                    <p><?= htmlspecialchars($thread['content']) ?></p>
                                </div>
                                <?php if (!empty($thread['image_path'])): ?>
                                    <div class="content-image">
                                        <img src="<?= htmlspecialchars($thread['image_path']) ?>" alt="Post Image" style="max-width: 200px; max-height: 150px; cursor: pointer;">
                                    </div>
                                <?php endif; ?>
                                <?php if ($_SESSION['user_id'] == $thread['user_id']): ?>
                                    <button class="archive-button" data-thread-id="<?= $thread['id'] ?>">Archive</button>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="time-posted">
            <form method="GET" action="forum.php">
                <button type="submit" name="order" value="newest" class="recent-post">Most recent post uploaded</button>
                <button type="submit" name="order" value="oldest" class="oldest-post">Oldest post uploaded</button>
            </form>
        </div>
    </main>

    <div class="cookie-consent-overlay" id="cookieConsent">
        <div class="cookie-consent-box">
            <p>We use cookies to ensure you get the best experience on our website. <a href="../policy/policy.php" target="_blank">Learn more</a></p>
            <button class="cookie-accept-btn">Accept</button>
            <button class="cookie-decline-btn">Decline</button>
        </div>
    </div>  

    <footer class="footer">
        <div class="socials">
            <p>Socials:</p>
            <a href="https://www.facebook.com/"><img src="../images/facebook.png" alt="Facebook"></a>
            <a href="https://www.instagram.com/"><img src="../images/instagram.png" alt="Instagram"></a>
            <a href="https://twitter.com/"><img src="../images/twitter.png" alt="Twitter"></a>
        </div>
    </footer>
</body>
</html>
