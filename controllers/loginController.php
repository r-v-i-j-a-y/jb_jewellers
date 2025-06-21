<?php
require_once './models/userModal.php';
require_once './services/response.php';
require_once './services/session.php';
require_once 'config/constants.php';
require_once './config/db.php';
require_once './services/authentication.php';
class LoginController extends SessionData
{
    public function index()
    {
        if (AuthChechMiddleware::handle()) {
            require_once "views/homePage.php";
            exit;
        }
        require_once "views/auth/loginPage.php";
    }

    public function login()
    {
        $pdo = DB::connection();

        $userModel = new UserModel($pdo);

        $data = $_POST;

        $user = $userModel->find(['mobile' => $data['mobile']]);

        if ($user) {
            if (password_verify($data['password'], $user['password'])) {

                unset($user['password']);

                Session::set('auth', $user);

                Response::success('login successful');
            } else {
                Response::error('Password faild', 'password is not match', 422);
            }
        } else {
            Response::error('User faild', 'mobile number not found', 422);
        }

        print_r($user);

    }

}
