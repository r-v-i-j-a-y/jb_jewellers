<?php
require '../functions/validator.php';
require '../config/db.php';
require '../functions/response.php';
require '../functions/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $errors = [];

    // ✅ Validate ID
    validate_required($data, 'id', $errors);

    if (!empty($errors)) {
        send_json_error('Validation failed', $errors, 200);
    }

    try {
        $pdo = db_connection();

        // ✅ Check if chit exists
        $stmt = $pdo->prepare("SELECT * FROM chits WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $data['id']]);
        $chit = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($chit) {
            $newStatus = ($chit['status'] === 'active') ? 'inactive' : 'active';

            // ✅ Update chit status
            $updateStmt = $pdo->prepare("UPDATE chits SET status = :status WHERE id = :id");
            $updateStmt->execute([
                'status' => $newStatus,
                'id' => $data['id']
            ]);

            send_json_success("Chit {$newStatus} successfully");
        } else {
            send_json_error('Chit not found', ['id' => 'No chit with this ID'], 200);
        }

    } catch (PDOException $e) {
        send_json_error('Database error occurred', ['database' => [$e->getMessage()]], 500);
    }
} else {
    send_json_error('Invalid request method', [], 405);
}