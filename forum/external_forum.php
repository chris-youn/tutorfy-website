<?php
require '../forum/config.php';
include('../scripts/functions.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$thread_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reply'])) {
    $reply_content = $_POST['reply_content'];
    $user_id = $_SESSION['user_id'];

    if (!empty($reply_content)) {
        $insert_reply_stmt = $pdo->prepare("INSERT INTO replies (thread_id, user_id, content) VALUES (?, ?, ?)");
        $insert_reply_stmt->execute([$thread_id, $user_id, $reply_content]);

        header("Location: external_forum.php?id=" . $thread_id);
        exit();
    }
}

$stmt = $pdo->prepare("SELECT threads.*, users.username, users.profile_image FROM threads JOIN users ON threads.user_id = users.id WHERE threads.id = ?");
$stmt->execute([$thread_id]);
$thread = $stmt->fetch();

$replies_stmt = $pdo->prepare("SELECT replies.*, users.username, users.profile_image FROM replies JOIN users ON replies.user_id = users.id WHERE replies.thread_id = ? AND replies.archived = 0 ORDER BY created_at ASC");
$replies_stmt->execute([$thread_id]);
$replies = $replies_stmt->fetchAll();
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Forum Post</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../global.css">
    <link rel="stylesheet" href="external_forum.css">
    <script src="external_forum.js"></script>
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
            <a href="../adminModule/adminModule.php" class="nav-link">Administration Module</a>
        </nav>
        <div class="icons">
            <div class="container">
                <span class="icon" id="cartIcon">🛒<span id="cartBadge" class="badge">0</span></span>
                <div id ="shopping-cart" class="shopping-cart" style="display:none;">
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
        <a href="../forum/forum.php" class="back-button">← Back to Forum</a>
        <div class="forum-post">
            <div class="post-content">
                <div class="post-header">
                    <div class="profile-picture">
                        <img src="<?= htmlspecialchars($thread['profile_image'] ?: 'default-profile.png') ?>" alt="Profile Picture">
                    </div>
                    <div class="post-info">
                        <span class="post-username"><?= htmlspecialchars($thread['username']) ?></span>
                        <span class="post-timestamp" data-timestamp="<?= $thread['created_at'] ?>"><?= date('F j, Y, g:i a', strtotime($thread['created_at'])) ?></span>
                    </div>
                </div>
                <h2><?= htmlspecialchars($thread['title']) ?></h2>
                <p><?= htmlspecialchars($thread['content']) ?></p>
                <?php if (!empty($thread['image_path'])): ?>
                    <div class="content-image">
                        <img src="../forum/<?= htmlspecialchars($thread['image_path']) ?>" alt="Thread Image" style="max-width: 500px; max-height: 450px;">
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="reply-form">
            <h3>Reply to this thread</h3>
            <form action="external_forum.php?id=<?= $thread_id ?>" method="POST">
                <textarea name="reply_content" rows="4" cols="50" placeholder="Enter your reply..." required></textarea><br>
                <button type="submit" name="submit_reply">Submit Reply</button>
            </form>
        </div>

        <div class="replies-section">
            <h3>Replies</h3>
            <?php foreach ($replies as $reply): ?>
                <div class="reply">
                    <div class="reply-header">
                        <div class="profile-picture">
                            <img src="<?= htmlspecialchars($reply['profile_image'] ?: 'default-profile.png') ?>" alt="Profile Picture">
                        </div>
                        <div class="reply-info">
                            <span class="reply-username"><?= htmlspecialchars($reply['username']) ?></span>
                            <span class="reply-timestamp"><?= date('F j, Y, g:i a', strtotime($reply['created_at'])) ?></span>
                        </div>
                    </div>
                    <p><?= htmlspecialchars($reply['content']) ?></p>
                    <?php if ($_SESSION['user_id'] == $reply['user_id']): ?>
                        <button class="archive-reply-button" data-reply-id="<?= $reply['id'] ?>">Delete</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
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
