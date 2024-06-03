<?php
if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $to = "Tutorfycontact@gmail.com";
    $name = $_POST["name"];
    $email = $_POST["emailInput"];
    $subject = $_POST["subject"];
    $content = $_POST["content"];
    echo "Name: " . $name . "<br>";
    echo "Email: ". $email. "<br>";
    echo "Subject: " . $subject. "<br>";
    echo "Content: " . $content . "<br>";
    mail($to,$subject,$content,$email);
    header("Location: contact.php");
}