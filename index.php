<?php

require './functions/middleware.php';
require './config/db.php';

$authData = auth_protect();
$authUserId = $authData['id'];

$sql = "SELECT 
                pr_schemes.id,
                pr_schemes.scheme_name,
                pr_schemes.scheme_tenure,
                pr_schemes.scheme_status,
                pr_schemes.scheme_created_by,
                pr_schemes.created_at,
                pr_schemes.updated_at,
                pr_users.user_name
            FROM pr_schemes
            LEFT JOIN pr_users ON pr_users.id = pr_schemes.scheme_created_by
            Where pr_schemes.scheme_status = 'active'
        ";

$pdo = db_connection();
$stmt = $pdo->prepare($sql);
$stmt->execute();
$schemeData = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<?php
$pageTitle = 'Dashboard';
include './common/head.php';
$topbarTitle = 'Dashboard';
$breadcrumbs = [
    ['title' => 'Home', 'url' => '']
];

?>

<body ng-app="myApp" ng-controller="MyController as jb" class="bg-light">
    <div ng-init="jb.commonInit()">
        <!-- Sidebar -->
        <?php include './common/sideBar.php'; ?>

        <!-- Main Content Area -->
        <div class="main-container">
            <!-- Fixed Topbar -->
            <?php include './common/topBar.php'; ?>

            <!-- Scrollable Content -->
            <div class="content-scrollable card border-0">

                <div class="container py-5">

                </div>
            </div>
        </div>
    </div>

</body>

<?php include './common/footer.php'; ?>

</html>