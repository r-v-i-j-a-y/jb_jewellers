<?php

require './functions/middleware.php';
require './config/db.php';

auth_protect();


$sql = "SELECT 
                    id, 
                    chit_amount, 
                    scheme_id, 
                    chit_created_by, 
                    created_at, 
                    updated_at, 
                    status
                FROM chits
                WHERE scheme_id = :scheme_id
            ";
$pdo = db_connection();
$stmt = $pdo->prepare($sql);
$stmt->execute(['scheme_id' => $_GET['scheme_id']]);
$chitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

                <div class="container py-5">
                    <div class="row g-4">
                        <!-- Card 1 -->
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-warning rounded-pill"
                                href="chit-create.php?scheme_id=<?php echo $_GET['scheme_id'] ?>">Add Chit</a>
                        </div>
                        <?php foreach ($chitData as $chit): ?>
                            <?php $jsonChit = htmlspecialchars(json_encode($chit), ENT_QUOTES, 'UTF-8'); ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card shadow-sm border-0" style="background-color:rgb(211, 255, 215);">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">

                                            <i class="bi bi-bookmark"></i>
                                        </div>
                                        <h5 class="card-title fw-bold text-center">
                                            <?= $chit['chit_amount'] ?> ₹
                                        </h5>

                                        <div class="d-flex gap-2 mb-3">
                                            <span
                                                class="badge bg-light border <?= $chit['status'] == "active" ? "text-success" : "text-danger" ?>"><?= $chit['status'] ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="form-check form-switch d-flex align-items-center mt-2">
                                                <input class="form-check-input me-2" type="checkbox"
                                                    id="chitStatusSwitch<?= $chit['id'] ?>"
                                                    onclick="chitStatusChangeModal(<?= $jsonChit ?>)" role="switch"
                                                    <?= $chit['status'] == "active" ? "checked" : "" ?>>
                                                <label
                                                    class="form-check-label fw-bold <?= $chit['status'] == "active" ? "text-success" : "text-danger" ?>"
                                                    for="chitStatusSwitch">
                                                    <?= $chit['status'] == "active" ? "Active" : "Inactive" ?>

                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>

                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="chitStatusModal" tabindex="-1" aria-labelledby="chitStatusModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="chitStatusModalLabel">Modal title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are You Sure to change status
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                    onclick="chitStatusChangeModalClose()">Close</button>
                                <button type="button" class="btn btn-primary" onclick="confirmChitStatusChange()">Yes,
                                    Change</button>
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