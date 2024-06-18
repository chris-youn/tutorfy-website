<?php 
session_start();

require '../forum/config.php';

function applyCoupon() {

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try{
    $coupon = $_POST['coupon_code'];
       // Prepare and bind
       $stmt = $pdo->prepare("SELECT DiscountPercentage FROM CouponCodes WHERE CouponCode = :couponCode AND CURDATE() BETWEEN ActivationDate AND ExpirationDate");
       $stmt->bindParam(':couponCode', $coupon);
        
       // Execute the query
       $stmt->execute();
   
       // Check if the coupon code is found and active
       if ($stmt->rowCount() > 0) {
           $row = $stmt->fetch(PDO::FETCH_ASSOC);
           var_dump($row);
           print_r($row['DiscountPercentage']);
           $_SESSION['discountPercentage'] = $row['DiscountPercentage'];
           echo "found";
           header("Location: cart.php");

            exit();
       } else {
        $_SESSION['discountPercentage'] = null;
        echo "not found";
        header("Location: cart.php");
        exit();
       }
   } catch(PDOException $e) {
       echo "Error: " . $e->getMessage();
   }
}
$pdo = null;



?>

