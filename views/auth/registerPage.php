<!-- views/home.php -->
<!DOCTYPE html>
<html>

<?php includeWithData(__DIR__ . '/../common/head.php', [
    'title' => 'Register',
]);
?>

<body ng-app="myApp" ng-controller="MyController as jb">
    <!-- <div>
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
    </div> -->
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

                                <form id="registerForm">
                                    <div class="mb-3">
                                        <input type="text" name="user_name" id="name" class="form-control rounded-pill"
                                            placeholder="Your Name" >
                                        <p class="m-1 text-danger error-message"></p>
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="email" id="email" class="form-control rounded-pill"
                                            placeholder="Email Address" onkeypress="return event.charCode != 32">
                                        <p class="m-1 text-danger error-message"></p>
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="mobile" id="mobile" class="form-control rounded-pill"
                                            placeholder="Mobile Number" >
                                        <p class="m-1 text-danger error-message"></p>
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" name="password" id="password"
                                            class="form-control rounded-pill" placeholder="Password" onkeypress="return event.charCode != 32">
                                        <p class="m-1 text-danger error-message"></p>
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="password_confirmation" id="cnf-password"
                                            class="form-control rounded-pill" placeholder="Confirm Password" onkeypress="return event.charCode != 32">
                                        <p class="m-1 text-danger error-message"></p>
                                    </div>
   

                                    <button class="btn btn-primary w-100 rounded-pill mt-3"
                                        ng-click="jb.registerSubmit($event,'registerForm')">Register</button>
                                </form>

                                <p class="text-center mt-3 small">
                                    Already have an account? <a href="/login" class="text-decoration-none">Sign in</a>
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

<?php include __DIR__ . '../../common/footer.php'; ?>

</html>