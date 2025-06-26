<?php
require '../functions/validator.php';
require '../config/db.php';
require '../functions/response.php';
require '../functions/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo = db_connection();
    $data = $_POST;
    $data['role_id'] = 2;
    $errors = [];

    validate_required($data, 'user_name', $errors);

    validate_required($data, 'email', $errors);
    validate_email($data, 'email', $errors);
    validate_unique($data, 'email', 'pr_users', 'email', $pdo, $errors);

    validate_required($data, 'mobile', $errors);
    validate_numeric($data, 'mobile', $errors);
    validate_unique($data, 'mobile', 'pr_users', 'mobile', $pdo, $errors);

    validate_required($data, 'password', $errors);
    validate_min($data, 'password', 8, $errors);
    validate_confirmed($data, 'password_confirmation', 'password', $errors);

    if (!empty($errors)) {
        send_json_error('Validation failed', $errors, 200);
    }

    try {
        unset($data['password_confirmation']);

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("
            INSERT INTO pr_users (user_name, email, mobile, password, role_id, status, created_at)
            VALUES (:user_name, :email, :mobile, :password, :role_id, 'active', NOW())
        ");
        $stmt->execute([
            'user_name' => $data['user_name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'password' => $data['password'],
            'role_id' => $data['role_id']
        ]);

        $userId = $pdo->lastInsertId();
        $user = [
            'id' => $userId,
            'user_name' => $data['user_name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'role_id' => $data['role_id'],
            'status' => 'active'
        ];

        $pdo->prepare("INSERT INTO pr_user_details (user_id, created_at) VALUES (?, NOW())")
            ->execute([$userId]);

        $authstmt = $pdo->prepare("SELECT pr_users.password, pr_users.user_name, pr_users.id, pr_users.mobile, pr_users.role_id, pr_roles.role_name FROM pr_users LEFT JOIN pr_roles ON pr_users.role_id = pr_roles.id WHERE pr_users.id = :id LIMIT 1  ");
        $authstmt->execute(['id' => $user['id']]);
        $authUser = $authstmt->fetch(PDO::FETCH_ASSOC);

        session_set('auth', $authUser);

        send_json_success('Registration successful', $user);
    } catch (PDOException $e) {
        send_json_error('Database error occurred', ['database' => [$e->getMessage()]], 500);
    }
}