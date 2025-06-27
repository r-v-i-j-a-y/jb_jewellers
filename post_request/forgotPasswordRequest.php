<?php
// $mail = require 'mailer.php';

// $mail->setFrom('vijayr.office@gmail.com', 'Vijay');
// $mail->addAddress('recipient@example.com', 'User');
// $mail->Subject = 'Test Email';
// $mail->Body = '<strong>Hello!</strong> This is a test email.';

// if ($mail->send()) {
//     echo 'Message sent successfully!';
// } else {
//     echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
// }
require_once '../config/db.php';
require_once '../functions/validator.php';
require_once '../functions/response.php';
require_once '../functions/session.php';
require_once '../functions/middleware.php';

require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';
require_once '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Get PDO connection
$pdo = db_connection();
$env = parse_ini_file(__DIR__ . '/../.env');

$baseUrl = $env['BASE_URL'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = $_POST;
    $errors = [];

    $email = $_POST['email'];
    validate_required($data, 'email', $errors);


    if (!empty($errors)) {
        send_json_error('Validation failed', $errors, 422);
    }


    // Prepare and execute SELECT query
    $stmt = $pdo->prepare("SELECT email FROM pr_users WHERE email = :email");
    $stmt->execute(['email' => $email]);

    if ($stmt->rowCount() === 1) {
        $token = bin2hex(random_bytes(16));
        $token_hash = md5($token);
        $expire = date("Y-m-d H:i:s", time() + (60 * 30)); // 30 minutes

        // Prepare and execute UPDATE query
        $updateStmt = $pdo->prepare("INSERT INTO pr_password_reset_temp (email,tokenkey,expDate) VALUES (:email,:token_hash,:expire)");
        $result = $updateStmt->execute([
            'token_hash' => $token_hash,
            'expire' => $expire,
            'email' => $email
        ]);

        if ($result) {
            $mail = new PHPMailer(true);
            try {
                // $mail->isSMTP();
                // $mail->Host = 'smtp.gmail.com';
                // $mail->SMTPAuth = true;
                // $mail->Username = 'vijayr.office@gmail.com';
                // $mail->Password = 'vijay@1112gmail'; // Use Gmail App Password in production
                // $mail->SMTPSecure = 'tls';
                // $mail->Port = 587;
                $mail->isSMTP();
                $mail->Host = 'sandbox.smtp.mailtrap.io';
                $mail->SMTPAuth = true;
                $mail->Username = '7326957f3e7a55';
                $mail->Password = '3ae3024e0dc258'; // Use Gmail App Password in production
                $mail->SMTPSecure = 'tls';
                $mail->Port = 2525;
                
                $mail->setFrom("vijayr.office@gmail.com", "Support");
                $mail->addAddress($email);
                $mail->Subject = "Password reset";
                $mail->isHTML(true);
                $mail->Priority = 1;

                // Adjust the reset link to match your domain
                $mail->Body = "Click <a href='$baseUrl/reset_password.php?token=$token'>here</a> to reset your password.";

                $mail->send();

                send_json_success("Password reset email sent");

                // $data->message = "Password reset email sent!";
                // $data->status = true;
                // echo json_encode($data);
            } catch (PDOException $e) {
                send_json_error('Database error occurred', ['database' => [$e->getMessage()]], 500);
            }
        } else {
            send_json_error("Failed to update token in DB", $errors, 200);

            // $data->message = "Failed to update token in DB.";
            // $data->status = false;
            // echo json_encode($data);
        }
    } else {
        // $data->message = "Email ID doesn't exist!";
        // $data->status = false;
        // echo json_encode($data);
        send_json_error("Email ID doesn't exist", $errors, 200);
    }
}