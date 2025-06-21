<?php

require './services/validator.php';
require './services/response.php';
require './config/db.php';
require './models/userModal.php';
require './models/userDetailsModal.php';
require './services/session.php';
require_once './services/authentication.php';


class RegisterController extends SessionData
{
    public function index()
    {
        if (AuthChechMiddleware::handle()) {
            require "views/homePage.php";
            exit;
        }
        require "views/auth/registerPage.php";
    }

    public function store()
    {

        $pdo = DB::connection();

        $userModel = new UserModel($pdo);

        $userDetailsModel = new UserDetailsModel($pdo);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = $_POST;
            $data['role_id'] = 2;

            $pdo = DB::connection();

            $validator = new Validator($_POST);

            $validator->required('user_name');

            $validator->required('email');
            $validator->email('email');
            $validator->unique('email', 'users', 'email', $pdo);

            $validator->numeric('mobile');
            $validator->required('mobile');
            $validator->unique('mobile', 'users', 'mobile', $pdo);

            $validator->required('password');
            $validator->min('password', 8);
            $validator->confirmed('password_confirmation', 'password');

            if (!$validator->passes()) {
                Response::error('Validation failed', $validator->errors(), 422);
            }


            try {

                unset($data['password_confirmation']);

                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

                $user_details = $userModel->insert($data);

                unset($user_details['password']);

                $userDetailsModel->insert(['user_id' => $user_details['id']]);

                Session::set('auth', $user_details);

                Response::success('Registration successful', $user_details);

            } catch (PDOException $e) {

                Response::error('Database error occurred.', ['database' => [$e->getMessage()]], 500);
            }

        }

    }
}
