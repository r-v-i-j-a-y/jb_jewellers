<?php
require './config/db.php';
require './services/session.php';
require './models/userDetailsModal.php';
require './services/validator.php';
require './services/response.php';
require_once './services/authentication.php';
class UserdetailsController extends SessionData
{
    public function index()
    {
        if (AuthMiddleware::handle()) {
            require "views/homePage.php";
            exit;
        }
        require "views/userdetailsPage.php";
    }

    public function update()
    {

        $pdo = DB::connection();

        $userDetailsModel = new UserDetailsModel($pdo);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $data = $_POST;
            $data['anniversary'] = !empty($data['anniversary']) ? $data['anniversary'] : null;
            $data['address2'] = !empty($data['address2']) ? $data['address2'] : null;
            $validator = new Validator($_POST);

            $validator->required('first_name');
            $validator->required('last_name');
            $validator->required('last_name');
            $validator->required('dob');
            $validator->required('address1');
            $validator->required('city');
            $validator->required('state');
            $validator->required('pincode');
            $validator->required('pan_number');
            $validator->unique('pan_number', 'user_details', 'pan_number', $pdo, $this->auth_user_id, 'user_id');

            $validator->required('aadhaar_number');
            $validator->numeric('aadhaar_number');
            $validator->unique('aadhaar_number', 'user_details', 'aadhaar_number', $pdo ,$this->auth_user_id, 'user_id');

            $validator->required('nominee');
            $validator->required('nominee_relation');

            $validator->required('user_id');
            $validator->numeric('user_id');

            if (!$validator->passes()) {
                Response::error('Validation failed', $validator->errors(), 422);
            }

            try {
                $updateUserDetails = $userDetailsModel->update($data, ['user_id' => $data['user_id']]);

                if ($updateUserDetails) {
                    Response::success('User Details Updated Sccessfully', $updateUserDetails);
                }
            } catch (PDOException $e) {

                Response::error('Database error occurred.', ['database' => [$e->getMessage()]], 500);
            }


        }


    }

}
