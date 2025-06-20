<!-- views/home.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link rel="stylesheet" href="../../css/lib/bootstrap.min.css">
</head>

<body ng-app="myApp" ng-controller="MyController as jb">
    <div>
        <h1>Register </h1>
        <form action="" id="registerForm">
            <label for="name">Name</label>
            <input type="text" name="user_name" id="name">
            <p class="m-0 text-danger error-message"></p>

            <label for="email">Email</label>
            <input type="text" name="email" id="email">
            <p class="m-0 text-danger error-message"></p>

            <label for="mobile">Mobile</label>
            <input type="text" name="mobile" id="mobile">
            <p class="m-0 text-danger error-message"></p>

            <label for="password">password</label>
            <input type="text" name="password" id="password">
            <p class="m-0 text-danger error-message"></p>

            <label for="cnf-password">confirm password</label>
            <input type="text" name="password_confirmation" id="cnf-password">
            <p class="m-0 text-danger error-message"></p>

            <button ng-click="jb.registerSubmit($event,'registerForm')">register</button>
        </form>
    </div>
</body>

<?php include __DIR__ . '../../common/footer.php'; ?>

</html>