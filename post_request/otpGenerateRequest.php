<?php
require '../functions/response.php';
session_start();

$mobile = 7397302034; // recipient number
$otp = rand(100000, 999999); // generate OTP
$_SESSION['otp'] = $otp; // store OTP for later verification

$apiKey = "YOUR_FAST2SMS_API_KEY"; // replace with your actual API key
$senderId = "FSTSMS"; // or your approved sender ID

$msg = "Your OTP for verification is $otp. Do not share it with anyone.";

$postData = array(
    "sender_id" => $senderId,
    "message" => $msg,
    "language" => "english",
    "route" => "p", // 'p' for promotional, 't' for transactional
    "numbers" => $mobile,
);

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($postData),
    CURLOPT_HTTPHEADER => array(
        "authorization: $apiKey",
        "accept: */*",
        "cache-control: no-cache",
        "content-type: application/json"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    send_json_error('Otp Generation filed', $err, 200);
} else {
    send_json_success('OTP sent successfully');
}
