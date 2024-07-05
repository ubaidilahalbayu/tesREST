<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Pricelist extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Load model 
        $this->load->model(array('pricelist_Model'));
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
                $pricelists = array();

                if ($where_data === null) {
                    $pricelists = $this->pricelist_Model->get_pricelists();
                } else {
                    $pricelists = $this->pricelist_Model->get_pricelist_where($where_data);
                }

                // Check if the pricelists data store contains pricelists
                if (count($pricelists) > 0) {
                    // Set the response and exit
                    $this->response([
                        'status' => true,
                        'message' => 'Users available',
                        'data' => $pricelists
                    ], RESTController::HTTP_OK);
                } else {
                    // Set the response and exit
                    $this->response([
                        'status' => false,
                        'message' => 'No pricelists were found'
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
                    $insert = $this->pricelist_Model->input_pricelist($data);
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
                    $update = $this->pricelist_Model->update_pricelist($update_data, $where_data);
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
                        $delete = $this->pricelist_Model->delete_all();
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
                    $delete = $this->pricelist_Model->delete_pricelist($where_data);
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