<?php
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tutorfy | Profile</title>
    <link rel="stylesheet" type="text/css" href="profile.css">
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
            <a href="../forum/forum.php" class="nav-link">Forums</a>
            <a href="../adminModule/adminModule.php" class="nav-link">Administration Module</a>
        </nav>
        <div class="icons">
            <div class="container">
                <span class="icon" id="cartIcon">🛒</span>
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
    
    <main>
        <h2>Profile</h2>
        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br>

            <label for="theme">Theme:</label>
            <select id="theme" name="theme">
                <option value="light" <?php if($theme == 'light') echo 'selected'; ?>>Light</option>
                <option value="dark" <?php if($theme == 'dark') echo 'selected'; ?>>Dark</option>
            </select><br>

            <label for="profile_image">Profile Image:</label>
            <input type="file" id="profile_image" name="profile_image"><br>
            <?php if ($profile_image): ?>
                <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image" width="100"><br>
            <?php endif; ?>

            <input type="submit" value="Update Profile">
        </form>

        <form action="archive_account.php" method="POST">
            <input type="submit" value="Archive Account">
        </form>

        <form action="logout.php" method="POST">
            <input type="submit" value="Logout">
        </form>
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
                <a href="../login/login.php" class="`sec-nav">Login</a>
                <a href="../cart/cart.php" class="sec-nav">Cart</a>
            </div>
        </div>
        <h4>&copy Tutorfy | Web Programming Studio 2023</h4>
    </footer>
</body>
</html>
