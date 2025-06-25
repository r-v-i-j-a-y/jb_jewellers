<?php
require '../functions/validator.php';
require '../config/db.php';
require '../functions/response.php';
require '../functions/session.php';
require '../functions/middleware.php';

$authData = auth_protect();
$authUserId = $authData['id'];
$authRoleID = $authData['role_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $errors = [];

    $pdo = db_connection();

    validate_required($data, 'chit_id', $errors);
    validate_required($data, 'status', $errors);

    // print_r($data);
    try {

        $sql = "
                UPDATE pr_userchits SET
                    status = :status,
                    updated_at = :updated_at
                WHERE id = :id
            ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':status' => $data['status'],
            ':updated_at' => $authUserId,
            ':id' => $data['chit_id'],
        ]);

        send_json_success("Status updated successfully");
    } catch (PDOException $e) {
        send_json_error('Database error occurred.', ['database' => [$e->getMessage()]], 500);
    }


} else {
    send_json_error('Invalid request method', [], 405);
}