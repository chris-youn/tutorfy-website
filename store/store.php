<?php
include('../adminModule/configuration.php');
include('../scripts/functions.php');
require '../forum/config.php';

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

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tutorfy | Store</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <?php if ($theme == 'dark'): ?>
        <link rel="stylesheet" type="text/css" href="../global-dark.css">
    <?php else: ?>
        <link rel="stylesheet" type="text/css" href="../global.css">
    <?php endif; ?>
    <link rel="stylesheet" href="store.css">
    <script src="store.js" defer></script>
</head>

<body>
    <header class="topnav">
        <a href="../homepage/homepage.php">
            <div class="logo">
            <img src="../assets/img/tutorfy-logo.png" alt="Tutorfy Logo">
            <span>Tutorfy</span>
            </div>
        </a>
        <div class="nav-links">
            <a href="../homepage/homepage.php" class="nav-link">Home</a>
            <a href="../article/article.php" class="nav-link">Articles</a>
            <a href="../store/store.php" class="nav-link active">Store</a>
            <a href="../forum/forum.php" class="nav-link">Forums</a>
            <?php if ($isAdmin): ?>
                <a href="../adminModule/adminModule.php" class="nav-link">Administration Module</a>
            <?php endif; ?>
        </div>
        <div class="icons">
            <div class="container">
                <span class="icon" id="cartIcon">🛒<span id="cartBadge" class="badge">0</span></span>
                <div id='shopping-cart' class="shopping-cart" style="display:none;">
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
                            <input id="cartCheckout" type="submit" value="Checkout">
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
        <div class="header">
            <h1 class="heading">Invest in your education with Tutorfy</h1>
            <p class="headingsubtext">Book tutor sessions now, supplement your studies, and join our global community of enthusiastic learners</p>
        </div>

        <div class="content">
            <form method="post">
                <div class="tutorBooking">
                    <div class="planItem">
                        <h1 class="planHeading">Short session</h1>
                        <p class="planPrice">$40 for 1 hour</p>
                        <p class="planShortDescription">Best for students who need a bit of support or in between</p>
                        <input type="button" id="tutorButtonShort" class="selectPlan" value="Add to cart" name="tutorButton">
                    </div>
                    <div class="planItem">
                        <h1 class="planHeading">Long session</h1>
                        <p class="planPrice">$70 for 2 hours</p>
                        <p class="planShortDescription">Save by selecting a longer session.</p>
                        <input type="button" id="tutorButtonLong" class="selectPlan" value="Add to cart" name="tutorButton">
                    </div>
                </div>
                <h2>Bulk session bookings</h2>
                <div class="tutorBookingBulk">
                    <div class="planItem">
                        <h1 class="planHeading">Short sessions</h1>
                        <p class="planPrice">$170 for 5x 1 hour sessions</p>
                        <p class="planShortDescription">Good for those who will commit to many sessions and need extra support.</p>
                        <input type="button" id="tutorButtonShortBulk" class="selectPlan" value="Add to cart" name="tutorButton">
                    </div>
                    <div class="planItem">
                        <h1 class="planHeading">Long sessions</h1>
                        <p class="planPrice">$300 for 5x 2 hour sessions</p>
                        <p class="planShortDescription">Good for students who will need more time per session.</p>
                        <input type="button" id="tutorButtonLongBulk" class="selectPlan" value="Add to cart" name="tutorButton">
                    </div>
                </div>
            </form>
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
