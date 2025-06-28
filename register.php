<!-- views/home.php -->
<?php
require './functions/middleware.php';

auth_check()
    ?>
<!DOCTYPE html>
<html>

<?php
$pageTitle = 'Register';
include './common/head.php'; ?>

<body ng-app="myApp" ng-controller="MyController as jb">
    <div class="d-flex align-items-center justify-content-center min-vh-100 left-panel rounded-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card rounded-4 shadow overflow-hidden">
                        <div class="row g-0">

                            <!-- Left Panel -->
                            <div class="col-md-6 d-none d-md-flex flex-column justify-content-between left-panel p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="text-white">Register</h6>
                                </div>
                            </div>

                            <!-- Right Panel -->
                            <div class="col-md-6 bg-white p-5">
                                <h3 class="fw-bold mt-3 text-center">JB JEWELLERS</h3>
                                <p class="text-muted text-center">Welcome</p>

                                <?php include './registerForm.php'; ?>

                                <p class="text-center mt-3 small">
                                    Already have an account? <a href="login.php" class="text-decoration-none">Sign
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