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
    validate_required($data, 'amount', $errors);
    validate_required($data, 'user_id', $errors);

    // print_r($data);
    $chit_id = $data['chit_id'];
    $user_id = $data['user_id'];
    $amount = $data['amount'];

    $sql = "SELECT 
            pay.user_id,
            pay.user_chit_id,
            pay.chit_month,
            pay.chit_year,
            pay.amount,
            pay.transaction_date,
            pay.payment_id,
            pay.payment_method,
            pay.remarks,
            pay.payment_status,
            pay.created_at,
            pay.payment_created_by,
            pay.updated_at,
            pay.payment_updated_by
            FROM pr_payments as pay
            WHERE user_chit_id = :user_chit_id
            AND pay.payment_status = :success
            ORDER BY id DESC LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_chit_id' => $chit_id,
        ':success' => 'success',
    ]);
    $chitPayments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // print_r($chitPayments);

    $sql2 = "SELECT
        uct.id, 
        uct.userid, 
        uct.chit_scheme_id , 
        uct.scheme_amt_id , 
        uct.chit_scheme_number, 
        uct.start_date,
        uct.end_date,
        uct.status,
        uct.enable,
        uct.enable,
        uct.created_by,
        uct.created_by,
        uct.remarks
        FROM pr_userchits as uct 
        WHERE uct.id = :chit_id
        LIMIT 1
        ";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([
        ':chit_id' => $chit_id,
    ]);
    $chitDetails = $stmt2->fetchAll(PDO::FETCH_ASSOC)[0];
    // Date select
    if (!empty($chitPayments)) {
        $lastPaidMonth = isset($chitPayments[0]['chit_month']) ? $chitPayments[0]['chit_month'] : null;
        $lastPaidYear = isset($chitPayments[0]['chit_year']) ? $chitPayments[0]['chit_year'] : null;

        $date = DateTime::createFromFormat('Y-n', "$lastPaidYear-$lastPaidMonth");
        $date->modify('+1 month');

        $month = (int) $date->format('n');
        $year = (int) $date->format('Y');
    } else {

        $date = new DateTime($chitDetails['start_date']);
        $month = (int) $date->format('n');
        $year = (int) $date->format('Y');
    }

    $currentMonth = (int) date('n');
    $currentYear = (int) date('Y');

    if ($year > $currentYear || ($year === $currentYear && $month > $currentMonth)) {
        send_json_error('Already paid wait for next month', 200);
        return;
    }

    $month = $date->format('F');


    send_json_success("success", ["month" => $month, "year" => $year]);


} else {
    send_json_error('Invalid request method', [], 405);
}