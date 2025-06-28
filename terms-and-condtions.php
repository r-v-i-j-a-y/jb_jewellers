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
$pageTitle = 'Terms and Conditions';
include './common/head.php';
$topbarTitle = 'About Us';
$breadcrumbs = [
    ['title' => 'Home', 'url' => 'index.php'],
    ['title' => 'About us', 'url' => '']
];

$termList = [
    "Tara-Jewellery plan scheme will enable customers to plan their jewellery purchase.",
    "Customer can choose with minimum monthly scheme value of Rs. 1000/- (Rupees One Thousand only) per month & per customer and can increase by multiples of Rs. 1000/-.",
    "Customer should fill the application form and attach the necessary KYC document to enrol. Documents like (a) Voters ID (b) Aadhaar Card (c) Pancard (d) Passport Copy (e) Driving License are accepted.",
    "Customer may appoint nominee on submission of KYC details for self and the nominee. Guardian to sign the application, in case of the applicant is minor.",
    "If the maturity value exceeds Rs. 2 Lakhs & above, customer should submit the PAN Card Copy.",
    "Future monthly payment shall be made either by Cash/Debit/Credit Cards/NEFT/RTGS & UPI.",
    "Monthly payment to be paid on or before 15th of every month, in case if the payment not able to do continuously, company will allow the applicant to make subsequent month on one time only.",
    "On receipt of the payment, a statement will be printed either by pass book or will be sent through whats app.",
    "Customer who choose to discontinue within 6 months, will not be eligible for any scheme discount.",
    "In case customer would like to redeem the scheme between 7 to 10 months, customer is entitled for the scheme discount proportionately calculated on pro rate basis.",
    "The monthly payment against jewellery purchase plan must be equal and paid continuously for 11 months. The payment cannot be extended for more than 11 months and it is not transferrable under any circumstances.",
    "Customer will not be eligible for multiple jewellery purchase plan account for single jewellery.",
    "Customer to bring the pass book while making the monthly payment and should be surrendered at the time of redemption.",
    "Customer shall purchase jewellery for the redemption value. In case the jewellery purchase is less than the redemption value, there will not be any cash refund to the customer and can be adjusted for future purchases.",
    "Customer shall not be eligible to purchase gold & silver coins, bars, solid/semi-solid idols, frames and silver articles under this scheme.",
    "GST & any other government levies at the time of purchase should be borne by the customer.",
    "Customer signature will be verified at the time of redemption.",
    "Scheme shall be redeemed for Gold Jewellery, Diamond Jewellery, Silver Jewellery and Electro form idols only.",
    "Company at its sole discretion shall alter, modify, amend, add or remove any terms and conditions.",
    "All disputes are subject to exclusive jurisdiction of the competent courts in Chennai only.",
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
                    <div class="mb-5">
                        <h2 class="text-uppercase text-warning text-center mb-5" style="text-shadow: 1px 1px 2px #00000047;">TARA -
                            GOLDEN SCHEME / Terms & Conditions</h2>
                        <ul>
                            <?php foreach ($termList as $term): ?>
                                <li class="mb-3 text-justify"><?= $term ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                </div>
                <?php include './footerTop.php'; ?>
            </div>
        </div>
    </div>

</body>

<?php include './common/footer.php'; ?>

</html>