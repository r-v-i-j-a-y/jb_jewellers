<?php

require './functions/middleware.php';
require './config/db.php';

$authData = auth_protect();
$authUserId = $authData['id'];
$isAdmin = ($authData['role_id'] == 1) ? true : false;


$sql = "
            SELECT 
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
        ";

$pdo = db_connection();
$stmt = $pdo->prepare($sql);
$stmt->execute();
$schemeData = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html>

<?php
$pageTitle = 'Schemes';
include './common/head.php';
$topbarTitle = 'Scheme List';
$breadcrumbs = [
    ['title' => 'Home', 'url' => 'index.php'],
    ['title' => 'Schemes', 'url' => '']
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

                <div class="container py-5">
                    <div class="row g-4">
                        <!-- Card 1 -->
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-primary rounded-pill" href="scheme-create.php">Add Scheme</a>
                        </div>
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
                                        <div class="d-flex justify-content-between">
                                            <div class="form-check form-switch d-flex align-items-center mt-2">
                                                <input class="form-check-input me-2" type="checkbox"
                                                    id="schemeStatusSwitch<?= $scheme['id'] ?>"
                                                    onclick="schemeStatusChangeModal('<?= $jsonScheme ?>')" role="switch"
                                                    <?= $scheme['scheme_status'] == "active" ? "checked" : "" ?>>
                                                <label
                                                    class="form-check-label fw-bold <?= $scheme['scheme_status'] == "active" ? "text-success" : "text-danger" ?>"
                                                    for="schemeStatusSwitch" id="schemeStatusLable<?= $scheme['id'] ?>">
                                                    <?= $scheme['scheme_status'] == "active" ? "Active" : "Inactive" ?>
                                                </label>
                                            </div>
                                            <div>
                                                <a class="btn btn-success rounded-pill"
                                                    href="chits.php?scheme_id=<?= $scheme['id'] ?>">Chit List</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>

                    </div>
                </div>
                  <?php include './footerTop.php'; ?>

                <!-- Modal -->
                <div class="modal fade" id="schemeStatusModal" tabindex="-1" aria-labelledby="schemeStatusModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="schemeStatusModalLabel">Confirm to Change </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are You Sure to change status
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                    onclick="schemeStatusChangeModalClose()">Close</button>
                                <button type="button" class="btn btn-primary" onclick="confirmStatusChange(event)">
                                    <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                                    <span class="" role="status">Yes, Change</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

<?php include './common/footer.php'; ?>

</html>