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
    $admin_id_sql = "SELECT id FROM pr_users WHERE role_id = 1 AND status = 'active' ORDER BY id DESC LIMIT 1";
    $admin_stmt = $pdo->prepare($admin_id_sql);
    $admin_stmt->execute();
    $admin_id = $admin_stmt->fetchAll(PDO::FETCH_ASSOC)[0]['id'];




    // print_r($admin_id['id']);
    try {
        $chit_number = $data['chit_number'];
        $scheme_id = $data['scheme_id'];
        $user_id = $data['user_id'];

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
            'userid' => $user_id,
            'chit_scheme_id' => $scheme_id,
            'scheme_amt_id' => $data['chit_id'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'created_by' => $authUserId
        ]);
        $lastInsertId = $pdo->lastInsertId();
        $chit_scheme_number = $chit_number . '_' . $lastInsertId;


        $updateSql = "UPDATE pr_userchits SET chit_scheme_number = :chit_scheme_number WHERE id = :id";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([
            'chit_scheme_number' => $chit_scheme_number,
            'id' => $lastInsertId
        ]);

        $user_details_sql = "SELECT user_name,mobile,email FROM pr_users WHERE id =  $user_id ";
        $userStmt = $pdo->prepare($user_details_sql);
        $userStmt->execute();
        $user_details = $userStmt->fetchAll(PDO::FETCH_ASSOC)[0];
        $customer_name = $user_details['user_name'];
        $customer_mobile = $user_details['mobile'];
        $customer_email = $user_details['email'];

        // Notification 
        $message = "purchased new chit :  customer name : $customer_name , Mobile : $customer_mobile, Email : $customer_email,  chit number : $chit_scheme_number";
        $notification_sql = "INSERT INTO pr_notifications (user_id ,message,created_at,updated_at) VALUE (:user_id,:message,NOW(),NOW())";
        $notify_stmt = $pdo->prepare($notification_sql);
        $notify_stmt->execute(
            [
                'user_id' => $admin_id,
                'message' => $message
            ]
        );

        send_json_success("Chit Purchased Successfully");


    } catch (PDOException $e) {
        send_json_error('Database error occurred', ['database' => [$e->getMessage()]], 500);
    }
} else {
    send_json_error('Invalid request method', [], 405);
}