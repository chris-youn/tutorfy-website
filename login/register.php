<?php
include('../login/config.php'); 

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
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $captcha = trim($_POST['captcha']);

    // Validate CAPTCHA
    if ($captcha !== '10') { // Simple CAPTCHA: 3 + 7 = 10
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
                            <div id='tutorSessionCart'></div>
                            
                            <button id="tutorSessionClear">X</button>
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

                <h4>Solve: 3 + 7 = ?</h4>
                <label for="captcha"></label>
                <input type="text" name="captcha" id="captcha" required>

                <br>

                <button type="submit">Register</button>
            </form>
            <div class="links">
                <a href="login.php">Already have an account? <span>Sign in here</span></a>
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
                <a href="../login/login.php" class="`sec-nav">Login</a>
                <a href="../cart/cart.php" class="sec-nav">Cart</a>
            </div>
        </div>
        <h4>&copy Tutorfy | Web Programming Studio 2023</h4>
    </footer>
</body>
</html>
