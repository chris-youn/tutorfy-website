<?php
include('../login/config.php');
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    // Check if username and email match
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Update the password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ? AND email = ?");
        $update_stmt->bind_param("sss", $hashed_password, $username, $email);
        $update_stmt->execute();

        echo "Password has been updated.";
    } else {
        echo "Invalid username or email.";
    }
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Tutorfy | Reset Password</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" type="text/css" href="../global.css">
    <script src="reset.js" defer></script>
</head>
<body>
    <header class="topnav">
        <div class="logo">
            <img src="../assets/img/tutorfy-logo.png" alt="Tutorfy Logo">
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
        <form id="resetForm" method="POST" action="reset.php">
            <h2>Reset Your Password</h2>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            
            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" required>
            
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            
            <button type="submit">Submit</button>
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
