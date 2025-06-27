<?php
require '../functions/validator.php';
require '../config/db.php';
require '../functions/response.php';
require '../functions/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo = db_connection();
    $data = $_POST;
    $errors = [];
    $payment_id = $data['payment_id'];
    $status = $data['status'];
    validate_required($data, 'payment_id', $errors);

    if (!empty($errors)) {
        send_json_error('Validation failed', $errors, 200);
    }

    try {
        $sql = "
            UPDATE pr_payments SET
            payment_status = :payment_status
            WHERE payment_id = :payment_id
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'payment_status' => $status,
            'payment_id' => $payment_id
        ]);

        send_json_success("Payment" . " " . $status);

    } catch (PDOException $e) {
        send_json_error('Database error occurred.', ['database' => [$e->getMessage()]], 500);
    }
}