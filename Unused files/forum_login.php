<?php
include('../login/config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

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
        $last_failed_login_time = new DateTime($last_failed_login);
        $interval = $last_failed_login_time->diff($current_time);

        if ($failed_attempts >= 3 && $interval->i < 60) {
            echo "Account locked. Try again later in an Hour.";
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

                // Redirect to profile page
                header("Location: ../forum/forum.php");
                exit;
            } else {
                // Increment failed attempts
                $failed_attempts++;
                $update_attempts_stmt = $conn->prepare("UPDATE users SET failed_attempts = ?, last_failed_login = NOW() WHERE id = ?");
                $update_attempts_stmt->bind_param("ii", $failed_attempts, $id);
                $update_attempts_stmt->execute();
                $update_attempts_stmt->close();

                echo "Invalid credentials.";
            }
        }
    } else {
        echo "No user found.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Tutorfy | Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../login/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../global.css">
    <script src="forum_login.js" defer></script>
    <script src="../global.js" defer></script>

</head>
<body>
    <div class="main">
        <div class="topnav">
            <div class="logo">
                <img src="../assets/img/tutorfy-logo.png" alt="Tutorfy Logo">
                <span>Tutorfy</span>
            </div>  
            <div class="nav-links">
                <a href="../homepage/homepage.php" class="nav-link">Home</a>
                <a href="../article/article.php" class="nav-link">Articles</a>
                <a href="../store/store.php" class="nav-link">Store</a>
                <a href="../forum/forum.php" class="nav-link">Forums</a>
            </div>
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
                <a href="../login/login.php" class="icon">👤</a>
            </div>
        </div>

        <div class="content">
            <div class="login-box">
                <h2>Please sign in to use forums.</h2>
                <form id="login" action="forum_login.php" method="POST">
                    <h4>Username</h4>
                    <label for="username"></label>
                    <input type="text" name="username" id="username" required>
                    <div id="usernameError" class="error-message"></div>

                    <h4>Password</h4>
                    <label for="password"></label>
                    <input type="password" name="password" id="password" required>

                    <div id="userpassError" class="error-message"></div>
                    <button type="submit">Login</button>
                </form>
                <div class="links">
                    <a href="reset.php">Forgot password? <span>Reset here</span></a>
                    <a href="register.php">Don't have an account? <span>Register here!</span></a>
                </div>
            </div>
        </div>

        <div class="footer">
            Footer Information
        </div>
    </div>
</body>
</html>
