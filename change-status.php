<?php

require './functions/middleware.php';
require './config/db.php';
$authData = auth_protect();
$authUserId = $authData['id'];
$isAdmin = ($authData['role_id'] == 1) ? true : false;

$chit_status = isset($_GET['chit_status']) ? $_GET['chit_status'] : null;


$sql = "SELECT 
        uc.id,
        uc.userid ,
        uc.chit_scheme_id,
        uc.scheme_amt_id,
        uc.chit_scheme_number,
        uc.start_date,
        uc.end_date,
        uc.status,
        uc.enable,
        uc.created_at,
        uc.created_by,
        uc.updated_at,
        uc.remarks,
        ct.chit_amount,
        sm.scheme_name,
        sm.scheme_tenure,
        ur.user_name,
        ur.mobile,
        ur.email
        FROM pr_userchits as uc
        LEFT JOIN pr_chits as ct ON ct.id = uc.scheme_amt_id
        LEFT JOIN pr_schemes as sm ON sm.id = uc.chit_scheme_id
        LEFT JOIN pr_users as ur ON ur.id = uc.userid
        ORDER BY  uc.created_at DESC
    ";
$params = null;
if (!empty($chit_status)) {
    $sql .= "WHERE uc.status = :chit_status";
    $params['chit_status'] = $chit_status;
}
$pdo = db_connection();
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$chitStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<?php
$pageTitle = 'Change Status';
include './common/head.php';
$topbarTitle = 'Change Status';
$breadcrumbs = [
    ['title' => 'Home', 'url' => 'index.php'],
    ['title' => 'Change Status', 'url' => '']
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
            <div class="mt-4 p-0">
                <div class="card p-4 ">
                    <!-- <h5 class="text-muted">Filters</h5> -->
                    <div class="d-flex flex-wrap  gap-3 justify-content-center">
                        <div class="d-flex flex-column">
                            <label for="" class="small text-muted ms-1">Chit Status</label>
                            <select onchange="changeStatusFilter()" class="selectpicker " name="" id="chitStatusFilter">
                                <option value="">Select Chit Status</option>
                                <option <?= $chit_status == 'pending' ? 'selected="selected"' : '' ?> value="pending">
                                    Pending</option>
                                <option <?= $chit_status == 'approved' ? 'selected="selected"' : '' ?> value="approved">
                                    Approved</option>
                                <option <?= $chit_status == 'rejected' ? 'selected="selected"' : '' ?> value="rejected">
                                    Rejected
                                </option>
                                <option <?= $chit_status == 'closed' ? 'selected="selected"' : '' ?> value="closed">Closed
                                <option <?= $chit_status == 'discontinued' ? 'selected="selected"' : '' ?>
                                    value="discontinued">Discontinued
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Scrollable Content -->
            <div class="content-scrollable card border-0 d-flex justify-content-between">

                <div class="container my-5  p-4  form-section ">
                    <table id="statusTable" datatable class="display table overflow-x-scroll " style="width:100%">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Date</th>
                                <th>User Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Scheme Name</th>
                                <th>Chit id</th>
                                <th>Tenure</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($chitStatus as $index => $chit): ?>
                                <tr class="text-nowrap" ng-repeat="user in jb.allUserList track by $index">
                                    <td><?= $index + 1 ?></td>
                                    <td class="text-capitalize"><?= $chit['created_at'] ?></td>
                                    <td class="text-capitalize"><?= $chit['user_name'] ?></td>
                                    <td class="text-capitalize"><?= $chit['mobile'] ?></td>
                                    <td class="text-capitalize"><?= $chit['email'] ?></td>
                                    <td class="text-capitalize"><?= $chit['scheme_name'] ?></td>
                                    <td class="text-capitalize"><?= $chit['chit_scheme_number'] ?></td>
                                    <td class=""><?= $chit['scheme_tenure'] ?> Months</td>
                                    <td><?= $chit['chit_amount'] ?> ₹</td>
                                    <td class="text-capitalize"><?= $chit['status'] ?></td>
                                    <td>
                                        <select class="form-select" onchange="chitStatusChange(<?= $chit['id'] ?>)" name=""
                                            id="chitStatus<?= $chit['id'] ?>">
                                            <option <?= $chit['status'] == 'pending' ? 'selected' : '' ?> value="pending">
                                                Pending</option>
                                            <option <?= $chit['status'] == 'approved' ? 'selected' : '' ?> value="approved">
                                                Approved</option>
                                            <option <?= $chit['status'] == 'rejected' ? 'selected' : '' ?> value="rejected">
                                                Rejected</option>
                                            <option <?= $chit['status'] == 'closed' ? 'selected' : '' ?> value="closed">Closed
                                            </option>
                                            <option <?= $chit['status'] == 'pending' ? 'discontinued' : '' ?>
                                                value="discontinued">Discontinued</option>
                                        </select>
                                    </td>
                                <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                                  <?php include './footerTop.php'; ?>
            </div>
        </div>
    </div>
    <div class="modal fade" id="chaneStatusModal" tabindex="-1" aria-labelledby="chaneStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="chaneStatusModalLabel">Confirm to Change</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are You Sure to change status
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="confirmChangeStatusClose()"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="confirmChangeStatus(event)">
                        <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                        <span class="" role="status">Yes, Change</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>

<?php include './common/footer.php'; ?>

</html>