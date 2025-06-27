<?php

require './functions/middleware.php';
require './config/db.php';

$authData = auth_protect();
$authUserId = $authData['id'];

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
$pageTitle = 'Dashboard';
include './common/head.php';
$topbarTitle = 'About Us';
$breadcrumbs = [
    ['title' => 'Home', 'url' => 'index.php'],
    ['title' => 'About us', 'url' => '']
];

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
                        <h2 class="text-uppercase text-warning" style="text-shadow: 1px 1px 2px #00000047;">From the
                            House of Suprabhath</h2>
                        <p class="text-justify " style="text-shadow: 1px 1px 1px #00000047;">Introducing Suprabhath
                            Constructions, where the art of construction
                            meets the power of
                            philosophy! For over two decades, we have been building remarkable residential and
                            commercial
                            spaces, leaving a lasting mark on the skyline with more than 75 projects. But we are not
                            just
                            another construction company. We believe that every space we create should reflect a deeper
                            meaning, a philosophy that resonates with its inhabitants.</p>
                        <p class="text-justify" style="text-shadow: 1px 1px 1px #00000047;">As a extention from the
                            construction industry, House of Suprabhath now
                            venture into Jewellery
                            industry in the name Prayaan Jewel Mart where it in houses different unique variety of
                            jewellery
                            designs at affordable price for all sector of people.</p>
                    </div>
                    <div class="mb-5">
                        <h2 class="text-uppercase text-warning" style="text-shadow: 1px 1px 2px #00000047;">About Our
                            Scheme</h2>
                        <p class="text-justify" style="text-shadow: 1px 1px 1px #00000047;"> As the name Tara (savings)
                            indicates this Scheme will help you to save
                            and make your journey
                            towards purchasing your auspicious goal in ease. Planning your dream purchase for any
                            occasion or to celebrate yourself is now an easy journey with our Tara Jewellery Purchase
                            Plan!</p>
                    </div>

                    <div class="mb-5">
                        <h5 class="text-uppercase text-warning text-center" style="text-shadow: 1px 1px 2px #00000047;">
                            JOURNEY
                            TOWARDS YOUR GOLDEN GOAL</h5>
                        <p class="text-justify" style="text-shadow: 1px 1px 1px #00000047;"> <b>Tara Jewellery Purchase
                                Plan</b> helps you to plan your dream
                            purchase with our curated
                            collection of silver, gold and diamond jewellery and articles. Our collections are curated
                            and designed with utmost cautiousness of our culture and clients in mind. Furthermore,
                            Prayaan Jewel Mart only offers 100% BIS Hallmarked Jewellery and the finest Internationally
                            Certified Diamonds. So rest assured, your purchase plan results will be the best of quality
                            and designs.</p>
                    </div>
                </div>
                <?php include './footerTop.php'; ?>
            </div>
        </div>
    </div>

</body>

<?php include './common/footer.php'; ?>

</html>