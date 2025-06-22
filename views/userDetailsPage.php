<!-- views/home.php -->

<!DOCTYPE html>
<html>

<?php includeWithData(__DIR__ . '../common/head.php', [
    'title' => 'User Details',
]);
?>

<body ng-app="myApp" ng-controller="MyController as jb">

    <div ng-init="jb.commonInit(<?= htmlspecialchars(json_encode($this->auth), ENT_QUOTES, 'UTF-8') ?>)">
        <!-- Sidebar -->
        <?php include __DIR__ . '../common/sideBar.php'; ?>

        <!-- Main Content Area -->
        <div class="main-container">
            <!-- Fixed Topbar -->
            <?php include __DIR__ . '../common/topBar.php'; ?>

            <!-- Scrollable Content -->
            <div class="content-scrollable">
                <div class="container my-5">
                    <form id="userDetailsForm" class="card p-4 shadow form-section">
                        <div class="mb-3">
                            <strong>User Role ID:</strong> <?= $this->auth_user_role_id ?> &nbsp; | &nbsp;
                            <strong>User ID:</strong> <?= $this->auth_user_id ?>
                        </div>

                        <div class="row g-4">
                            <!-- Select User -->
                            <div class="col-md-4">
                                <label for="selectUserId">Select User</label>
                                <select name="user_id" id="selectUserId" class="form-select p-2">
                                    <option value="" selected>select user</option>
                                    <option ng-repeat="user in jb.allUserList track by user.id" value="{{user.id}}">
                                        {{user.mobile}} - {{user.user_name}}
                                    </option>
                                </select>
                                <p class="text-danger error-message m-0"></p>
                            </div>

                            <div class="col-md-4">
                                <label for="first_name">First Name</label>
                                <input type="text" id="first_name" name="first_name" class="form-control p-2">
                                <p class="text-danger error-message m-0"></p>
                            </div>

                            <div class="col-md-4">
                                <label for="last_name">Last Name</label>
                                <input type="text" id="last_name" name="last_name" class="form-control p-2">
                                <p class="text-danger error-message m-0"></p>
                            </div>

                            <div class="col-md-4">
                                <label for="dob">Date of Birth</label>
                                <input type="date" id="dob" name="dob" class="form-control p-2">
                                <p class="text-danger error-message m-0"></p>
                            </div>

                            <div class="col-md-4">
                                <label for="anniversary">Anniversary</label>
                                <input type="date" id="anniversary" name="anniversary" class="form-control p-2">
                                <p class="text-danger error-message m-0"></p>
                            </div>

                            <div class="col-md-4">
                                <label for="address1">Address 1</label>
                                <input type="text" id="address1" name="address1" class="form-control p-2">
                                <p class="text-danger error-message m-0"></p>
                            </div>

                            <div class="col-md-4">
                                <label for="address2">Address 2</label>
                                <input type="text" id="address2" name="address2" class="form-control p-2">
                                <p class="text-danger error-message m-0"></p>
                            </div>

                            <div class="col-md-4">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" class="form-control p-2">
                                <p class="text-danger error-message m-0"></p>
                            </div>

                            <div class="col-md-4">
                                <label for="state">State</label>
                                <input type="text" id="state" name="state" class="form-control p-2">
                                <p class="text-danger error-message m-0"></p>
                            </div>

                            <div class="col-md-4">
                                <label for="pincode">Pincode</label>
                                <input type="text" id="pincode" name="pincode" class="form-control p-2">
                                <p class="text-danger error-message m-0"></p>
                            </div>

                            <div class="col-md-4">
                                <label for="pan_number">PAN Number</label>
                                <input type="text" id="pan_number" name="pan_number" class="form-control p-2">
                                <p class="text-danger error-message m-0"></p>
                            </div>

                            <div class="col-md-4">
                                <label for="aadhaar_number">Aadhaar Number</label>
                                <input type="text" id="aadhaar_number" name="aadhaar_number" class="form-control p-2">
                                <p class="text-danger error-message m-0"></p>
                            </div>

                            <div class="col-md-4">
                                <label for="nominee">Nominee</label>
                                <input type="text" id="nominee" name="nominee" class="form-control p-2">
                                <p class="text-danger error-message m-0"></p>
                            </div>

                            <div class="col-md-4">
                                <label for="nominee_relation">Nominee Relation</label>
                                <input type="text" id="nominee_relation" name="nominee_relation" class="form-control p-2">
                                <p class="text-danger error-message m-0"></p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-primary"
                                ng-click="jb.userDetailsUpdate($event, 'userDetailsForm')">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
                <!-- <div
                    ng-init="jb.commonInit(<?= htmlspecialchars(json_encode($this->auth), ENT_QUOTES, 'UTF-8') ?>);jb.userDetailsInit(<?= htmlspecialchars(json_encode($userDetails), ENT_QUOTES, 'UTF-8') ?>)">
                    <h1>user details</h1>
                    <form id="userDetailsForm">
                        <?php echo $this->auth_user_role_id;
                        echo $this->auth_user_id ?>
                        <div>
                            <label for="selectUserId">Select User</label>
                            <select name="user_id" id="selectUserId">
                                <option value="" selected>select user</option>
                                <option ng-repeat="user in jb.allUserList track by user.id" value="{{user.id}}">
                                    {{user.mobile}} -
                                    {{user.user_name}}
                                </option>
                            </select>
                            <p class="m-0 text-danger error-message"></p>
                        </div>

                        <p class="m-0 text-danger error-message"></p>

                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name">
                        <p class="m-0 text-danger error-message"></p>

                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name">
                        <p class="m-0 text-danger error-message"></p>

                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob">
                        <p class="m-0 text-danger error-message"></p>

                        <label for="anniversary">Anniversary</label>
                        <input type="date" id="anniversary" name="anniversary">
                        <p class="m-0 text-danger error-message"></p>

                        <label for="address1">address1</label>
                        <input type="text" id="address1" name="address1">
                        <p class="m-0 text-danger error-message"></p>

                        <label for="address2">address2</label>
                        <input type="text" id="address2" name="address2">
                        <p class="m-0 text-danger error-message"></p>

                        <label for="city">city</label>
                        <input type="text" id="city" name="city">
                        <p class="m-0 text-danger error-message"></p>

                        <label for="state">state</label>
                        <input type="text" id="state" name="state">
                        <p class="m-0 text-danger error-message"></p>

                        <label for="pincode">pincode</label>
                        <input type="text" id="pincode" name="pincode">
                        <p class="m-0 text-danger error-message"></p>

                        <label for="pan_number">pan_number</label>
                        <input type="text" id="pan_number" name="pan_number">
                        <p class="m-0 text-danger error-message"></p>

                        <label for="aadhaar_number">aadhaar_number</label>
                        <input type="text" id="aadhaar_number" name="aadhaar_number">
                        <p class="m-0 text-danger error-message"></p>

                        <label for="nominee">nominee</label>
                        <input type="text" id="nominee" name="nominee">
                        <p class="m-0 text-danger error-message"></p>

                        <label for="nominee_relation">nominee_relation</label>
                        <input type="text" id="nominee_relation" name="nominee_relation">
                        <p class="m-0 text-danger error-message"></p>

                        <button ng-click="jb.userDetailsUpdate($event,'userDetailsForm')">Update</button>
                    </form>
                </div> -->
            </div>
        </div>
    </div>

</body>

<?php include __DIR__ . '../common/footer.php'; ?>


</html>