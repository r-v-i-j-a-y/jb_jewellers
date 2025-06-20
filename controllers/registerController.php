<?php

require_once './services/validator.php';
require_once './services/response.php';
require_once './config/db_connection.php';
require_once './config/db.php';
require_once './models/userModal.php';
require_once './models/userDetailsModal.php';


class RegisterController
{
    public function index()
    {
        $info = "This is the register page.";
        require_once "views/auth/registerPage.php";
    }

    public function store()
    {
        $userModel = new UserModel();
        $userDetailsModel = new UserDetailsModel();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = $_POST;
            $pdo = DB::connection();

            $validator = new Validator($_POST);

            $validator->required('user_name');
            $validator->required('email');
            $validator->email('email');
            $validator->unique('email', 'users', 'email', $pdo);

            $validator->required('password');
            $validator->min('password', 8);
            $validator->confirmed('password_confirmation', 'password');

            if (!$validator->passes()) {
                Response::error('Validation failed', $validator->errors(), 422);
            }

            try {
                $user_details = $userModel->create($data['user_name'], $data['email'], $data['password'], $data['mobile'], 2);

                $userDetailsModel->create($user_details["id"]);

                Response::success('Registration successful', $user_details);

            } catch (PDOException $e) {

                Response::error('Database error occurred.', ['database' => [$e->getMessage()]], 500);
            }

        }

    }
}
