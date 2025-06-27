<?php
require '../config/db.php';
require '../functions/middleware.php';
require '../functions/response.php';
require '../functions/validator.php';
require '../razorpay/Razorpay.php';// or include Razorpay.php manually
$env = parse_ini_file(__DIR__ . '/../.env');

$authData = auth_protect();
$authUserId = $authData['id'];
$authRoleID = $authData['role_id'];
$pdo = db_connection();


use Razorpay\Api\Api;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // validate_required($data, 'chit_id', $errors);
    // validate_required($data, 'amount', $errors);
    // validate_required($data, 'user_id', $errors);
    // validate_required($data, 'month', $errors);
    // validate_required($data, 'year', $errors);

    // if (!empty($errors)) {
    //     send_json_error('Validation failed', $errors, 200);
    // }

    $data = $_POST;

    $chit_id = $data['chit_id'];
    $user_id = $data['user_id'];
    $amount = $data['amount'];
    $month = $data['month'];
    $year = $data['year'];

    $amountInRupees = floatval($_POST['amount'] ?? 0);
    $razorpayAmount = intval($amountInRupees * 100);

    $api_key = $env['RAZER_PAY_KEY_ID'];
    $api_secret = $env['RAZER_PAY_SECRET'];

    $api = new Api($api_key, $api_secret);
    $payment_id = 'payid_' . uniqid() . '_' . time();
    $orderData = [
        'receipt' => $payment_id,
        'amount' => $razorpayAmount,
        'currency' => 'INR',
        'payment_capture' => 1
    ];

    $order = $api->order->create($orderData);




    $sql = "INSERT INTO pr_payments
                (user_id,
                user_chit_id,
                chit_month,
                chit_year,
                amount,
                transaction_date,
                payment_id,
                payment_method,
                payment_status,
                created_at,
                updated_at,
                payment_created_by,
                payment_updated_by)
                VALUES 
                (:user_id,
                :user_chit_id,
                :chit_month,
                :chit_year,
                :amount,
                NOW(),
                :payment_id,
                :payment_method,
                :payment_status,
                NOW(),
                NOW(),
                :payment_created_by,
                :payment_updated_by)

            ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        "user_id" => $user_id,
        "user_chit_id" => $chit_id,
        "chit_month" => $month,
        "chit_year" => $year,
        "amount" => $amount,
        "payment_id" => $payment_id,
        "payment_method" => "upi",
        "payment_status" => "pending",
        "payment_created_by" => $authUserId,
        "payment_updated_by" => $authUserId
    ]);

    echo json_encode([
        'order_id' => $order['id'],
        'amount' => $order['amount'],
        'currency' => $order['currency'],
        'payment_id' => $payment_id
    ]);

} else {
    send_json_error('Invalid request method', [], 405);
}