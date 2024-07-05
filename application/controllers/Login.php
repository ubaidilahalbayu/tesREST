<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Login extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Load model 
        $this->load->model(array('user_Model'));
        $this->load->library('Authorization_Token');
    }

    public function index_get()
    {
        $id = $this->get('id');
        $userData = $this->user_Model->get_user_where(array("id" => $id));
        $tokenData = $this->authorization_token->generateToken($userData);
        $final = array();
        $final['access_token'] = $tokenData;
        $final['status'] = true;

        $this->response($final, RESTController::HTTP_OK);
    }

    // public function index_delete()
    // {
    //     $headers = $this->input->request_headers();
    //     if (isset($headers['Authorization'])) {
    //         $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
    //         if ($decodedToken['status']) {
    //             $user = $decodedToken['data'];
    //             $this->response($user, RESTController::HTTP_OK);
    //         } else {
    //             $this->response($decodedToken, RESTController::HTTP_UNAUTHORIZED);
    //         }
    //     } else {
    //         $this->response(['Authentication failed'], RESTController::HTTP_UNAUTHORIZED);
    //     }
    // }
}