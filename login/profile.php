<?php
include('../scripts/functions.php');
include('../forum/config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

include('config.php');
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT username, email, theme, profile_image FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($username, $email, $theme, $profile_image);
$stmt->fetch();
$stmt->close();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$isAdmin = false;

if ($user_id) {
    // Fetch the isAdmin status if the user is logged in
    $stmt = $pdo->prepare("SELECT isAdmin FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $isAdmin = $stmt->fetchColumn();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tutorfy | Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="profile.css">
    <?php if ($theme == 'dark'): ?>
        <link rel="stylesheet" type="text/css" href="../global-dark.css">
    <?php else: ?>
        <link rel="stylesheet" type="text/css" href="../global.css">
    <?php endif; ?>
    <script src="../global.js"></script>
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
            <a href="../forum/forum.php" class="nav-link">Forums</a>
            <a href="../quiz/quiz.php" class="nav-link">Quiz</a>
            <?php if ($isAdmin): ?>
                <a href="../adminModule/adminModule.php" class="nav-link">Administration Module</a>
            <?php endif; ?>
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
    
    <main class="profile-page">
        <div class="profile-container">
            <h2>Profile</h2>
            <form action="update_profile.php" method="POST" enctype="multipart/form-data" class="profile-form">
                <div class="profile-image">
                    <?php if ($profile_image): ?>
                        <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image" class="profile-pic">
                    <?php else: ?>
                        <div class="image-placeholder">Image here</div>
                    <?php endif; ?>
                    <label for="profile_image" class="upload-button">Upload Image</label>
                    <input type="file" id="profile_image" name="profile_image" style="display:none;">
                </div>

                <div class="information">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

                    <label for="theme">Theme:</label>
                    <div class="themes">
                        <label>
                            <input type="radio" name="theme" value="light" <?php if($theme == 'light') echo 'checked'; ?>> Light
                        </label>
                        <label>
                            <input type="radio" name="theme" value="dark" <?php if($theme == 'dark') echo 'checked'; ?>> Dark
                        </label>
                    </div>

                    <input type="submit" value="Save Profile" class="save-button">
                </div>
            </form>
            <form action="order_history.php" method="POST" class="archive-logout-form">
                <input type="submit" value="Order History" class="orderhistory-button">
            </form>
            <form action="archive_account.php" method="POST" class="archive-logout-form">
                <input type="submit" value="Delete Account" class="archive-button">
            </form>

            <form action="logout.php" method="POST" class="archive-logout-form">
                <input type="submit" value="Logout" class="logout-button">
            </form>
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