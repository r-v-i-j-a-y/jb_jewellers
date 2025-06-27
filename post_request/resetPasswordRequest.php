<?php
require '../config/db.php';
require '../functions/response.php';
require '../functions/validator.php';
require '../functions/middleware.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo = db_connection();
    $data = $_POST;
    $errors = [];

    validate_required($data, 'password', $errors);
    validate_min($data, 'password', 8, $errors);
    validate_required($data, 'email', $errors);

    if (!empty($errors)) {
        send_json_error('Validation failed', $errors);
    }

    try {
        $email_id = $data['email'];
        $passwordPlain = $data['password'];
        $hashedPassword = password_hash($passwordPlain, PASSWORD_BCRYPT);

        // Update the user's password
        $stmt = $pdo->prepare("UPDATE pr_users SET password = :password WHERE email = :email");
        $stmt->execute([
            'password' => $hashedPassword,
            'email' => $email_id
        ]);

        // Invalidate the reset token
        $tokenStmt = $pdo->prepare("DELETE FROM  pr_password_reset_temp WHERE email = :email");
        $tokenStmt->execute(['email' => $email_id]);

        send_json_success("Password updated successfully!");
    } catch (PDOException $e) {
        send_json_error("Password update failed!", ['database' => [$e->getMessage()]]);
    }
}
