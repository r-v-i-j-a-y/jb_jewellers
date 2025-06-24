<?php
require '../functions/validator.php';
require '../config/db.php';
require '../functions/response.php';
require '../functions/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $errors = [];

    validate_required($data, 'id', $errors);

    if (!empty($errors)) {
        send_json_error('Validation failed', $errors, 200);
    }

    try {
        $pdo = db_connection();
        $stmt = $pdo->prepare("SELECT * FROM schemes WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $data['id']]);
        $scheme = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($scheme) {
            $newStatus = ($scheme['scheme_status'] === 'active') ? 'inactive' : 'active';
            $updateStmt = $pdo->prepare("UPDATE schemes SET scheme_status = :status WHERE id = :id");
            $updateStmt->execute([
                'status' => $newStatus,
                'id' => $data['id']
            ]);

            send_json_success("Scheme {$newStatus} successfully");
        } else {
            send_json_error('Scheme not found', ['id' => 'No scheme with this ID'], 200);
        }
    } catch (PDOException $e) {
        send_json_error('Database error occurred.', ['database' => [$e->getMessage()]], 500);
    }
} else {
    send_json_error('Invalid request method', [], 405);
}