<?php

require './functions/middleware.php';
require './config/db.php';

$authData = auth_protect();
$authUserId = $authData['id'];


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
    sm.scheme_name AS scheme_name,
    sm.scheme_tenure AS scheme_tenure,
    uct.chit_scheme_number 
    FROM pr_payments AS pay
    LEFT JOIN pr_userchits AS uct ON uct.id = pay.user_chit_id
    LEFT JOIN pr_chits AS ct ON ct.id = uct.scheme_amt_id 
    LEFT JOIN pr_schemes AS sm ON sm.id = uct.chit_scheme_id  
    WHERE pay.user_id = :user_id";

$pdo = db_connection();
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $authUserId]);
$paymentData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<?php
$pageTitle = 'Dashboard';
include './common/head.php';

$topbarTitle = 'Chit List';
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

            <!-- Scrollable Content -->
            <div class="content-scrollable card border-0">

                <div class="container my-5">
                    <table id="userTable" datatable class="display table " style="width:100%">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Scheme Name</th>
                                <th>Scheme Tenure</th>
                                <th>Chit Number</th>
                                <th>Chit Month</th>
                                <th>Chit Year</th>
                                <th>Amount</th>
                                <th>Payment date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($paymentData as $index => $payment): ?>
                                <tr ng-repeat="user in jb.allUserList track by $index">
                                    <td><?= $index + 1 ?></td>
                                    <td class="text-capitalize"><?= $payment['scheme_name'] ?></td>
                                    <td><?= $payment['scheme_tenure'] ?> Months</td>
                                    <td><?= $payment['chit_scheme_number'] ?></td>
                                    <td><?= $payment['chit_month'] ?></td>
                                    <td><?= $payment['chit_year'] ?></td>
                                    <td><?= $payment['amount'] ?> ₹</td>
                                    <td><?= $payment['transaction_date'] ?></td>
                                    <td><?= $payment['payment_status'] ?></td>
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