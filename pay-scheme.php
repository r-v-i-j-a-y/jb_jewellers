<?php

require './functions/middleware.php';
require './config/db.php';
$env = parse_ini_file('.env');

$authData = auth_protect();
$authUserId = $authData['id'];
$isAdmin = ($authData['role_id'] == 1) ? true : false;

$pdo = db_connection();
$usersql = "
        SELECT 
            pr_users.user_name,
            pr_users.id,
            pr_users.mobile,
            pr_users.email,
            pr_users.role_id,
            pr_users.created_at,
            pr_users.status as user_status
        FROM pr_users
        WHERE pr_users.role_id != 1
    ";
$stmt1 = $pdo->prepare($usersql);
$stmt1->execute();
$userData = $stmt1->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT
        uct.id, 
        uct.userid, 
        uct.chit_scheme_id , 
        uct.scheme_amt_id , 
        uct.chit_scheme_number, 
        uct.start_date,
        uct.end_date,
        uct.status,
        uct.enable,
        uct.enable,
        uct.created_by,
        uct.created_by,
        uct.remarks,
        sm.scheme_name,
        sm.scheme_tenure,
        sm.scheme_status,
        sm.scheme_created_by,
        ct.chit_amount
        FROM pr_userchits as uct 
        LEFT JOIN pr_schemes as sm ON sm.id = uct.chit_scheme_id
        LEFT JOIN pr_chits as ct ON ct.id = uct.scheme_amt_id
        WHERE uct.userid = :userid
        ";

$getUserId = isset($_GET['user_id']) ? $_GET['user_id'] : null;
$params['userid'] = $isAdmin ? $getUserId : $authUserId;


$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$userChitData = $stmt->fetchAll(PDO::FETCH_ASSOC);


$paymentSql = "SELECT py.id, py.user_id, py.user_chit_id, py.chit_month, py.chit_year, py.amount, py.transaction_date, py.payment_id, py.payment_method, py.remarks, py.payment_status, py.created_at, py.payment_created_by, py.updated_at, py.payment_updated_by FROM pr_payments as py WHERE py.payment_status = 'success'";
$paymentStmt = $pdo->prepare($paymentSql);
$paymentStmt->execute();
$payments = $paymentStmt->fetchAll(PDO::FETCH_ASSOC);

$groupedPayments = [];
foreach ($payments as $pay) {
    $groupedPayments[$pay['user_chit_id']][] = $pay;
}

$combinedData = [];

foreach ($userChitData as $chit) {
    $chitId = $chit['id'];
    $combinedData[] = [
        'chit' => $chit,
        'payments' => $groupedPayments[$chitId] ?? [] // empty if no payments
    ];
}



// print_r($combinedData);

// Create a DateInterval of 1 month
$interval = new DateInterval('P1M');

?>

<!DOCTYPE html>
<html>
<?php
$pageTitle = 'Dashboard';
include './common/head.php';

$topbarTitle = 'Payment ';
$breadcrumbs = [
    ['title' => 'Home', 'url' => 'index.php'],
    ['title' => 'Pay Scheme', 'url' => '']
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

                <div class="container py-5">
                    <div class="col-md-4 mb-3 <?php echo !$isAdmin ? "visually-hidden" : "" ?>">
                        <label for="selectPayUserId">Select User</label>
                        <select onchange="paySchemeUserSelect()" name="user_id" id="selectPayUserId"
                            class="form-select p-2">
                            <option value="" selected>select user</option>
                            <?php foreach ($userData as $user): ?>
                                <?php
                                $selected = (!$isAdmin && $user['id'] == $authUserId) || ($isAdmin && $user['id'] == $_GET['user_id']) ? 'selected="selected"' : '';
                                ?>

                                <option value="<?= $user['id'] ?>" <?= $selected ?>>
                                    <?= $user['user_name'] ?> - <?= $user['mobile'] ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                        <p class="text-danger error-message m-0" id="selectPayUserIdError"></p>

                    </div>
                    <div class="row g-4">
                        <?php foreach ($combinedData as $userChit): ?>
                            <?php $jsonChit = htmlspecialchars(json_encode($userChit['chit']), ENT_QUOTES, 'UTF-8'); ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card shadow-sm border-0" style="background-color:rgba(220, 211, 255, 0.57);">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5>
                                                <?= $userChit['chit']['scheme_name'] ?> -
                                                <?= $userChit['chit']['chit_scheme_number'] ?>

                                            </h5>
                                            <h6
                                                class=" badge rounded-pill bg-light border m-0 p-1 text-capitalize <?php echo $userChit['chit']['status'] === "approved" ? "text-success" : "text-danger" ?>">
                                                <?= $userChit['chit']['status'] ?>
                                            </h6>
                                            <h5 class="card-title fw-bold text-center">
                                                <?= $userChit['chit']['chit_amount'] ?> ₹
                                            </h5>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <div>

                                                <p class="m-0 p-0  text-muted small"> Start date :
                                                    <?= $userChit['chit']['start_date'] ?>
                                                </p>
                                                <p class="m-0 p-0  text-muted small"> End date :
                                                    <?= $userChit['chit']['end_date'] ?>
                                                </p>
                                            </div>
                                            <p class="badge bg-success rounded-pill">
                                                <?= $userChit['chit']['scheme_tenure'] ?>
                                                Months
                                            </p>
                                        </div>


                                        <?php
                                        $startDate = new DateTime($userChit['chit']['start_date']);
                                        $endDate = new DateTime($userChit['chit']['end_date']);
                                        $period = new DatePeriod($startDate, $interval, $endDate);

                                        foreach ($period as $index => $date):
                                            $month = (int) $date->format('n'); // 1-12
                                            $year = (int) $date->format('Y');

                                            $status = 'pending';
                                            $badgeClass = 'secondary';

                                            foreach ($userChit['payments'] as $payment) {
                                                if ((int) $payment['chit_month'] === $month && (int) $payment['chit_year'] === $year) {
                                                    $status = $payment['payment_status'];
                                                    if ($status === 'success') {
                                                        $badgeClass = 'success';
                                                    } elseif ($status === 'failed') {
                                                        $badgeClass = 'danger';
                                                    } elseif ($status === 'pending') {
                                                        $badgeClass = 'warning';
                                                    }
                                                    break;
                                                }
                                            }
                                            ?>
                                            <div class="d-flex justify-content-between text-muted small border-bottom py-1">
                                                <span><?= $date->format('F') ?> - <?= $date->format('Y') ?></span>
                                                <span
                                                    class="badge rounded-pill bg-<?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                                            </div>
                                        <?php endforeach; ?>

                                        <!-- <?php if ($userChit['scheme_tenure'] > 11): ?>
                                            <div class="text-center mt-2">
                                                <button  class="btn btn-sm btn-outline-primary" id="toggleMoreBtn">Show
                                                    More</button>
                                            </div>
                                        <?php endif; ?> -->
                                        <?php if ($userChit['chit']['status'] === "approved"): ?>
                                            <?php if (count($userChit['payments']) != $userChit['chit']['scheme_tenure']): ?>
                                                <button
                                                    onclick="payChitModal(event,<?= $userChit['chit']['id'] ?>,<?= $userChit['chit']['chit_amount'] ?>, '<?= $userChit['chit']['scheme_name'] ?>','<?= $userChit['chit']['chit_scheme_number'] ?>')"
                                                    class="btn btn-primary rounded-pill w-100 mt-3">
                                                    <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                                                    <span class="" role="status">Pay</span>
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-success rounded-pill  w-100  mt-3">Completed </button>
                                            <?php endif ?>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="payChitModal" tabindex="-1" aria-labelledby="payChitModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="payChitModalLabel">Confirm to Pay</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body m-auto" id="paymentModalContent">
                                Are You Sure to pay
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary"
                                    onclick="confirmChitPayment(event,'<?= $env['RAZER_PAY_KEY_ID'] ?>','<?= $env['RAZER_PAY_SECRET'] ?>')">
                                    <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                                    <span class="" role="status">Yes, Change</span></button>
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