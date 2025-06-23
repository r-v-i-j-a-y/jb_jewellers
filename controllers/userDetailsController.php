<?php
require './config/db.php';
require './services/session.php';
require './models/userDetailsModal.php';
require './services/validator.php';
require './services/response.php';
require_once './services/authentication.php';
require_once './models/userModal.php';
class UserdetailsController extends SessionData
{
    public function index()
    {
        $pdo = DB::connection();
        if (AuthMiddleware::handle()) {
            require "views/homePage.php";
            exit;
        }
        $getData = $_GET;
        $userId = $getData['id'];
        $allUserDetails = new UserModel($pdo);


        // $data = $allUserDetails->select(['users.user_name', 'users.id', 'users.mobile', 'users.role_id', 'user_details.first_name', 'user_details.last_name', 'user_details.dob', 'user_details.anniversary', 'user_details.address1', 'user_details.address2', 'user_details.city', 'user_details.state', 'user_details.pincode', 'user_details.pan_number', 'user_details.aadhaar_number', 'user_details.nominee', 'user_details.nominee_relation', 'user_details.updated_by', 'user_details.updated_at'])->join('user_details', 'user_details.user_id', '=', 'users.id')->where('users.role_id', '!=', 1)->where('users.id', '=', $userId)->get();
        $data = $allUserDetails
            ->select([
                'users.user_name',
                'users.id',
                'users.mobile',
                'users.role_id',
                'user_details.first_name',
                'user_details.last_name',
                'user_details.dob',
                'user_details.anniversary',
                'user_details.address1',
                'user_details.address2',
                'user_details.city',
                'user_details.state',
                'user_details.pincode',
                'user_details.pan_number',
                'user_details.aadhaar_number',
                'user_details.nominee',
                'user_details.nominee_relation',
                'user_details.updated_by',
                'user_details.updated_at'
            ])
            ->leftJoin('user_details', 'user_details.user_id', '=', 'users.id')
            ->where('users.role_id', '!=', 1)
            ->where('users.id', '=', $userId)
            ->first();
        // $data = json_encode($data, true);
        // print_r($data);

        $this->view('userdetailsPage', ['userDetails' => $data]);

    }

    public function update()
    {

        $pdo = DB::connection();

        $userDetailsModel = new UserDetailsModel($pdo);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = $_POST;
            $userId = $data['user_id'];
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
            $validator->unique('pan_number', 'user_details', 'pan_number', $pdo, $userId, 'user_id');

            $validator->required('aadhaar_number');
            $validator->numeric('aadhaar_number');
            $validator->unique('aadhaar_number', 'user_details', 'aadhaar_number', $pdo, $userId, 'user_id');

            $validator->required('nominee');
            $validator->required('nominee_relation');

            $validator->required('user_id');
            $validator->numeric('user_id');

            if (!$validator->passes()) {
                Response::error('Validation failed', $validator->errors(), 200);
            }

            try {
                $updateUserDetails = $userDetailsModel->update($data, ['user_id' => $data['user_id']]);

                if ($updateUserDetails) {
                    Response::success('User Details Updated Sccessfully', $updateUserDetails);
                }
            } catch (PDOException $e) {

                Response::error('Database error occurred.', ['database' => [$e->getMessage()]], 200);
            }


        }


    }

    public function allUser()
    {
        $pdo = DB::connection();
        if (AuthMiddleware::handle()) {
            require "views/homePage.php";
            exit;
        }

        $allUserDetails = new UserModel($pdo);

        $data = $allUserDetails
            ->select([
                'users.user_name',
                'users.id',
                'users.mobile',
                'users.role_id',
                'user_details.first_name',
                'user_details.last_name',
                'user_details.dob',
                'user_details.anniversary',
                'user_details.address1',
                'user_details.address2',
                'user_details.city',
                'user_details.state',
                'user_details.pincode',
                'user_details.pan_number',
                'user_details.aadhaar_number',
                'user_details.nominee',
                'user_details.nominee_relation',
                'user_details.updated_by',
                'user_details.updated_at'
            ])
            ->leftJoin('user_details', 'user_details.user_id', '=', 'users.id')
            ->where('users.role_id', '!=', 1)
            ->get();

        $this->view('userPage', ['userDetails' => $data]);

    }

}
