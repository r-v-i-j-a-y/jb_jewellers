<?php

require './functions/middleware.php';
require './config/db.php';

$authData = auth_protect();
$authUserId = $authData['id'];
$isAdmin = ($authData['role_id'] == 1) ? true : false;


$sql = "
        SELECT 
            pr_users.user_name,
            pr_users.id,
            pr_users.mobile,
            pr_users.email,
            pr_users.role_id,
            pr_users.created_at,
            pr_users.status,
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
            pr_user_details.updated_at
        FROM pr_users
        LEFT JOIN pr_user_details ON pr_user_details.user_id = pr_users.id
        WHERE pr_users.role_id != 1
    ";

$pdo = db_connection();
$stmt = $pdo->prepare($sql);
$stmt->execute();
$userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<?php
$pageTitle = 'User List';
include './common/head.php';


$topbarTitle = 'Users List';
$breadcrumbs = [
    ['title' => 'Home', 'url' => 'index.php'],
    ['title' => 'View Users', 'url' => '']
];

?>

<body ng-app="myApp" ng-controller="MyController as jb" class="bg-light">
    <div>
        <!-- Sidebar -->
        <?php include './common/sideBar.php'; ?>

        <!-- Main Content Area -->
        <div class="main-container">
            <!-- Fixed Topbar -->
            <?php include './common/topBar.php'; ?>

            <!-- Scrollable Content -->
            <div class="content-scrollable card border-0 d-flex justify-content-between">

                <div class="container my-5 card p-4 shadow form-section">
                    <table id="userTable" datatable class="display table " style="width:100%">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Joined</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($userData as $index => $user): ?>
                                <tr ng-repeat="user in jb.allUserList track by $index">
                                    <td><?= $index + 1 ?></td>
                                    <td class="text-capitalize"><?= $user['user_name'] ?></td>
                                    <td><?= $user['mobile'] ?></td>
                                    <td><?= $user['email'] ?></td>
                                    <td><?= $user['created_at'] ?></td>
                                    <td><?= $user['status'] ?></td>
                                    <td>
                                        <a class="btn btn-primary " href="user-details.php?id=<?= $user['id'] ?>">Update</a>
                                    </td>
                                <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                  <?php include './footerTop.php'; ?>

            </div>
        </div>
    </div>

</body>

<?php include './common/footer.php'; ?>

</html>