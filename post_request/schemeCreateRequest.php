<?php
require '../functions/validator.php';
require '../config/db.php';
require '../functions/response.php';
require '../functions/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $errors = [];

    validate_required($data, 'scheme_name', $errors);
    validate_required($data, 'scheme_tenure', $errors);

    if (!empty($errors)) {
        send_json_error('Validation failed', $errors, 200);
    }

    try {
        $pdo = db_connection();

        $stmt = $pdo->prepare("SELECT * FROM pr_schemes WHERE scheme_name = :scheme_name AND scheme_tenure = :scheme_tenure LIMIT 1");
        $stmt->execute([
            'scheme_name' => $data['scheme_name'],
            'scheme_tenure' => $data['scheme_tenure']
        ]);
        $exists = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($exists) {
            send_json_error('Scheme Already Exists', ['scheme' => 'A scheme with this name and tenure already exists'], 200);
        }

        $createdBy = session_get('auth')['id'] ?? null;

        if (!$createdBy) {
            send_json_error('Unauthorized', ['auth' => 'User not logged in'], 401);
        }

        $insertStmt = $pdo->prepare("INSERT INTO pr_schemes (scheme_name, scheme_tenure, scheme_created_by, created_at) VALUES (:scheme_name, :scheme_tenure, :created_by, NOW())");
        $insertStmt->execute([
            'scheme_name' => $data['scheme_name'],
            'scheme_tenure' => $data['scheme_tenure'],
            'created_by' => $createdBy
        ]);

        send_json_success('Scheme Created successfully');
    } catch (PDOException $e) {
        send_json_error('Database error occurred.', ['database' => [$e->getMessage()]], 500);
    }
} else {
    send_json_error('Invalid request method', [], 405);
}