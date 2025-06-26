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

$topbarTitle = 'Scheme List';
$breadcrumbs = [
    ['title' => 'Home', 'url' => 'index.php'],
    ['title' => 'Purchase Schemes', 'url' => '']
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
                    <div class="row g-4">
                        <!-- Card 1 -->


                        <?php foreach ($schemeData as $scheme): ?>
                            <?php $jsonScheme = htmlspecialchars(json_encode($scheme), ENT_QUOTES, 'UTF-8'); ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card shadow-sm border-0" style="background-color: #e9f0ff;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">

                                            <i class="bi bi-bookmark"></i>
                                        </div>
                                        <h5 class="card-title fw-bold text-center"><?= $scheme['scheme_name'] ?></h5>

                                        <div class="d-flex gap-2 mb-3">
                                            <span class="badge bg-light text-dark border"><?= $scheme['scheme_tenure'] ?>
                                                Months</span>
                                            <span
                                                class="badge bg-light border <?= $scheme['scheme_status'] == "active" ? "text-success" : "text-danger" ?>"><?= $scheme['scheme_status'] ?></span>
                                        </div>
                                        <div class="d-flex justify-content-end w-100">
                                            <a class="btn btn-success rounded-pill"
                                                href="purchase-chit.php?scheme_id=<?= $scheme['id'] ?>">Chit List</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

<?php include './common/footer.php'; ?>

</html>