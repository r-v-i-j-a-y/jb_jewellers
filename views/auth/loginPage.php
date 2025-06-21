<!-- views/home.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="../../css/lib/bootstrap.min.css">
</head>

<body ng-app="myApp" ng-controller="MyController as jb">
    <div>
        <h1>Login </h1>
        <form action="" id="loginForm">
            <label for="mobile">Mobile</label>
            <input type="text" name="mobile" id="mobile">
            <p class="m-0 text-danger error-message"></p>

            <label for="password">password</label>
            <input type="text" name="password" id="password">
            <p class="m-0 text-danger error-message"></p>

            <button ng-click="jb.loginSubmit($event,'loginForm')">Login</button>
        </form>
    </div>
</body>

<?php include __DIR__ . '../../common/footer.php'; ?>

</html>