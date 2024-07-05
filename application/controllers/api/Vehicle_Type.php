<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Vehicle_Type extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Load model 
        $this->load->model(array('vehicle_Type_Model'));
        $this->load->library('Authorization_Token');
    }

    // GET USER
    public function index_get()
    {
        $headers = $this->input->request_headers();
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                $where_data = $this->get();
                $types = array();

                if ($where_data === null) {
                    $types = $this->vehicle_Type_Model->get_types();
                } else {
                    $types = $this->vehicle_Type_Model->get_type_where($where_data);
                }

                // Check if the types data store contains types
                if (count($types) > 0) {
                    // Set the response and exit
                    $this->response([
                        'status' => true,
                        'message' => 'Types available',
                        'data' => $types
                    ], RESTController::HTTP_OK);
                } else {
                    // Set the response and exit
                    $this->response([
                        'status' => false,
                        'message' => 'No types were found'
                    ], RESTController::HTTP_OK);
                }
            } else {
                $this->response($decodedToken, RESTController::HTTP_UNAUTHORIZED);
            }
        } else {
            $this->response(['Authentication failed'], RESTController::HTTP_UNAUTHORIZED);
        }
    }

    //INPUT USER
    public function index_post()
    {
        $headers = $this->input->request_headers();
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                $data = $this->post();
                $data['created_at'] = date("Y-m-d H:i:s");;
                $data['updated_at'] = date("Y-m-d H:i:s");;
                if ($data === null) {
                    $this->response([
                        'status' => false,
                        'message' => 'The Data Incorrect'
                    ], RESTController::HTTP_OK);
                } else {
                    $insert = $this->vehicle_Type_Model->input_type($data);
                    if ($insert > 0) {
                        $this->response([
                            'status' => true,
                            'message' => 'The Data Inserted'
                        ], RESTController::HTTP_CREATED);
                    } else {
                        $this->response([
                            'status' => false,
                            'message' => 'THE DATA PARAMETER IS INCORRECT'
                        ], RESTController::HTTP_OK);
                    }
                }
            } else {
                $this->response($decodedToken, RESTController::HTTP_UNAUTHORIZED);
            }
        } else {
            $this->response(['Authentication failed'], RESTController::HTTP_UNAUTHORIZED);
        }
    }

    // UPDATE USER
    public function index_patch()
    {
        $headers = $this->input->request_headers();
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                $where_data = array('id' => $this->input->get('id'));
                $update_data = $this->patch();
                $update_data['updated_at'] = date("Y-m-d H:i:s");;
                if ($update_data === null) {
                    $this->response([
                        'status' => false,
                        'message' => 'The Params Incorrect'
                    ], RESTController::HTTP_OK);
                } else {
                    $update = $this->vehicle_Type_Model->update_type($update_data, $where_data);
                    if ($update > 0) {
                        $this->response([
                            'status' => true,
                            'message' => 'The Data Updated'
                        ], RESTController::HTTP_CREATED);
                    } else {
                        $this->response([
                            'status' => false,
                            'message' => 'THE DATA PARAMETER IS INCORRECT'
                        ], RESTController::HTTP_OK);
                    }
                }
            } else {
                $this->response($decodedToken, RESTController::HTTP_UNAUTHORIZED);
            }
        } else {
            $this->response(['Authentication failed'], RESTController::HTTP_UNAUTHORIZED);
        }
    }

    // DELETE USER
    public function index_delete()
    {
        $headers = $this->input->request_headers();
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                $where_data = array('id' => $this->input->get('id'));
                if ($where_data === null) {
                    if ($this->delete('confirm_all')) {
                        $delete = $this->vehicle_Type_Model->delete_all();
                        $this->response([
                            'status' => true,
                            'message' => 'ALL Data Deleted'
                        ], RESTController::HTTP_OK);
                    } else {
                        $this->response([
                            'status' => false,
                            'message' => 'Forbidden'
                        ], RESTController::HTTP_FORBIDDEN);
                    }
                } else {
                    $delete = $this->vehicle_Type_Model->delete_type($where_data);
                    if ($delete > 0) {
                        $this->response([
                            'status' => true,
                            'message' => 'The Data Deleted'
                        ], RESTController::HTTP_OK);
                    } else {
                        $this->response([
                            'status' => false,
                            'message' => 'INTERNAL ERROR'
                        ], RESTController::HTTP_INTERNAL_ERROR);
                    }
                }
            } else {
                $this->response($decodedToken, RESTController::HTTP_UNAUTHORIZED);
            }
        } else {
            $this->response(['Authentication failed'], RESTController::HTTP_UNAUTHORIZED);
        }
    }
}