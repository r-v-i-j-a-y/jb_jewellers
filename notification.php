<?php

require './functions/middleware.php';
require './config/db.php';

$authData = auth_protect();
$authUserId = $authData['id'];
$isAdmin = ($authData['role_id'] == 1) ? true : false;

$pdo = db_connection();

$sql = "SELECT 
        ntf.id,
        ntf.user_id,
        ntf.message,
        ntf.notification_status,
        ntf.created_at,
        ntf.updated_at,
        us.id as user_id,
        us.role_id
        FROM pr_notifications as ntf 
        LEFT JOIN pr_users as us ON ntf.user_id = us.id";

if ($isAdmin) {
    $sql .= " WHERE us.role_id = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // read
    $updateSql = "UPDATE pr_notifications AS ntf
                  LEFT JOIN pr_users AS us ON ntf.user_id = us.id
                  SET ntf.notification_status = 'read'
                  WHERE us.role_id = 1";

    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute();
} else {
    $sql .= " WHERE ntf.user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $authUserId]);

    // Read 
    $updateSql = "UPDATE pr_notifications AS ntf
                  LEFT JOIN pr_users AS us ON ntf.user_id = us.id
                  SET ntf.notification_status = 'read'
                  WHERE ntf.user_id = :user_id";

    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute(['user_id' => $authUserId]);
}

$notificationData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<?php
$pageTitle = 'Notification';
include './common/head.php';

$topbarTitle = 'Notification';
$breadcrumbs = [
    ['title' => 'Home', 'url' => 'index.php'],
    ['title' => 'Notification', 'url' => '']
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

                <div class="container my-5">
                    <table id="userTable" datatable class="display table " style="width:100%">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notificationData as $index => $notification): ?>
                                <tr ng-repeat="user in jb.allUserList track by $index">
                                    <td><?= $index + 1 ?></td>
                                    <td><?= $notification['message'] ?></td>
                                    <td><?= $notification['notification_status'] ?></td>
                                    <td><?= $notification['created_at'] ?></td>
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