<?php

require './functions/middleware.php';
require './config/db.php';

$authData = auth_protect();
$authUserId = $authData['id'];


$sql = "SELECT 
            pr_users.user_name,
            pr_users.id,
            pr_users.mobile,
            pr_users.role_id,
            pr_user_details.first_name,
            pr_user_details.last_name,
            pr_user_details.dob,
            pr_user_details.anniversary,
            pr_user_details.address1,
            pr_user_details.address2,
            pr_user_details.city,
            pr_user_details.state,
            pr_user_details.pincode,
            pr_user_details.pan_number,
            pr_user_details.aadhaar_number,
            pr_user_details.nominee,
            pr_user_details.nominee_relation,
            pr_user_details.updated_by,
            pr_user_details.updated_at,
            pr_roles.role_name
        FROM pr_users
        LEFT JOIN pr_user_details ON pr_user_details.user_id = pr_users.id
        LEFT JOIN pr_roles ON pr_users.role_id = pr_roles.id
        WHERE pr_users.role_id != :role_id AND pr_users.id = :user_id
        LIMIT 1";

$pdo = db_connection();
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':role_id' => 1,
    ':user_id' => $_GET['id']
]);
$userDetails = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
$unauthorized = false;
if ($authData['role_id'] == 2 && $authUserId != $_GET['id']) {
    $unauthorized = true;
}
?>

<!DOCTYPE html>
<html>
<?php
$pageTitle = 'User Details';
include './common/head.php'; ?>

<body class="bg-light">
    <div>
        <!-- Sidebar -->
        <?php include './common/sideBar.php'; ?>

        <!-- Main Content Area -->
        <div class="main-container">
            <!-- Fixed Topbar -->
            <?php include './common/topBar.php'; ?>

            <!-- Scrollable Content -->
            <div class="content-scrollable card border-0">
                <div class="container  my-5">
                    <?php if ($unauthorized) { ?>
                        <div class="d-flex flex-column align-items-center justify-content-center">
                            <img src="assets/images/unauth.jpg" alt="" class="w-50 h-100">
                            <h2>Unauthorized</h2>
                        </div>
                    <?php } else { ?>
                        <form id="userDetailsForm" class="card p-4 shadow form-section">
                            <div class="mb-3">
                                <strong>User Role :</strong> &nbsp; | &nbsp;
                                <strong>User ID:</strong>
                            </div>

                            <div class="row g-4">
                                <input hidden type="text" id="user_id" name="user_id" class="form-control p-2"
                                    value="<?= isset($userDetails['id']) ? $userDetails['id'] : '' ?> ">
                                <div class="col-md-6 col-lg-4">
                                    <label for="first_name">First Name</label>
                                    <input type="text" id="first_name" name="first_name" class="form-control p-2"
                                        value="<?= isset($userDetails['first_name']) ? $userDetails['first_name'] : '' ?>">
                                    <p class="text-danger error-message m-0"></p>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" id="last_name" name="last_name" class="form-control p-2"
                                        value="<?= isset($userDetails['last_name']) ? $userDetails['last_name'] : '' ?>">
                                    <p class="text-danger error-message m-0"></p>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="dob">Date of Birth</label>
                                    <input type="date" id="dob" name="dob" class="form-control p-2"
                                        value="<?= isset($userDetails['dob']) ? $userDetails['dob'] : '' ?>">
                                    <p class="text-danger error-message m-0"></p>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="anniversary">Anniversary</label>
                                    <input type="date" id="anniversary" name="anniversary" class="form-control p-2"
                                        value="<?= isset($userDetails['anniversary']) ? $userDetails['anniversary'] : '' ?>">
                                    <p class="text-danger error-message m-0"></p>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="address1">Address 1</label>
                                    <input type="text" id="address1" name="address1" class="form-control p-2"
                                        value="<?= isset($userDetails['address1']) ? $userDetails['address1'] : '' ?>">
                                    <p class="text-danger error-message m-0"></p>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="address2">Address 2</label>
                                    <input type="text" id="address2" name="address2" class="form-control p-2"
                                        value="<?= isset($userDetails['address2']) ? $userDetails['address2'] : '' ?>">
                                    <p class="text-danger error-message m-0"></p>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="city">City</label>
                                    <input type="text" id="city" name="city" class="form-control p-2"
                                        value="<?= isset($userDetails['city']) ? $userDetails['city'] : '' ?>">
                                    <p class="text-danger error-message m-0"></p>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="state">State</label>
                                    <input type="text" id="state" name="state" class="form-control p-2"
                                        value="<?= isset($userDetails['state']) ? $userDetails['state'] : '' ?>">
                                    <p class="text-danger error-message m-0"></p>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="pincode">Pincode</label>
                                    <input type="text" id="pincode" name="pincode" class="form-control p-2"
                                        value="<?= isset($userDetails['pincode']) ? $userDetails['pincode'] : '' ?>">
                                    <p class="text-danger error-message m-0"></p>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="pan_number">PAN Number</label>
                                    <input type="text" id="pan_number" name="pan_number" class="form-control p-2"
                                        value="<?= isset($userDetails['pan_number']) ? $userDetails['pan_number'] : '' ?>">
                                    <p class="text-danger error-message m-0"></p>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="aadhaar_number">Aadhaar Number</label>
                                    <input type="text" id="aadhaar_number" name="aadhaar_number" class="form-control p-2"
                                        value="<?= isset($userDetails['aadhaar_number']) ? $userDetails['aadhaar_number'] : '' ?>">
                                    <p class="text-danger error-message m-0"></p>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="nominee">Nominee</label>
                                    <input type="text" id="nominee" name="nominee" class="form-control p-2"
                                        value="<?= isset($userDetails['aadhaar_number']) ? $userDetails['nominee'] : '' ?>">
                                    <p class="text-danger error-message m-0"></p>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="nominee_relation">Nominee Relation</label>
                                    <input type="text" id="nominee_relation" name="nominee_relation"
                                        class="form-control p-2"
                                        value="<?= isset($userDetails['nominee_relation']) ? $userDetails['nominee_relation'] : '' ?>">
                                    <p class="text-danger error-message m-0"></p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-end mt-4">
                                <button type="button" class="btn btn-primary"
                                    onclick="userDetailsUpdate(event, 'userDetailsForm')">
                                    Update
                                </button>
                            </div>
                        </form>
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>

</body>

<?php include './common/footer.php'; ?>

</html>