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
$pageTitle = 'FAQ';
include './common/head.php';
$topbarTitle = 'About Us';
$breadcrumbs = [
    ['title' => 'Home', 'url' => 'index.php'],
    ['title' => 'About us', 'url' => '']
];


$faq_list = [
    [
        "question" => "On maturity, am I eligible to avail the ongoing In-showroom promotion along with the discount under this scheme ?",
        "answer" => ["Yes, all ongoing discounts will be applicable"]
    ],
    [
        "question" => "What if I don't pay the monthly installments by the due date ?",
        "answer" => ["If the Installments are not paid on or before the due date, your total discount eligibility amount shall be reduced proportionately with respect to the number of days delayed."]
    ],
    [
        "question" => "Can I pay the overdue amount after scheme Maturity date ?",
        "answer" => ["No."]
    ],
    [
        "question" => "What If I pay before the monthly Installments are actually due ?",
        "answer" => ["No additional benefit can be given for depositing the installment before due dates."]
    ],
    [
        "question" => "How can I redeem ?",
        "answer" => ["You can purchase jewellery from any of the Prayaan Jewels Showrooms for an amount equal to or more than the installments paid plus discount under the scheme (purchase eligibility amount).", "The final purchase value should be equal to or in excess of purchase eligibility amount.", "Partial redemption of the scheme is not permitted."]
    ],
    [
        "question" => "What about the purity of Jewellery ?",
        "answer" => ["We sell Hallmarked Gold Jewellery from all our showrooms only and all our Diamond Jewellery is certified by Internationally Accredited Certifying agencies. Thus, be rest assured about the purity of the jewellery that you buy."]
    ],
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
            <div class="content-scrollable card border-0 d-flex flex-column justify-content-between">

                <div class="container p-5 text-muted ">
                    <h2 class="text-uppercase text-warning mb-3" style="text-shadow: 1px 1px 2px #00000047;">Frequently
                        Asked Questions
                    </h2>
                    <div class="accordion" id="smoothAccordion">
                        <?php foreach ($faq_list as $index => $faq): ?>
                            <div class="accordion-item mb-2">
                                <h2 class="accordion-header " id="headingOne">
                                    <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse<?= $index ?>">
                                        <?= $faq['question'] ?>
                                    </button>
                                </h2>
                                <div id="collapse<?= $index ?>" class="accordion-collapse collapse "
                                    data-bs-parent="#smoothAccordion">
                                    <?php foreach ($faq['answer'] as $list): ?>
                                        <div class="accordion-body">
                                            <?= $list ?>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        <?php endforeach ?>

                    </div>
                </div>

                <?php include './footerTop.php'; ?>
            </div>

        </div>
    </div>

</body>

<?php include './common/footer.php'; ?>

</html>