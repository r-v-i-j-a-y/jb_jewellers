<?php

require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // $mail->SMTPDebug = 2; // Enable verbose debug output

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'vijayr.office@gmail.com';
    $mail->Password = 'vijay@mail'; 
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->isHTML(true);

    return $mail;

} catch (Exception $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}
