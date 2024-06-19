<?php
use FFI\Exception;
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

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Tutorfy | Checkout</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if ($theme == 'dark'): ?>
        <link rel="stylesheet" type="text/css" href="../global-dark.css">
    <?php else: ?>
        <link rel="stylesheet" type="text/css" href="../global.css">
    <?php endif; ?>
    <link rel="stylesheet" type="text/css" href="cart.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <script src="cart.js" defer></script>
    <script src="../global.js" defer></script>
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
                <div id="shopping-cart" class="shopping-cart" style="display:none;">
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
        <div class="checkout-container">
            <form id="payment" method="POST" class="payment-form" action="order_finished.php">
                <h2>Payment Details</h2>
                <div>
                    <h4>Full Name</h4>
                    <div>
                        <p>
                            <label for="fullName"></label>
                            <input type="text" name="fullName" id="fullName" placeholder="John Doe" maxlength="35" size="25" required>
                        </p>
                    </div>
                </div>

                <div>
                    <h4>Email</h4>
                    <div>
                        <p>
                            <label for="email"></label>
                            <input type="email" name="email" id="email" placeholder="johndoe@gmail.com" maxlength="35" size="25" required>
                        </p>
                    </div>
                </div>

                <div>
                    <h4>Card Number</h4>
                    <p>
                        <label for="cardNo"></label>
                        <input type="text" name="cardNo" id="cardNo" placeholder="1234 1234 1234 1234" maxlength="19" size="30" required>
                    </p>
                </div>

                <div id="expiry-cvc">
                    <div>
                        <h4>Expiration</h4>
                        <div>
                            <p>
                                <label for="cardDate"></label>
                                <input type="text" name="cardDate" id="cardDate" placeholder="MM/YY" maxlength="5" size="10" required>
                            </p>
                        </div>
                    </div>

                    <div id="cvc">
                        <h4>CVV/CVC</h4>
                        <div>
                            <p>
                                <label for="cvc"></label>
                                <input type="text" name="cvc" id="cvcInput" placeholder="123" maxlength="3" size="10" required>
                            </p>
                        </div>
                    </div>
                    
                </div>
                <button type="submit" target="order_complete.php">Pay now</button>
            </form>

            <div id="cart-summary" class="cart-summary">
                <h2>Your Cart</h2>
                <ul class="cart-items" id="cart-summary-items">
                    
                    <!-- Cart items will be dynamically populated here -->
                </ul>
                <script>
                    const d = new Date();
                    d.setTime(d.getTime() + (7*24*60*60*1000));
                    let expires = "expires="+ d.toUTCString();
                    document.cookie = "total" + "=" + sessionStorage.getItem("total") + ";" + expires + ";path=/";
                </script>
                
                <div class="cart-total">
                    <span id="cart-total-text">Total: $0</span><br>
                    <span id="cart-discount-text">
                    <?php 
                    $total = $_COOKIE['total'];
                             if (isset($_SESSION['discountPercentage'])){
                            
                            
                                $discount = $_SESSION['discountPercentage'];
                            
                            
                             
                            
                            try {
                               
                                $newTotal = $total-($total*($discount/100));
                                
                            }catch(Exception $e) {
                                echo "No items in cart";
                            }
                            
                            echo "<script>sessionStorage.setItem('discountedTotal', ".$newTotal.")</script>";
                        } else {
                            "<script>sessionStorage.setItem('discountedTotal',".$total.")</script>";
                        }
                            if (isset($_SESSION['discountPercentage'])) {
                                $discount = $_SESSION['discountPercentage'];
                                echo "Discount Applied! ".$discount."% off!";
                                echo "<br>New Total: ".($total-($total*($discount/100)));                                 
                            }
                            ?>
                            
                            
                        </span>
                </div>
               
                <div id="couponcode">
                        <h4>Coupon Code</h4>
                        <form id="coupon_apply_form" action="coupon_apply.php" method="POST" enctype="multipart/form-data">
                        <p>
                            <label for="coupon_code"></label>
                            <input type="text" name="coupon_code" id="coupon_code" placeholder="ABCD1234">
                            <input type="submit" id="couponSubmit" value="Apply"></input>
                            
            </form>
                        </p>
                    </div>
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
