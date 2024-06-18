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

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$isAdmin = false;

if ($user_id) {
    // Fetch the isAdmin status if the user is logged in
    $stmt = $pdo->prepare("SELECT isAdmin FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $isAdmin = $stmt->fetchColumn();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $captcha = trim($_POST['captcha']);

    // Validate CAPTCHA
    if ($captcha !== $_SESSION['captcha_solution']) {
        echo json_encode(['success' => false, 'message' => 'Captcha failed!']);
        exit;
    }

    // Check for duplicate username
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Username is already taken']);
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();

    // Check for duplicate email
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'User already exists for this email']);
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Prepare and execute the insertion query
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Registration successful!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit; // Ensure the script stops here for POST requests
}

$num1 = rand(1, 10);
$num2 = rand(1, 10);
$captcha_question = "$num1 + $num2";
$_SESSION['captcha_solution'] = strval($num1 + $num2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tutorfy | Register</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="register.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <script src="register.js" defer></script>
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
                        <!-- Cart items will be displayed here -->
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
        <div class="signup-box">
            <h2>Sign Up</h2>
            <form id="register" action="register.php" method="POST" enctype="multipart/form-data">
                <h4>Username</h4>
                <label for="username"></label>
                <input type="text" name="username" id="username" required>
                <div id="usernameError" class="error-message"></div>

                <h4>Email</h4>
                <label for="email"></label>
                <input type="email" name="email" id="email" required>
                <div id="emailError" class="error-message"></div>

                <h4>Password</h4>
                <label for="password"></label>
                <input type="password" name="password" id="password" required oninput="validatePassword()">
                <div id="passwordChecklist">
                    <ul>
                        <li id="lowercase" class="invalid">At least one lowercase letter</li>
                        <li id="uppercase" class="invalid">At least one uppercase letter</li>
                        <li id="number" class="invalid">At least one number</li>
                        <li id="special" class="invalid">At least one special character (@$!%*?&)</li>
                        <li id="length" class="invalid">At least 8 characters long</li>
                    </ul>
                </div>
                <div id="passwordError" class="error-message"></div>

                <h4>Confirm password</h4>
                <label for="passwordConfirm"></label>
                <input type="password" name="passwordConfirm" id="passwordConfirm" required>
                <div id="passwordConfirmError" class="error-message"></div>

                <h4>CAPTCHA: Solve the following:</h4>
                <label for="captcha"></label>
                <div class="captcha">
                    <span><?php echo $captcha_question; ?> = </span>
                    <input type="text" name="captcha" id="captcha" required>
                </div>
                <div id="captchaError" class="error-message"></div>

                <br>

                <button type="submit">Register</button>
            </form>
            <div class="links">
                <a href="login.php">Already have an account? <span>Sign in here</span></a>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Account</h4>
                <div class="profile-footer">
                    <?php echo getProfileFooter() ?>
                </div>
            </div>
            <div class="footer-section">
                <h4>Contact Us</h4>
                <p>Email: support@tutorfy.com</p>
                <p>Phone: 123-456-7890</p>
            </div>
            <div class="footer-section">
                <h4>Follow Us</h4>
                <div class="social-media">
                    <a href="https://www.facebook.com/tutorfy" target="_blank">Facebook</a>
                    <a href="https://www.twitter.com/tutorfy" target="_blank">Twitter</a>
                    <a href="https://www.instagram.com/tutorfy" target="_blank">Instagram</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Tutorfy. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>