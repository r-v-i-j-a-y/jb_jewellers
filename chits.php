<?php

require './functions/middleware.php';
require './config/db.php';

$authData = auth_protect();
$authUserId = $authData['id'];
$isAdmin = ($authData['role_id'] == 1) ? true : false;

$sql = "SELECT id, 
                    chit_amount, 
                    scheme_id, 
                    chit_created_by, 
                    created_at, 
                    updated_at, 
                    status
                FROM pr_chits
                WHERE scheme_id = :scheme_id
            ";

$params = ['scheme_id' => $_GET['scheme_id']];

// Conditionally add OR condition
if (!$isAdmin) {
    $sql .= " And status = :status";
    $params['status'] = "active";
}

$pdo = db_connection();
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$chitData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$usersql = "
        SELECT 
            pr_users.user_name,
            pr_users.id,
            pr_users.mobile,
            pr_users.email,
            pr_users.role_id,
            pr_users.created_at,
            pr_users.status
        FROM pr_users
        WHERE pr_users.role_id != 1
    ";
$pdo = db_connection();
$stmt1 = $pdo->prepare($usersql);
$stmt1->execute();
$userData = $stmt1->fetchAll(PDO::FETCH_ASSOC);


$schmeSql = "SELECT sm.id,
                    sm.scheme_name,
                    sm.scheme_tenure
                FROM pr_schemes as sm
                WHERE sm.id = :scheme_id
    ";
$pdo = db_connection();
$stmt2 = $pdo->prepare($schmeSql);
$stmt2->execute(['scheme_id' => $_GET['scheme_id']]);
$schemeData = $stmt2->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<?php
$pageTitle = 'Chits';
include './common/head.php';

$topbarTitle = 'Chit List';
$breadcrumbs = [
    ['title' => 'Home', 'url' => 'index.php'],
    ['title' => 'Schemes', 'url' => 'schemes.php'],
    ['title' => 'Purchase Chit', 'url' => '']
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
                        <h4 class="text-muted m-0">Scheme - <?= $schemeData[0]['scheme_name'] ?></h4>
                        <h6 class="text-muted m-0">Duration - <?= $schemeData[0]['scheme_tenure'] ?> Months</h6>
                        <!-- Card 1 -->
                        <div class="d-flex justify-content-end">

                            <a class="btn btn-warning rounded-pill"
                                href="chit-create.php?scheme_id=<?php echo $_GET['scheme_id'] ?>">Add Chit</a>
                        </div>
                        <!-- <input hidden type="text" id="user_id" name="user_id" class="form-control p-2"
                                ng-model="jb.selectUserDetilas.id"> -->
                        <?php if (empty($chitData)): ?>
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <img src="assets/images/comming_soon.jpg" alt="" class="w-50 h-100">
                            </div>
                        <?php endif ?>
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
                  <?php include './footerTop.php'; ?>

                <!-- Modal -->
                <div class="modal fade" id="chitStatusModal" tabindex="-1" aria-labelledby="chitStatusModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="chitStatusModalLabel">Confirm to Change</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are You Sure to chage status
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                    onclick="chitStatusChangeModalClose()">Close</button>
                                <button type="button" class="btn btn-primary" onclick="confirmChitStatusChange(event)">
                                    <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                                    <span class="" role="status">Yes, Change</span>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="modal fade" id="chitPurchaseModal" tabindex="-1" aria-labelledby="chitPurchaseModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="chitPurchaseModalLabel">Modal title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are You Sure to purchase
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="confirmChitPurchase()">Yes,
                                    Change</button>
                            </div>
                        </div>
                    </div>
                </div> -->

            </div>
        </div>
    </div>

</body>

<?php include './common/footer.php'; ?>




</html>