<!-- views/home.php -->
<?php
require './functions/middleware.php';

auth_check()
    ?>
<!DOCTYPE html>
<html>

<?php
$pageTitle = 'Login';
include './common/head.php'; ?>

<body ng-app="myApp" ng-controller="MyController as jb" class="bg-light">
    <div class="d-flex align-items-center justify-content-center min-vh-100 left-panel rounded-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card rounded-4 shadow overflow-hidden">
                        <div class="row g-0">

                            <!-- Left Panel -->
                            <div class="col-md-6 d-none d-md-flex flex-column justify-content-between left-panel p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="text-white">Forgot Password</h6>
                                </div>
                            </div>

                            <!-- Right Panel -->
                            <div class="col-md-6 bg-white p-5">
                                <h3 class="fw-bold mt-3 text-center">JB JEWELLERS</h3>
                                <p class="text-muted text-center"></p>

                                <form id="forgotPasswordForm">
                                    <div class="mb-3">
                                        <!-- <input type="email" name="email" class="form-control rounded-pill"
                                            placeholder="Email" required> -->
                                        <input type="text" name="email" id="email" class="form-control rounded-pill"
                                            placeholder="Enter email address">
                                        <p class="m-1 text-danger error-message"></p>
                                    </div>

                                    <button class="btn btn-danger w-100 rounded-pill mt-2" type="submit"
                                        onclick="forgotPassword(event,'forgotPasswordForm')">
                                        <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                                        <span class="d-flex justify-content-center align-items-center" role="status"><i class="fa-solid fa-envelope me-2"></i>Send reset email</span>
                                    </button>
                                </form>

                                <p class="text-center mt-3 small">
                                    Back to <a href="login.php" class="text-decoration-none">Sign
                                        in</a>
                                </p>

                                <div class="text-center mt-4">
                                    <a href="#"><i class="bi bi-facebook me-3"></i></a>
                                    <a href="#"><i class="bi bi-twitter me-3"></i></a>
                                    <a href="#"><i class="bi bi-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<?php include './common/footer.php'; ?>

</html>