<?php

$to = "drhema1988@example.com";

$name              = $_POST['name'] ?? '';
$phone             = $_POST['phone'] ?? '';
$email             = $_POST['email'] ?? '';
$gender            = $_POST['department'] ?? '';
$child_age         = $_POST['child_age'] ?? '';
$duration_concern  = $_POST['duration_concern'] ?? '';
$doctor            = $_POST['doctor'] ?? '';
$message           = $_POST['message'] ?? 'No additional message';


$subject = "New Appointment Request from $name";

$body = "Parent/Guardian Name: $name\r\n";
$body .= "Phone Number: $phone\r\n";
$body .= "Email: $email\r\n";
$body .= "Child's Gender: $gender\r\n";
$body .= "Child's Age: $child_age\r\n";
$body .= "Duration of Concern: $duration_concern\r\n";
$body .= "Selected Service: $doctor\r\n";
$body .= "Additional Message:\r\n$message\r\n";


$attachment = $_FILES['report'] ?? null;
$has_attachment = false;

if ($attachment && $attachment['error'] === 0) {
    $file_tmp = $attachment['tmp_name'];
    $file_name = $attachment['name'];
    $file_type = $attachment['type'];
    $file_size = $attachment['size'];


    if ($file_size <= 4.5 * 1024 * 1024) {
        $content = chunk_split(base64_encode(file_get_contents($file_tmp)));
        $uid = md5(uniqid(time()));
        $file_encoded = $content;

        
        $boundary = "==Multipart_Boundary_x{$uid}x";

     
        $headers = "From: $name <$email>\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

        
        $message_body = "--$boundary\r\n";
        $message_body .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
        $message_body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $message_body .= "$body\r\n\r\n";

        $message_body .= "--$boundary\r\n";
        $message_body .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
        $message_body .= "Content-Transfer-Encoding: base64\r\n";
        $message_body .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n\r\n";
        $message_body .= "$file_encoded\r\n";
        $message_body .= "--$boundary--";

        $has_attachment = true;
    }
}

if (!$has_attachment) {
    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";
}


if ($has_attachment) {
    $mail_sent = mail($to, $subject, $message_body, $headers);
} else {
    $mail_sent = mail($to, $subject, $body, $headers);
}


if ($mail_sent) {
    header("Location: ../thankyou.html");
} else {
    echo "Error: Unable to send appointment email.";
}
exit;
?>
