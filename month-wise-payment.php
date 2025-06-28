<?php

require './functions/middleware.php';
require './config/db.php';

$authData = auth_protect();
$authUserId = $authData['id'];
$isAdmin = ($authData['role_id'] == 1) ? true : false;
$pdo = db_connection();


$schemeSql = "SELECT id,scheme_name FROM pr_schemes";
$schemeStmt = $pdo->prepare($schemeSql);
$schemeStmt->execute();
$SchemeData = $schemeStmt->fetchAll(PDO::FETCH_ASSOC);


$chitSql = "SELECT id,chit_amount FROM pr_chits GROUP BY chit_amount";
$chiStmt = $pdo->prepare($chitSql);
$chiStmt->execute();
$chitData = $chiStmt->fetchAll(PDO::FETCH_ASSOC);

$userChitSql = "SELECT uct.userid,uct.id as user_chit_id,us.id as user_id,uct.chit_scheme_number,us.user_name,us.mobile  FROM pr_userchits as uct LEFT JOIN pr_users as us ON us.id = uct.userid";
$userChiStmt = $pdo->prepare($userChitSql);
$userChiStmt->execute();
$userChitData = $userChiStmt->fetchAll(PDO::FETCH_ASSOC);


$scheme_id = isset($_GET['scheme_id']) ? $_GET['scheme_id'] : null;
$chit_amount = isset($_GET['chit_amount']) ? $_GET['chit_amount'] : null;
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
$pay_status = isset($_GET['pay_status']) ? $_GET['pay_status'] : null;
$chit_status = isset($_GET['chit_status']) ? $_GET['chit_status'] : null;
$start_at = isset($_GET['start_at']) ? $_GET['start_at'] : null;
$end_at = isset($_GET['end_at']) ? $_GET['end_at'] : null;



$sql = "SELECT 
    pay.id,
    pay.user_id,
    pay.user_chit_id,
    pay.chit_month,
    pay.chit_year,
    pay.amount,
    pay.transaction_date,
    pay.payment_id,
    pay.payment_method,
    pay.remarks,
    pay.payment_status,
    pay.created_at,
    pay.updated_at,
    pay.payment_created_by,
    pay.payment_updated_by,
    sm.scheme_name,
    sm.id as scheme_id,
    sm.scheme_tenure,
    uct.chit_scheme_number,
    usr.user_name,
    usr.email,
    usr.mobile,
    uct.status as user_chit_status
    FROM pr_payments AS pay
    LEFT JOIN pr_userchits AS uct ON uct.id = pay.user_chit_id
    LEFT JOIN pr_users AS usr ON usr.id = pay.user_id
    LEFT JOIN pr_chits AS ct ON ct.id = uct.scheme_amt_id 
    LEFT JOIN pr_schemes AS sm ON sm.id = uct.chit_scheme_id";

$conditions = [];
$params = [];

if (!empty($scheme_id)) {
    $conditions[] = "sm.id = :scheme_id";
    $params['scheme_id'] = $scheme_id;
}

if (!empty($chit_amount)) {
    $conditions[] = "ct.chit_amount = :chit_amount";
    $params['chit_amount'] = $chit_amount;
}

if (!empty($user_id)) {
    $conditions[] = "pay.user_id = :user_id";
    $params['user_id'] = $user_id;
}

if (!empty($pay_status)) {
    $conditions[] = "pay.payment_status = :status";
    $params['status'] = $pay_status;
}
if (!empty($chit_status)) {
    $conditions[] = "uct.status = :chit_status";
    $params['chit_status'] = $chit_status;
}

if (!empty($start_at)) {
    $conditions[] = "DATE(pay.transaction_date) >= :start_at";
    $params['start_at'] = $start_at;
}

if (!empty($end_at)) {
    $conditions[] = "DATE(pay.transaction_date) <= :end_at";
    $params['end_at'] = $end_at;
}

// Combine conditions
if (!empty($conditions)) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}

$pdo = db_connection();
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$paymentData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<?php
$pageTitle = 'Payment Details';
include './common/head.php';

$topbarTitle = 'Payment History';
$breadcrumbs = [
    ['title' => 'Home', 'url' => 'index.php'],
    ['title' => 'Payment History', 'url' => '']
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
                            <label for="" class="small text-muted ms-1" class="small text-muted ms-1">Scheme</label>
                            <select onchange="transactionFilter()" class="selectpicker" data-live-search="true" name=""
                                id="schemeFilter">
                                <option value="" selected>Select Scheme</option>
                                <?php foreach ($SchemeData as $scheme): ?>
                                    <?php
                                    $selected = ($scheme_id == $scheme['id']) ? 'selected="selected"' : '';
                                    ?>
                                    <option <?= $selected ?> value="<?= $scheme['id'] ?>"><?= $scheme['scheme_name'] ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="d-flex flex-column">
                            <label for="" class="small text-muted ms-1">Chit</label>
                            <select onchange="transactionFilter()" class=" selectpicker" data-live-search="true" name=""
                                id="chitFilter">
                                <option value="">Chit amount</option>
                                <?php foreach ($chitData as $chit): ?>
                                    <?php
                                    $selected = ($chit_amount == $chit['chit_amount']) ? 'selected="selected"' : '';
                                    ?>
                                    <option <?= $selected ?> value="<?= $chit['chit_amount'] ?>">
                                        <?= $chit['chit_amount'] ?> ₹
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="d-flex flex-column">
                            <label for="" class="small text-muted ms-1">Chit Status</label>
                            <select onchange="transactionFilter()" class="selectpicker " name="" id="chitStatusFilter">
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
                        <div class="d-flex flex-column">
                            <label for="" class="small text-muted ms-1">User</label>
                            <select onchange="transactionFilter()" class=" selectpicker" data-live-search="true" name=""
                                id="userFilter">
                                <option value="">Select User</option>
                                <?php foreach ($userChitData as $userChit): ?>
                                    <?php
                                    $selected = ($user_id == $userChit['user_id']) ? 'selected="selected"' : '';
                                    ?>
                                    <option <?= $selected ?> value="<?= $userChit['user_id'] ?>">
                                        <?= $userChit['user_name'] ?> -
                                        <?= $userChit['mobile'] ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="d-flex flex-column">
                            <label for="" class="small text-muted ms-1">Payment Status</label>
                            <select onchange="transactionFilter()" class="selectpicker " name=""
                                id="paymentStatusFilter">
                                <option value="">Payment Status</option>
                                <option <?= $pay_status == 'pending' ? 'selected="selected"' : '' ?> value="pending">
                                    pending</option>
                                <option <?= $pay_status == 'success' ? 'selected="selected"' : '' ?> value="success">
                                    Success</option>
                                <option <?= $pay_status == 'failed' ? 'selected="selected"' : '' ?> value="failed">Failed
                                </option>
                            </select>
                        </div>
                        <div class="d-flex flex-column">
                            <label for="" class="small text-muted ms-1">Start date</label>
                            <input max="<?php echo date('Y-m-d') ?>" onchange="transactionFilter()"
                                class="form-control datepicker" type="date" id="startDate" value="<?= $start_at ?>">
                        </div>
                        <div class="d-flex flex-column">
                            <label for="" class="small text-muted ms-1">End date</label>
                            <input max="<?php echo date('Y-m-d') ?>" onchange="transactionFilter()" class="form-control"
                                type="date" id="endDate" value="<?= $end_at ?>">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Scrollable Content -->
            <div class="content-scrollable card d-flex justify-content-between ">

                <div class="container  mb-5 p-4">

                    <div class="table-responsive">

                        <table id="userTable" datatable class="display table table-bordered table-striped table-hover "
                            style="width:100%">
                            <thead>
                                <tr class="text-nowrap">
                                    <th>S.No</th>
                                    <th>User Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Scheme Name</th>
                                    <th>Scheme Tenure</th>
                                    <th>Chit Number</th>
                                    <th>Chit Month</th>
                                    <th>Chit Year</th>
                                    <th>Chit Status</th>
                                    <th>Amount</th>
                                    <th>Payment date</th>
                                    <th>Payment Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($paymentData as $index => $payment): ?>
                                    <tr ng-repeat="user in jb.allUserList track by $index">
                                        <td><?= $index + 1 ?></td>
                                        <td class="text-capitalize"><?= $payment['user_name'] ?></td>
                                        <td><?= $payment['mobile'] ?></td>
                                        <td><?= $payment['email'] ?></td>
                                        <td><?= $payment['scheme_name'] ?></td>
                                        <td><?= $payment['scheme_tenure'] ?> Months</td>
                                        <td><?= $payment['chit_scheme_number'] ?></td>
                                        <td><?= $payment['chit_month'] ?></td>
                                        <td><?= $payment['chit_year'] ?></td>
                                        <td><?= $payment['user_chit_status'] ?></td>
                                        <td><?= $payment['amount'] ?> ₹</td>
                                        <td><?= $payment['transaction_date'] ?></td>
                                        <td><?= $payment['payment_status'] ?></td>
                                    <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php include './footerTop.php'; ?>
            </div>
        </div>
    </div>

</body>

<?php include './common/footer.php'; ?>

</html>