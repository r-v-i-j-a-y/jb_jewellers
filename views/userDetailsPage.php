<!-- views/home.php -->
<?php
include __DIR__ . '/../services/sessionData.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>User Details</title>
    <link rel="stylesheet" href="../css/lib/bootstrap.min.css">
</head>

<body ng-app="myApp" ng-controller="MyController as jb">
    <div>
        <h1>user details</h1>
        <form id="userDetailsForm">
            <div <?php if ($auth_user_role_id == 1) { ?> hidden <?php } ?>>
                <label for="user_id">Select User</label>
                <select name="user_id" id="user_id">
                    <option value="<?php echo $auth_user_id ?>" selected>Auth user</option>
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
    </div>
</body>

<?php include __DIR__ . '../common/footer.php'; ?>


</html>