<?php
require '../functions/validator.php';
require '../config/db.php';
require '../functions/response.php';
require '../functions/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo = db_connection();
    $data = $_POST;
    $errors = [];

    $userId = $data['user_id'];
    $data['anniversary'] = !empty($data['anniversary']) ? $data['anniversary'] : null;
    $data['address2'] = !empty($data['address2']) ? $data['address2'] : null;

    // Validation
    validate_required($data, 'first_name', $errors);
    validate_required($data, 'last_name', $errors);
    validate_required($data, 'dob', $errors);
    validate_required($data, 'address1', $errors);
    validate_required($data, 'city', $errors);
    validate_required($data, 'state', $errors);
    validate_required($data, 'pincode', $errors);
    validate_required($data, 'pan_number', $errors);
    validate_required($data, 'aadhaar_number', $errors);
    validate_numeric($data, 'aadhaar_number', $errors);
    validate_required($data, 'nominee', $errors);
    validate_required($data, 'nominee_relation', $errors);
    validate_required($data, 'user_id', $errors);
    validate_numeric($data, 'user_id', $errors);

    validate_unique($data, 'pan_number', 'pr_user_details', 'pan_number', $pdo, $errors, $userId, 'user_id');
    validate_unique($data, 'aadhaar_number', 'pr_user_details', 'aadhaar_number', $pdo, $errors, $userId, 'user_id');

    if (!empty($errors)) {
        send_json_error('Validation failed', $errors, 200);
    }

    try {
        $sql = "
            UPDATE pr_user_details SET
                first_name = :first_name,
                last_name = :last_name,
                dob = :dob,
                anniversary = :anniversary,
                address1 = :address1,
                address2 = :address2,
                city = :city,
                state = :state,
                pincode = :pincode,
                pan_number = :pan_number,
                aadhaar_number = :aadhaar_number,
                nominee = :nominee,
                nominee_relation = :nominee_relation,
                updated_at = NOW()
            WHERE user_id = :user_id
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':dob' => $data['dob'],
            ':anniversary' => $data['anniversary'],
            ':address1' => $data['address1'],
            ':address2' => $data['address2'],
            ':city' => $data['city'],
            ':state' => $data['state'],
            ':pincode' => $data['pincode'],
            ':pan_number' => $data['pan_number'],
            ':aadhaar_number' => $data['aadhaar_number'],
            ':nominee' => $data['nominee'],
            ':nominee_relation' => $data['nominee_relation'],
            ':user_id' => $data['user_id']
        ]);

        send_json_success("User Details Updated Successfully");

    } catch (PDOException $e) {
        send_json_error('Database error occurred.', ['database' => [$e->getMessage()]], 500);
    }
}