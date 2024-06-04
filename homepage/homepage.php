<?php
session_start();
if (isset($_COOKIE['userConsent']) && $_COOKIE['userConsent'] === 'true') {
    echo '<script src="additionalScript.js" defer></script>';
}

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
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Tutorfy | Get on top of your schoolwork</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="homepage.css">
    <link rel="stylesheet" type="text/css" href="../global.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet"> 
    <script src="homepage.js" defer></script>
    <script src="../global.js" async defer></script>
</head>

<body>
    <header class="topnav">
        <div class="logo">
            <img src="logo.png" alt="Logo">
            <span>Tutorfy</span>
        </div>
        <nav class="nav-links">
            <a href="../homepage/homepage.php" class="nav-link active">Home</a>
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
        <section class="banner">
            <h1>Having trouble keeping on top of schoolwork?</h1>
            <p><strong>You've come to the right place!</strong></p>
        </section>

        <section class="site-intro">
            <h1>Welcome to Tutorfy!</h1>
            <p>Tutorfy is an online platform dedicated to enhancing students' learning experiences. 
                It offers a wealth of accessible articles crafted by experienced tutors, covering a wide range of educational topics. 
                In addition to these informative resources, Tutorfy serves as a vibrant hub where students can engage in discussions about subjects they are passionate about. 
                Whether you're looking to improve your study habits, grasp complex concepts, or simply connect with like-minded peers, 
                Tutorfy provides the tools and community support to help you succeed academically.</p>
        </section>

        <section class="articles-section">
            <h1>Don't know where to start?</h1>
            <p>Here are some articles to get you up and running!</p>
            <div class="articles">
                <a href="../article/article.php#article1" class="article">
                    <h3>How to Write an Essay: A Comprehensive Guide</h3>
                    <p>Writing an essay is a fundamental skill for students and professionals alike, offering a 
                    structured way to convey ideas and arguments. The process begins with understanding
                    <br>
                    ...(CONTINUED)</p>
                </a>
                <a href="../article/article.php#article3" class="article">
                    <h3>How Covalent Bonds Work: A Simple Guide</h3>
                    <p>Have you ever wondered what keeps the atoms in a molecule together? The answer is something called a covalent bond. 
                    Covalent bonds are a fundamental concept in chemistry
                    <br>
                    ...(CONTINUED)
                    </p>
                </a>
                <a href="../article/article.php#article4" class="article">
                    <h3>Exploring the World's Biomes</h3>
                    <p>Welcome to the amazing world of biomes! Biomes are large regions of the Earth that share similar climate, plants, and animals. 
                    Each biome is like a unique neighborhood
                    <br>
                    ...(CONTINUED)
                    </p>
                </a>
            </div>
        </section>

        <section class="reviews">
            <h1>Hear what our students have to say!</h1>
            <div class="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <p>"Tutorfy has been very instrumental in my academic growth! - John Doe"</p>
                    </div>
                    <div class="carousel-item">
                        <p>"The tutoring sessions are amazing and have really helped me understand complex topics. - Jane Smith"</p>
                    </div>
                    <div class="carousel-item">
                        <p>"I love the flexibility and the quality of the tutors available on Tutorfy. - Mike Johnson"</p>
                    </div>
                </div>
                <button class="prev">&#10094;</button>
                <button class="next">&#10095;</button>
            </div>
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
                <a href="../login/login.php" class="`sec-nav">Login</a>
                <a href="../cart/cart.php" class="sec-nav">Cart</a>
            </div>
        </div>
        <h4>&copy Tutorfy | Web Programming Studio 2023</h4>
    </footer>
</body>
</html>
