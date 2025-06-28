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
    $status = $data['status'];
    $chit_id = $data['chit_id'];

    $pdo = db_connection();

    validate_required($data, 'chit_id', $errors);
    validate_required($data, 'status', $errors);

    try {

        $sql = "
                UPDATE pr_userchits SET
                    status = :status,
                    updated_at = :updated_at
                WHERE id = :id
            ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':status' => $status,
            ':updated_at' => $authUserId,
            ':id' => $chit_id,
        ]);

        $user_chit_sql = "SELECT chit_scheme_number ,userid,scheme_name,scheme_tenure FROM pr_userchits as uct  LEFT JOIN pr_schemes as scm ON scm.id = uct.chit_scheme_id WHERE uct.id = $chit_id";
        $stmt1 = $pdo->prepare($user_chit_sql);
        $stmt1->execute();
        $user_chit = $stmt1->fetchAll(PDO::FETCH_ASSOC)[0];
        $scheme_name = $user_chit['scheme_name'];
        $scheme_tenure = $user_chit['scheme_tenure'];
        $userid = $user_chit['userid'];

        $message = "your chit is $status . Scheme : $scheme_name, tenure :$scheme_tenure";
        $notification_sql = "INSERT INTO pr_notifications (user_id ,message,created_at,updated_at) VALUE (:user_id,:message,NOW(),NOW())";
        $notify_stmt = $pdo->prepare($notification_sql);
        $notify_stmt->execute(
            [
                'user_id' => $userid,
                'message' => $message
            ]
        );


        send_json_success("Status updated successfully");
    } catch (PDOException $e) {
        send_json_error('Database error occurred.', ['database' => [$e->getMessage()]], 500);
    }


} else {
    send_json_error('Invalid request method', [], 405);
}