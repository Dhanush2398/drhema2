<?php

$name    = $_POST['name'] ?? '';
$email   = $_POST['email'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';


$to = "drhema1988@example.com";


$email_subject = "New contact from $name";


$txt = "Name: $name\r\n";
$txt .= "Email: $email\r\n";
$txt .= "Subject: $subject\r\n";
$txt .= "Message:\r\n$message";


$headers = "From: $name <$email>\r\n";
$headers .= "CC:drhema1988@example.com";


if (!empty($email)) {
    mail($to, $email_subject, $txt, $headers);
}

header("Location: thankyou.html");
exit;
?>
