<?php

require './functions/middleware.php';
require './config/db.php';

$authData = auth_protect();
$authUserId = $authData['id'];
$isAdmin = ($authData['role_id'] == 1) ? true : false;


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
$pageTitle = 'Create Chit';
include './common/head.php';

$scheme_id = $_GET['scheme_id'];
$topbarTitle = 'Create Chit';
$breadcrumbs = [
    ['title' => 'Home', 'url' => 'index.php'],
    ['title' => 'Schemes', 'url' => 'schemes.php'],
    ['title' => $schemeData[0]['scheme_name'], 'url' => "chits.php?scheme_id=$scheme_id"],
    ['title' => 'Create Chit', 'url' => '']
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
                <div class="container">
                    <form id="chitCreateForm" class="card p-4 shadow form-section mt-5">
                        <div class="mb-3 ">
                            <strong>Create Chit</strong>
                        </div>

                        <div class="row g-4">
                            <!-- Select User -->
                            <!-- <div class="col-md-4">
                                <label for="selectUserId">Select User</label>
                                <select name="user_id" id="selectUserId" class="form-select p-2">
                                    <option value="" selected>select user</option>
                                    <option ng-repeat="user in jb.allUserList track by user.id" value="{{user.id}}">
                                        {{user.mobile}} - {{user.user_name}}
                                    </option>
                                </select>
                                <p class="text-danger error-message m-0"></p>
                            </div> -->
                            <input hidden type="text" id="scheme_id" name="scheme_id" class="form-control p-2"
                                value="<?php echo $_GET['scheme_id'] ?>">
                            <div class="col-md-6 ">
                                <label for="chit_amount">Chit Amount</label>
                                <input type="number" id="chit_amount" name="chit_amount" class="form-control p-2">
                                <p class="text-danger error-message m-0"></p>
                            </div>

                            <!-- <div class="col-md-6">
                                <label for="scheme_tenure">Scheme Tenure</label>
                                <input type="number" id="scheme_tenure" name="scheme_tenure" class="form-control p-2">
                                <p class="text-danger error-message m-0"></p>
                            </div> -->

                            <!-- <div class="col-md-6 col-lg-4">
                                <label for="scheme_status">Scheme Status</label>
                                <input type="date" id="scheme_status" name="scheme_status" class="form-control p-2"
                                    ng-model="jb.selectUserDetilas.scheme_status">
                                <p class="text-danger error-message m-0"></p>
                            </div> -->
                        </div>

                        <!-- Submit Button -->
                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-warning" onclick="chitCreate(event, 'chitCreateForm')">
                                <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                                <span class="" role="status">Create</span>
                            </button>
                        </div>
                    </form>
                </div>
                  <?php include './footerTop.php'; ?>
            </div>
        </div>
    </div>

</body>

<?php include './common/footer.php'; ?>

</html>