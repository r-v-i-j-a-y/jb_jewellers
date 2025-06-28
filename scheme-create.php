<?php

require './functions/middleware.php';
require './config/db.php';
$authData = auth_protect();
$authUserId = $authData['id'];
$isAdmin = ($authData['role_id'] == 1) ? true : false;
$pdo = db_connection();
?>

<!DOCTYPE html>
<html>
<?php
$pageTitle = 'Create Scheme';
include './common/head.php';

$topbarTitle = 'Create Scheme';
$breadcrumbs = [
    ['title' => 'Home', 'url' => 'index.php'],
    ['title' => 'Schemes', 'url' => 'schemes.php'],
    ['title' => 'Create Scheme', 'url' => '']
]; ?>

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
                    <form id="schemeCreateForm" class="card p-4 shadow form-section mt-5">
                        <div class="mb-3">
                            <strong>Create Scheme</strong>
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
                            <!-- <input hidden type="text" id="user_id" name="user_id" class="form-control p-2"
                                ng-model="jb.selectUserDetilas.id"> -->
                            <div class="col-md-6 ">
                                <label for="scheme_name">Scheme Name</label>
                                <input type="text" id="scheme_name" name="scheme_name" class="form-control p-2">
                                <p class="text-danger error-message m-0"></p>
                            </div>

                            <div class="col-md-6">
                                <label for="scheme_tenure">Scheme Tenure</label>
                                <input type="number" id="scheme_tenure" name="scheme_tenure" class="form-control p-2">
                                <p class="text-danger error-message m-0"></p>
                            </div>

                            <!-- <div class="col-md-6 col-lg-4">
                                <label for="scheme_status">Scheme Status</label>
                                <input type="date" id="scheme_status" name="scheme_status" class="form-control p-2"
                                    ng-model="jb.selectUserDetilas.scheme_status">
                                <p class="text-danger error-message m-0"></p>
                            </div> -->
                        </div>

                        <!-- Submit Button -->
                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-primary"
                                onclick="schemeCreate(event, 'schemeCreateForm')">
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