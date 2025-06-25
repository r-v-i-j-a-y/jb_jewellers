<?php
require '../functions/validator.php';
require '../config/db.php';
require '../functions/response.php';
require '../functions/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $errors = [];

    // ✅ Validation
    validate_required($data, 'chit_amount', $errors);
    validate_required($data, 'scheme_id', $errors);

    if (!empty($errors)) {
        send_json_error('Validation failed', $errors, 200);
    }

    try {
        $pdo = db_connection();

        // ✅ Check if chit already exists
        $checkStmt = $pdo->prepare("SELECT * FROM pr_chits WHERE scheme_id = :scheme_id AND chit_amount = :chit_amount LIMIT 1");
        $checkStmt->execute([
            'scheme_id' => $data['scheme_id'],
            'chit_amount' => $data['chit_amount']
        ]);
        $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            send_json_error('Chit Already Exists', ['duplicate' => 'A chit with the same amount already exists for this scheme'], 200);
        }

        // ✅ Get user ID from session
        $createdBy = session_get('auth')['id'] ?? null;
        if (!$createdBy) {
            send_json_error('Unauthorized', ['auth' => 'User not logged in'], 401);
        }

        // ✅ Insert chit
        $insertStmt = $pdo->prepare("
            INSERT INTO pr_chits (scheme_id, chit_amount, chit_created_by, created_at)
            VALUES (:scheme_id, :chit_amount, :created_by, NOW())
        ");
        $insertStmt->execute([
            'scheme_id' => $data['scheme_id'],
            'chit_amount' => $data['chit_amount'],
            'created_by' => $createdBy
        ]);

        send_json_success('Chit Created successfully');
    } catch (PDOException $e) {
        send_json_error('Database error occurred.', ['database' => [$e->getMessage()]], 500);
    }
} else {
    send_json_error('Invalid request method', [], 405);
}