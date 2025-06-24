<?php

require './functions/middleware.php';
require './config/db.php';

auth_protect();


$sql = "
        SELECT 
            users.user_name,
            users.id,
            users.mobile,
            users.email,
            users.role_id,
            users.created_at,
            users.status,
            user_details.first_name,
            user_details.last_name,
            user_details.dob,
            user_details.anniversary,
            user_details.address1,
            user_details.address2,
            user_details.city,
            user_details.state,
            user_details.pincode,
            user_details.pan_number,
            user_details.aadhaar_number,
            user_details.nominee,
            user_details.nominee_relation,
            user_details.updated_by,
            user_details.updated_at
        FROM users
        LEFT JOIN user_details ON user_details.user_id = users.id
        WHERE users.role_id != 1
    ";

$pdo = db_connection();
$stmt = $pdo->prepare($sql);
$stmt->execute();
$userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<?php
$pageTitle = 'Dashboard';
include './common/head.php'; ?>

<body ng-app="myApp" ng-controller="MyController as jb" class="bg-light">
    <div>
        <!-- Sidebar -->
        <?php include './common/sideBar.php'; ?>

        <!-- Main Content Area -->
        <div class="main-container">
            <!-- Fixed Topbar -->
            <?php include './common/topBar.php'; ?>

            <!-- Scrollable Content -->
            <div class="content-scrollable card border-0">

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

            </div>
        </div>
    </div>

</body>

<?php include './common/footer.php'; ?>

</html>