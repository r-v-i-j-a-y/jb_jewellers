<!-- views/home.php -->
<?php
require './functions/middleware.php';

auth_check();


require_once './config/db.php';

$pdo = db_connection();

$token = isset($_GET['token']) ? $_GET['token'] : '';
$token_err = '';

if (!empty($token)) {
    $token_hash = md5($token);

    $stmt = $pdo->prepare("SELECT * FROM pr_password_reset_temp WHERE tokenkey = :token_hash");
    $stmt->execute(['token_hash' => $token_hash]);
    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $user_email = $user['email'];
       
        if (strtotime($user['expDate']) <= time()) {
            $token_err = "Token has expired";
        }
    } else {
        $token_err = "Token not found";
    }
} else {
    $token_err = "No token provided";
}

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
                                    <h6 class="text-white">Reset Password</h6>
                                </div>
                            </div>

                            <!-- Right Panel -->
                            <div class="col-md-6 bg-white p-5">
                                <h3 class="fw-bold mt-3 text-center">JB JEWELLERS</h3>
                                <p class="text-muted text-center"></p>
                                <?php if ($token_err == ''): ?>
                                    <form id="resetPasswordForm">
                                        <input hidden type="text" name="email" value="<?= $user_email ?>">
                                        <div class="mb-3">
                                            <input type="password" name="password" id="password"
                                                class="form-control rounded-pill" placeholder="Enter new password">
                                            <p class="m-1 text-danger error-message"></p>
                                        </div>
                                        <div class="mb-3">

                                            <input type="password" name="password_confirmation" id="password_confirmation"
                                                class="form-control rounded-pill" placeholder="Re enter password">
                                            <p class="m-1 text-danger error-message"></p>
                                        </div>

                                        <button class="btn btn-success w-100 rounded-pill mt-2" type="submit"
                                            onclick="resetPassword(event,'resetPasswordForm')">
                                            <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                                            <span class="" role="status">Reset password</span>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <p class="text-danger text-center"><?php echo $token_err ?></p>
                                <?php endif ?>

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