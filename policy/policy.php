<?php
require '../forum/config.php';
include('../scripts/functions.php');

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$isAdmin = false;

if ($user_id) {
    // Fetch the isAdmin status if the user is logged in
    $stmt = $pdo->prepare("SELECT isAdmin FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $isAdmin = $stmt->fetchColumn();
}

$theme = getUserTheme(); // Fetch the user's theme
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Tutorfy | Cookie and Privacy Policy</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if ($theme == 'dark'): ?>
        <link rel="stylesheet" type="text/css" href="../global-dark.css">
    <?php else: ?>
        <link rel="stylesheet" type="text/css" href="../global.css">
    <?php endif; ?>
    <link rel="stylesheet" type="text/css" href="policy.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
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
            <?php if ($isAdmin): ?>
                <a href="../adminModule/adminModule.php" class="nav-link">Administration Module</a>
            <?php endif; ?>
        </nav>
        <div class="icons">
            <span class="icon" id="cartIcon">🛒<span id="cartBadge" class="badge">0</span></span>
            <div class="shopping-cart" style="display:none;">
                <div class="shopping-cart-header">
                    <div class="shopping-cart-total">
                        <span id="totalText" class="light-text">Total: $0</span>
                    </div>
                </div>
                <ul class="shopping-cart-items" id="items">
                    <li id="tutorSessionListItem">
                        <div id='tutorSessionCart'></div>
                        <button id="tutorSessionRemove">-</button>
                        <button id="tutorSessionAdd">+</button>
                        <button id="tutorSessionClear">X</button>
                    </li>
                    <li id="monthlyPlanListItem">
                        <div id="monthlyPlanCart"></div>
                        <button id="monthlyPlanClear">X</button>
                    </li>
                    <li id="yearlyPlanListItem">
                        <div id="yearlyPlanCart"></div>
                        <button id="yearlyPlanClear">X</button>
                    </li>
                </ul>
                <form action="../cart/cart.php" method="post">
                    <div class="checkout">
                        <input id="cartCheckout" type="submit" value="Checkout"></input>
                    </div>
                </form>
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
        <section class="cookie-policy">
            <h1>Cookie and Privacy Policy</h1>
            <p>We use cookies at Tutorfy to improve your user experience. Some cookies are necessary to make some services work, and others 
                are used to collect information of the use of the website (statistics), so we can make the site more convenient and useful for 
                you to use. Some cookies are temporary and will disappear when you close your browser, whereas others are persistent and will remain 
                on your computer for some time.
            </p>

            <ul><strong>Strictly necessary cookies are used to:</strong>
                <li>Remember what is in your shopping cart</li>
                <li>Remember how far you are into your order</li>
            </ul>

            <ul><strong>Functional cookies are used to:</strong>
                <li>Remember your login details</li>
                <li>Make sure you are secure when you are logged-in</li>
                <li>Ensure consistency throughout the website</li>
            </ul>

            <p><strong>Privacy and Data Protection</strong></p>
            <p>We are committed to protecting your privacy and ensuring that your personal information is handled in a safe and responsible manner. This policy outlines how we use cookies and other tracking technologies to improve your user experience and how we handle your personal data.</p>

            <p><strong>Information We Collect</strong></p>
            <p>When you visit our website, we may collect the following types of information:</p>
            <ul>
                <li>Personal details you provide (e.g., name, email address)</li>
                <li>Login details and preferences</li>
                <li>Information about your use of our website (e.g., pages visited, time spent on the site)</li>
            </ul>

            <p><strong>How We Use Your Information</strong></p>
            <p>We use your information to:</p>
            <ul>
                <li>Provide and personalize our services</li>
                <li>Process your orders and manage your account</li>
                <li>Improve our website and services</li>
                <li>Communicate with you regarding your orders, inquiries, and updates</li>
            </ul>

            <p><strong>Sharing Your Information</strong></p>
            <p>We do not sell, trade, or otherwise transfer to outside parties your personally identifiable information. This does not include trusted third parties who assist us in operating our website, conducting our business, or servicing you, so long as those parties agree to keep this information confidential.</p>

            <p><strong>Changes to Our Cookie and Privacy Policy</strong></p>
            <p>We may update this policy from time to time to reflect changes in our practices or for other operational, legal, or regulatory reasons. We will notify you of any significant changes by posting the new policy on our website.</p>

            <p><strong>Contact Us</strong></p>
            <p>If you have any questions about this policy or our practices, please contact us at:</p>
            <p>
                Tutorfy<br>
                Email: support@tutorfy.com<br>
            </p>
        </section>
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
