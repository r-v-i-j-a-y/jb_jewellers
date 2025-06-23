<?php

require './config/db.php';
require './services/session.php';
require './models/userDetailsModal.php';
require './services/validator.php';
require './services/response.php';
require_once './services/authentication.php';
require_once './models/userModal.php';
require_once './models/schemeModel.php';
require_once './models/chitModel.php';
class ChitController extends SessionData
{
    public function index()
    {
        if (AuthMiddleware::handle()) {
            exit;
        }
        $data = $_GET;
        $pdo = DB::connection();
        $chitData = new ChitModel($pdo)->select(['id', 'chit_amount', 'scheme_id', 'chit_created_by', 'created_at', 'updated_at', 'status'])->where("scheme_id", '=', $data['scheme_id'])->get();
        // print_r($schemes);
        $this->view('chitsPage', ['chitData' => $chitData]);
    }

    public function create()
    {
        if (AuthMiddleware::handle()) {
            exit;
        }
        $this->view('chitCreatePage');
    }

    public function store()
    {
        $data = $_POST;
        $validator = new Validator($_POST);
        $validator->required('chit_amount');
        $validator->required('scheme_id');
        $pdo = DB::connection();
        $schemes = new ChitModel($pdo);

        if (!$validator->passes()) {
            Response::error('Validation failed', $validator->errors(), 200);
        }

        $isExist = $schemes->find(['scheme_id' => $data['scheme_id'], 'chit_amount' => $data['chit_amount']]);

        // print_r($isExist);
        if ($isExist) {
            Response::error('Chit Already Exists', $validator->errors(), 200);
        }
        try {
            $schemes->insert(['scheme_id' => $data['scheme_id'], 'chit_amount' => $data['chit_amount'], 'chit_created_by' => $this->auth_user_id]);

            Response::success('Chit Created successfully');

        } catch (PDOException $e) {

            Response::error('Database error occurred.', ['database' => [$e->getMessage()]], 500);
        }

    }

    public function status()
    {
        $data = $_POST;

        $validator = new Validator($_POST);
        $validator->required('id');

        if (!$validator->passes()) {
            Response::error('Validation failed', $validator->errors(), 200);
        }

        $pdo = DB::connection();
        $schemes = new ChitModel($pdo);

        $isExist = $schemes->find(['id' => $data['id']], ['first' => true]);

        if (isset($isExist)) {
            try {
                $newStatus = $isExist['status'] === 'active' ? 'inactive' : 'active';


                $schemes->update(['status' => $newStatus], ['id' => $data['id']]);

                Response::success('Chit ' . $newStatus . ' successfully');

            } catch (PDOException $e) {

                Response::error('Database error occurred.', ['database' => [$e->getMessage()]], 500);
            }


            // $isExist->update[];
        } else {
            Response::error('Scheme Not Exists', $validator->errors(), 200);

        }

        // print_r($data);


    }
}
