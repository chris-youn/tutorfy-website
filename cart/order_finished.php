<?php

include('../adminModule/configuration.php');
include('../scripts/functions.php');
require '../forum/config.php';

// Make sure the user has in fact made an order
if(isset($_SESSION['orderValidated'])){
    $orderValid = $_SESSION['orderValidated'];
} else {
    $orderValid = false;
}

// Fetch user ID and admin status
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$isAdmin = false;

if ($user_id) {
    // Fetch the isAdmin status if the user is logged in
    $stmt = $pdo->prepare("SELECT isAdmin FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $isAdmin = $stmt->fetchColumn();
}

$theme = getUserTheme(); // Fetch the user's theme

// Fetch or create order ID
if (isset($_SESSION['orderID'])) {
    $orderID = $_SESSION['orderID'];
} else {
    $_SESSION['orderID'] = time() . mt_rand();
    $orderID = $_SESSION['orderID'];
}

// Fetch user's email and full name from cookies
$user_email = isset($_COOKIE['user_email']) ? $_COOKIE['user_email'] : null;
$full_name = isset($_COOKIE['full_name']) ? $_COOKIE['full_name'] : null;

// Fetch other information for database:
$itemsOrdered = isset($_SESSION['cart_details']) ? $_SESSION['cart_details'] : null;
if ($itemsOrdered != null){
    $purchaseDate = time();
}

// Fetch cart details from session
$cart_details = isset($_SESSION['cart_details']) ? json_decode($_SESSION['cart_details'], true) : null;
if ($cart_details != null){
    $totalCost = $cart_details['discountedTotal'];
}

if ($user_email && $cart_details && $orderValid) {
    // Construct the email content
    $content = "Dear " . htmlspecialchars($full_name) . ",\n\nYour order has been confirmed!\n\nOrder ID: $orderID\n\n";
    $content .= "Here are the details of your purchased items:\n";

    if (!empty($cart_details['tutorSessionShort'])) {
        $content .= "1hr Tutor Session(s): " . $cart_details['tutorSessionShort'] . " x $40\n";
    }
    if (!empty($cart_details['tutorSessionLong'])) {
        $content .= "2hr Tutor Session(s): " . $cart_details['tutorSessionLong'] . " x $70\n";
    }
    if (!empty($cart_details['tutorSessionShortBulk'])) {
        $content .= "5 x 1hr Tutor Session(s): " . $cart_details['tutorSessionShortBulk'] . " x $170\n";
    }
    if (!empty($cart_details['tutorSessionLongBulk'])) {
        $content .= "5 x 2hr Tutor Session(s): " . $cart_details['tutorSessionLongBulk'] . " x $300\n";
    }

    $content .= "\nTotal: $" . $cart_details['total'];
    if (!empty($cart_details['discountedTotal']) && $cart_details['discountedTotal'] < $cart_details['total']) {
        $content .= "\nDiscounted Total: $" . $cart_details['discountedTotal'];
    }

    $content .= "\n\nYou will receive an email containing information on booking your purchased sessions.\n\n";
    $content .= "Thank you for shopping with us!\n\nBest regards,\nTutorfy Team";

    // Email sending logic
    $to = $user_email;
    $subject = "Order Confirmation - Order ID: " . $orderID;
    $headers = "From: no-reply@tutorfy.com";

    if (mail($to, $subject, $content, $headers)) {
        echo "Order confirmation email sent successfully to $to";
    } else {
        echo "Failed to send order confirmation email.";
    }
} else {
    echo "User email not found, cart details missing, or order not validated. Cannot send order confirmation email.";
}

// check if orderId is in database to avoid duplicate entries:
function checkForOrderID($pdo, $orderID) {
    $sql = "SELECT COUNT(*) FROM orders WHERE orderid = :orderid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':orderid', $orderID, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        return false; // Order ID exists
    } else {
        return true; // Order ID does not exist
    }
}

// insert order into database if valid
if ($orderValid && checkForOrderID($pdo, $orderID)) {
    $sql = "INSERT INTO orders (orderid, userid, items_ordered, total_cost, purchase_date) 
    VALUES (:orderid, :userid, :items_ordered, :total_cost, NOW())";
    $stmt = $pdo->prepare($sql);
     
    // remove order total and discounted total from items ordered
    $itemsArray = json_decode($itemsOrdered, true);
    unset($itemsArray['total']);
    unset($itemsArray['discountedTotal']);
    $itemsOrdered = json_encode($itemsArray);
     
    // Bind parameters
    $stmt->bindParam(':orderid', $orderID, PDO::PARAM_STR);
    $stmt->bindParam(':userid', $user_email, PDO::PARAM_STR);
    $stmt->bindParam(':items_ordered', $itemsOrdered, PDO::PARAM_STR);
    $stmt->bindParam(':total_cost', $totalCost, PDO::PARAM_STR);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Order inserted successfully!";
    } else {
        echo "Failed to insert order.";
    }
}
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Tutorfy | Order Complete</title>
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
        <link rel="stylesheet" href="order_finished.css">
        <script src="../global.js" defer></script>
        <script src="order_finished.js" defer></script>

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
    
    <?php  if($orderValid == true):?>

    <section class="orderconfirmation">
        <div class="orderconfirmationcontainer">
        <h1>Your order has been confirmed!</h1>
        <p id="orderID">Order ID: <?php echo $orderID ?> </p>
        <p>You will receive an email containing information on booking your purchased sessions </p>
        <div id="orderSummary">
            <h2 style="margin:8px;">Order Summary:</h2>
            <div class="separator" style="margin-bottom:10px;"></div>
            <div id="orderSummaryItems"></div>
        </div>
        <script>
            
        </script>
        </div>
    </section>
    <?php endif; ?>
    
    
    <?php if($orderValid == false):?>
        <section class="orderconfirmation">
        <div class="orderconfirmationcontainer">
        <h1>You have not made an order.</h1>
        
        <p>If you are looking for a previous order you have made, please check your email or your account's order history. </p>
        
        </div>
    </section>
    <?php endif;?>
        
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