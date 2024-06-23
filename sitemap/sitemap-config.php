<?php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$server_name = $_SERVER['SERVER_NAME'];
$base_url = $protocol . $server_name . dirname($_SERVER['PHP_SELF']) . '/';

$folders_to_include = array(
    "adminModule" => array("adminModule.php"),
    "article" => array("article.php", "tutormodule.php"),
    "cart" => array("cart.php", "order_failed.php", "order_finished.php" ),
    "contact" => array("contact.php"),
    "forum" => array("forum.php" ,"external_forum.php"),
    "homepage" => array("homepage.php"),
    "login" => array("login.php", "order_hisotry.php", "profile.php", "register.php", "reset.php"),
    "policy" => array("policy.php"),
    "quiz" => array("quiz.php"),
    "store" => array("store.php")
);

$config = array(
    "SITE_URL" => $base_url,
    "ALLOW_EXTERNAL_LINKS" => false,
    "ALLOW_ELEMENT_LINKS" => false,
    "CRAWL_ANCHORS_WITH_ID" => "",
    "KEYWORDS_TO_SKIP" => array(),
    "SAVE_LOC" => "sitemap.xml",
    "PRIORITY" => 1,
    "CHANGE_FREQUENCY" => "daily",
    "LAST_UPDATED" => date('Y-m-d'),
    "FOLDERS_TO_INCLUDE" => $folders_to_include
);

return $config;
?>
