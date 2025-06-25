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
    validate_required($data, 'user_id', $errors);
    validate_required($data, 'scheme_id', $errors);
    validate_required($data, 'chit_number', $errors);

    if (!empty($errors)) {
        send_json_error('Validation failed', $errors, 200);
    }
    // print_r($data);
    try {
        $chit_number = $data['chit_number'];
        $scheme_id = $data['scheme_id'];

        $schemeSql = "SELECT id,scheme_tenure,scheme_status FROM pr_schemes WHERE id = $scheme_id";
        $schemeStmt = $pdo->prepare($schemeSql);
        $schemeStmt->execute();
        $schemeData = $schemeStmt->fetchAll(PDO::FETCH_ASSOC)[0];

        $schemeTenure = $schemeData['scheme_tenure'];
        $date = new DateTime();
        $startDate = $date->format('Y-m-d');
        $endDate = $date->modify("+{$schemeTenure} months")->format('Y-m-d');


        $sql = "INSERT INTO pr_userchits (
            userid, chit_scheme_id, scheme_amt_id, start_date, end_date,
            created_by, updated_at, created_at
        ) VALUES (
            :userid, :chit_scheme_id, :scheme_amt_id, :start_date, :end_date,
            :created_by, NOW(), NOW()
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'userid' => $data['user_id'],
            'chit_scheme_id' => $data['scheme_id'],
            'scheme_amt_id' => $data['chit_id'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'created_by' => $authUserId
        ]);
        $lastInsertId = $pdo->lastInsertId();

        $chit_scheme_number = $data['chit_number'] . '_' . $lastInsertId;

        $updateSql = "UPDATE pr_userchits SET chit_scheme_number = :chit_scheme_number WHERE id = :id";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([
            'chit_scheme_number' => $chit_scheme_number,
            'id' => $lastInsertId
        ]);

        send_json_success("Chit Purchased Successfully");


    } catch (PDOException $e) {
        send_json_error('Database error occurred', ['database' => [$e->getMessage()]], 500);
    }
} else {
    send_json_error('Invalid request method', [], 405);
}