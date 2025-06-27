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
$topbarTitle = 'About Us';
$breadcrumbs = [
    ['title' => 'Home', 'url' => 'index.php'],
    ['title' => 'About us', 'url' => '']
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
            <div class="content-scrollable card border-0  d-flex flex-column justify-content-between">

                <div class="container p-5 text-muted ">
                    <h2 class="text-uppercase text-warning mb-3" style="text-shadow: 1px 1px 2px #00000047;">Connect With Us
                    </h2>
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3><i class="fa-solid fa-map-location-dot me-2"></i>Address</h3>
                            <p class="mb-1">No. 27/29, 3rd Main Road,</p>
                            <P>Thillai Ganga Nagar, Nanganallur, Chennai - 600061.</p>
                        </div>
                        <div>
                            <h3> <i class="fa-solid fa-mobile-screen me-2"></i>Contact</h3>
                            <p class="mb-1"><b>Tel : </b> 044 35512123, 044 35511776</p>
                            <p><b>Email : </b> prayaanjewelmart@gmail.com, support@prayaanjewelmart.co.in</p>
                        </div>
                    </div>
                </div>
                <?php include './footerTop.php'; ?>
            </div>
        </div>
    </div>

</body>

<?php include './common/footer.php'; ?>

</html>