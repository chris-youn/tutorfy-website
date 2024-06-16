<?php
require '../forum/config.php';
include('../scripts/functions.php');

if (!isset($_SESSION['user_id'])) {
    $referrer = urlencode($_SERVER['REQUEST_URI']);
    header("Location: ../login/login.php?referrer=$referrer");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the user is an admin or a tutor
$stmt = $pdo->prepare("SELECT isAdmin, isTutor FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || (!$user['isAdmin'] && !$user['isTutor'])) {
    header("Location: ../homepage/homepage.php");
    exit();
}

$isAdmin = $user['isAdmin'];
$isTutor = $user['isTutor'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['article-title'];
    $content = $_POST['article-content'];
    $image_path = null;


    if (isset($_FILES['article-image']) && $_FILES['article-image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['article-image'];
        $image_name = time() . '_' . basename($image['name']);
        $image_path = '../forum/uploads/' . $image_name;

        if (!move_uploaded_file($image['tmp_name'], $image_path)) {
            echo "Image upload failed";
            exit();
        }
    }

    $stmt = $pdo->prepare("SELECT MAX(id) AS max_id FROM articles");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $newId = $result['max_id'] + 1;


    $stmt = $pdo->prepare("INSERT INTO articles (id, user_id, title, content, image_path) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$newId, $user_id, $title, $content, $image_path])) {
        echo "Article created!";
        header ("Location: tutorModule.php");
    } else {
        echo "Error Creating Article";
    }
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Tutorfy | Create Article</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="forum_styles.css">
    <link rel="stylesheet" href="../global.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <script src="forum_script.js" defer></script>
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
            <a href="../forum/forum.php" class="nav-link active">Forums</a>
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

    <h1>Create a New Article</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="article-title">Title:</label>
        <input type="text" id="article-title" name="article-title" required>
        <br>
        <label for="article-content">Content:</label>
        <textarea id="article-content" name="article-content" required></textarea>
        <br>
        <label for="article-image">Image:</label>
        <input type="file" id="article-image" name="article-image">
        <br>
        <input type="submit" value="Create Article">
    </form>

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
