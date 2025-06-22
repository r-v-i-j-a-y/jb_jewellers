<!-- views/home.php -->
<?php

?>
<!DOCTYPE html>
<html>

<?php includeWithData(__DIR__ . '../common/head.php', [
    'title' => 'Home',
]);

?>

<body ng-app="myApp" ng-controller="MyController as jb" class="bg-light">
    <div ng-init="jb.commonInit(<?= htmlspecialchars(json_encode($this->auth), ENT_QUOTES, 'UTF-8') ?>)">
        <!-- Sidebar -->
        <?php include __DIR__ . '../common/sideBar.php'; ?>

        <!-- Main Content Area -->
        <div class="main-container">
            <!-- Fixed Topbar -->
            <?php include __DIR__ . '../common/topBar.php'; ?>

            <!-- Scrollable Content -->
            <div class="content-scrollable">
                <div class="row g-4">
                    <!-- Cards and Charts go here -->
                </div>
            </div>
        </div>
    </div>

</body>

<?php include __DIR__ . '../common/footer.php'; ?>

</html>