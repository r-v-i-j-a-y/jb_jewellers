<?php

require './functions/middleware.php';
require './config/db.php';

$authData = auth_protect();
$authUserId = $authData['id'];
$isAdmin = ($authData['role_id'] == 1) ? true : false;

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
$pageTitle = 'Refund Policy';
include './common/head.php';
$topbarTitle = 'About Us';
$breadcrumbs = [
    ['title' => 'Home', 'url' => 'index.php'],
    ['title' => 'About us', 'url' => '']
];

$refundPolicy = [
    ['title' => 'Infrastructure Consulting includes:', "description" => "Any unopened product still in factory-sealed packaging may be refunded or exchanged within 7 days of original purchase date. Original receipts are required and refunds will be given based on payment method."],
    ['title' => 'Opened Items:', "description" => "Opened products must be exchanged or returned for store credit with a 25% restocking fee. All opened products must be in like-new condition and with all packing materials and original receipts. No refunds or exchanges will be given after 7 days of purchase."],
    ['title' => 'Used/Refurbished Devices:', "description" => "Return and Refund not applicable for used/refurbished products."],
    ['title' => 'Defective Items:', "description" => "Defective items may be returned with original packing, <b> All defective items must be tested and verified before we are able to offer exchange or store credit. </b>"],
    ['title' => 'Non-returnable Products:', "description" => "Products not eligible for refund, return or exchange include opened Laptops and desktops , opened software, opened printers, opened ink/toner, special orders , labor, digital licenses & products not accompanied with original packaging."],
    ['title' => 'Cash Refunds:', "description" => "No in-store cash refunds will be made. We will refund cash purchases by check mailed to customer within 7-15 business days."],
    ['title' => 'Shipping:', "description" => "You are responsible for shipping the item back for a return. Please ship all returns to <b> PRAYAAN JEWEL MART. </b> The cost of shipping will be deducted from your refund amount."],
]

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

                <div class="container p-5 text-muted">
                    <h2 class="text-uppercase text-warning text-center mb-5"
                        style="text-shadow: 1px 1px 2px #00000047;">Cancellation & Refund Policy</h2>
                    <?php foreach ($refundPolicy as $policy): ?>
                        <div class="mb-4">
                            <h6 class="fw-bold"><?= $policy['title'] ?></h6>
                            <p><?= $policy['description'] ?></p>
                        </div>
                    <?php endforeach ?>
                </div>
                <?php include './footerTop.php'; ?>
            </div>
        </div>
    </div>

</body>

<?php include './common/footer.php'; ?>

</html>