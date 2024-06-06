<?php
include('../adminModule/configuration.php');

include('../adminModule/fetchUsers.php');

session_start();

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
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Administration Module</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="adminModule.css">
    <link rel="stylesheet" type="text/css" href="../global.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <script src="adminModule.js" defer></script>
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
            <a href="../forum/forum.php" class="nav-link ">Forums</a>
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
        <section class="banner">
            <h1>Administration Module</h1>
        </section>

        <section class="admin-intro">
            <h2>Welcome to the Administration Module</h2>
            <p>
                This page is designed for the ease of access of the admininstrators.
                <br>
                Using this page, you will be able to: check and change details of the users; lock and unlock accounts; 
                archive or delete posts or threads in the discussion forum; add and remove aricles. 
            </p>
        </section>

        <section class="user-management">
        <h2>Manage Users</h2>
        <?php fetchUsers($pdo) ?>
        <!-- <table id="userTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Created At</th>
                    <th>Theme</th>
                    <th>Archived</th>
                    <th>Admin</th>
                    <th>Tutor</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
            </table> -->
        </section>

        <section class="thread-management">
        <h2>Manage Forum Threads</h2>
        <table id="threadTable">
            <thead>
                <tr>
                    <th>Thread ID</th>
                    <th>User ID</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Created At</th>
                    <th>Archived</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
            </table>
        </section>

        <section class="reply-management">
        <h2>Manage Individual Forum Posts/Replies</h2>
        <table id="replyTable">
            <thead>
                <tr>
                    <th>Post ID</th>
                    <th>User ID</th>
                    <th>Parent Thread ID</th>
                    <th>Content</th>
                    <th>Created At</th>
                    <th>Archived</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
            </table>
        </section>


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
                <a href="../login/login.php" class="`sec-nav">Login</a>
                <a href="../cart/cart.php" class="sec-nav">Cart</a>
            </div>
        </div>
        <h4>&copy Tutorfy | Web Programming Studio 2023</h4>
    </footer>
</body>
</html>