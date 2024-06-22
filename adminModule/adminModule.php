<?php
include('../adminModule/configuration.php');
include('../adminModule/fetchUsers.php');
include('../adminModule/fetchThreads.php');
include('../adminModule/fetchReplies.php');
include("../article/fetchArticles.php");
include('../scripts/functions.php');

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT isAdmin FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || (!$user['isAdmin'] )) {
    header("Location: ../homepage/homepage.php");
    exit();
}

$theme = getUserTheme(); // Fetch the user's theme
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Administration Module</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="adminModule.css">
    <?php if ($theme == 'dark'): ?>
        <link rel="stylesheet" type="text/css" href="../global-dark.css">
    <?php else: ?>
        <link rel="stylesheet" type="text/css" href="../global.css">
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <script src="adminModule.js" defer></script>
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
            <a href="../article/article.php" class="nav-link">Articles</a>
            <a href="../store/store.php" class="nav-link">Store</a>
            <a href="../forum/forum.php" class="nav-link ">Forums</a>
            <a href="../quiz/quiz.php" class="nav-link">Quiz</a>
            <a href="../adminModule/adminModule.php" class="nav-link active">Administration Module</a>
        </nav>

        <!-- No cart icon, as this page is meant to be accessed only by admins -->
        <div class="icons">
            <div class="profileMenu">
                <span class="profileIcon">👤</span>
                    <div class="profileMenuContent">
                        <?php echo getProfileOptions() ?>
                    </div>
            </div>
        </div>
    </header>

    <main class="content">
        <!-- <section class="banner">
            <h1>Administration Module</h1>
        </section> -->

        <section class="admin-intro">
            <h1>Administration Module</h1>
            <p>
                This page is designed for the ease of access of the admininstrators.
                <br><br>
                Using this page, you will be able to: check the details of the users; lock and unlock accounts; 
                archive or delete posts or threads in the discussion forum; archive and unarchive articles. 
            </p>
        </section>

        <section class="user-management" id="user-management">
        <h2>Manage Users</h2>
        <?php fetchUsers($pdo) ?>
        </section>

        <section class="thread-management" id="thread-management">
        <h2>Manage Forum Threads</h2>
        <?php fetchThreads($pdo) ?>
        </section>

        <section class="reply-management" id="reply-management">
        <h2>Manage Individual Forum Posts/Replies</h2>
        <?php fetchReplies($pdo) ?>
        </section>

        <section class="article-management" id="article-management">
            <h2>Manage Articles</h2>
            <?php fetchArticles($pdo) ?>
        </section>

    <button class="scroll-to-top" onclick="scrollToTop()">&#x290A;</button>
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