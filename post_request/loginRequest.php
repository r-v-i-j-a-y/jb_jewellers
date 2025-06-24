<?php
require '../functions/validator.php';
require '../config/db.php';
require '../functions/response.php';
require '../functions/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    session_start();

    $data = $_POST;
    $errors = [];

    validate_required($data, 'mobile', $errors);
    validate_required($data, 'password', $errors);

    if (!empty($errors)) {
        send_json_error('Validation failed', $errors, 422);
    }

    $mobile = $data['mobile'];
    $password = $data['password'];

    $pdo = db_connection();

    $stmt = $pdo->prepare("SELECT * FROM users WHERE mobile = :mobile LIMIT 1");
    $stmt->execute(['mobile' => $mobile]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            unset($user['password']);
            $_SESSION['auth'] = $user;
            send_json_success('Login successful');
        } else {
            send_json_error('Password failed', 'Password is incorrect', 200);
        }
    } else {
        send_json_error('User failed', 'Mobile number not found', 200);
    }
}