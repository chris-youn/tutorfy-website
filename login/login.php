<?php
include('../login/config.php');
include('../forum/config.php');
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

function getProfileFooter() {
    if (isUserLoggedIn()) {
        return '
                <a href="../login/profile.php">Profile</a>
                <a href="../cart/cart.php">Cart</a>
                ';
    } else {
        return '
                <a href="../login/login.php">Sign In</a>
                <a href="../login/register.php">Sign Up</a>
                <a href="../cart/cart.php">Cart</a>
                ';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $referrer = isset($_POST['referrer']) ? trim($_POST['referrer']) : 'profile.php';

    // Prepare the SQL statement to fetch user data
    $stmt = $conn->prepare("SELECT id, password, failed_attempts, last_failed_login, archived FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password, $failed_attempts, $last_failed_login, $archived);
    $stmt->fetch();

    // Check if user exists
    if ($stmt->num_rows == 1) {
        // Check if the account is archived
        if ($archived) {
            echo json_encode(['success' => false, 'message' => 'Account is archived.']);
            exit;
        }

        // Check if the account is locked due to too many failed attempts
        $current_time = new DateTime();
        if ($last_failed_login) {
            $last_failed_login_time = new DateTime($last_failed_login);
            $interval = $last_failed_login_time->diff($current_time);
            $minutes_since_last_failed_login = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
        } else {
            $minutes_since_last_failed_login = 61; // Set to more than 60 to allow login
        }

        if ($failed_attempts >= 3 && $minutes_since_last_failed_login < 60) {
            echo json_encode(['success' => false, 'message' => 'Account locked. Try again later.']);
        } else {
            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Reset failed attempts on successful login
                $reset_attempts_stmt = $conn->prepare("UPDATE users SET failed_attempts = 0, last_failed_login = NULL WHERE id = ?");
                $reset_attempts_stmt->bind_param("i", $id);
                $reset_attempts_stmt->execute();
                $reset_attempts_stmt->close();

                // Set session variables
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;

                // Redirect to referrer page
                echo json_encode(['success' => true, 'message' => 'Login successful.', 'referrer' => $referrer]);
                exit;
            } else {
                // Increment failed attempts
                $failed_attempts++;
                $update_attempts_stmt = $conn->prepare("UPDATE users SET failed_attempts = ?, last_failed_login = NOW() WHERE id = ?");
                $update_attempts_stmt->bind_param("ii", $failed_attempts, $id);
                $update_attempts_stmt->execute();
                $update_attempts_stmt->close();

                echo json_encode(['success' => false, 'message' => 'Invalid credentials.']);
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No user found.']);
    }

    $stmt->close();
    $conn->close();
    exit;
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$isAdmin = false;

if ($user_id) {
    // Fetch the isAdmin status if the user is logged in
    $stmt = $pdo->prepare("SELECT isAdmin FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $isAdmin = $stmt->fetchColumn();
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Tutorfy | Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <meta name="google-signin-client_id" content="1079756579805-sm6p2mgi4kp113f46609dejpaf58ng5i.apps.googleusercontent.com">
    <link rel="stylesheet" type="text/css" href="../global.css">
    <script src="login.js" defer></script>
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
            <a href="../forum/forum.php" class="nav-link">Forums</a>
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

    <main class="content">
        <div class="login-box">
            <h2>Sign In</h2>
            <form id="login" action="login.php" method="POST">
                <h4>Username</h4>
                <label for="username"></label>
                <input type="text" name="username" id="username" required>
                <div id="usernameError" class="error-message"></div>

                <h4>Password</h4>
                <label for="password"></label>
                <input type="password" name="password" id="password" required>
                <div id="userpassError" class="error-message"></div>

                <input type="hidden" name="referrer" id="referrer" value="<?php echo isset($_GET['referrer']) ? $_GET['referrer'] : 'profile.php'; ?>">

                <button type="submit">Login</button>
                <div class="g-signin2" data-onsuccess="onSignIn"></div>

            </form>
            <div class="links">
                <a href="reset.php">Forgot password? <span>Reset here</span></a>
                <a href="register.php">Don't have an account? <span>Register here!</span></a>
            </div>
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
