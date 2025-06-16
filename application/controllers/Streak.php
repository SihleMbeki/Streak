<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Streak extends CI_Controller {
	public $benchmark    = [];
    public $hooks     = [];
    public $config      = [];
    public $log      = [];
    public $utf8       = [];
    public $uri       = [];
    public $router      = [];
    public $output      = [];
    public $security      = [];
    public $input      = [];
    public $lang       = [];
    public $db       = [];
    public $Account_model;
    public $form_validation;
    public $session;
    public $Users_model; // Explicitly declare the property
    public $Streak_model; // Explicitly declare the property

    public function __construct() {
        parent::__construct();
        $this->load->database(); 
        $this->load->model('Streak_model');
        $this->load->model('Users_model');
        $this->load->helper(['url', 'form']);
        $this->load->library('form_validation');
    }

public function create() {
        // Get the Authorization header
        $authHeader = $this->input->get_request_header('Authorization', TRUE);

        // Check if the header contains a Bearer token
        if (strpos($authHeader, 'Bearer ') === 0) {
            $token = substr($authHeader, 7); // Extract the token after "Bearer "
            log_message('debug', 'Received Bearer token: ' . $token);
        } else {
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Unauthorized: Bearer token missing']));
            return;
        }
        if ($token==null){
            $this->output
            ->set_status_header(401)
            ->set_content_type('application/json')
            ->set_output(json_encode(['error' => 'Unauthorized: Bearer token missing']));
        return;
        }
        $query = $this->db->get_where('auth_tokens', ['token' => $token, 'is_active' => 1]);
        if ($query->num_rows() === 0) {
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Unauthorized: Invalid token']));
            return;
        }
    
        // Token is valid, proceed with the request
        $user = $query->row_array();
        log_message('debug', 'Authenticated user ID: ' . $user['user_id']);

    $this->form_validation->set_rules('name', 'Name', 'min_length[3]');
    $this->form_validation->set_rules('schedule_type', 'Schedule_type', 'required');

    if ($this->form_validation->run() === FALSE) {
        // Return validation errors as JSON
        $errors = validation_errors();
        $this->output
            ->set_status_header(400)
            ->set_content_type('application/json')
            ->set_output(json_encode(['error' => $errors]));
        return;
    }
    $email;
    $user_id=$user['user_id'];
    if ( $this->db->get_where('streaks', ['name' =>$this->input->post('name'), 'user_id'=> $user_id])->num_rows() > 0)   {
        $this->output
            ->set_status_header(400)
            ->set_content_type('application/json')
            ->set_output(json_encode(['error' => '400-02']));
        return;

    }

    if ($this->input->post('schedule_type') !="Weekly" ) {
        $this->output
            ->set_status_header(400)
            ->set_content_type('application/json')
            ->set_output(json_encode(['error' => '422-03']));
        return;
    }
    $nextWeek = date('Y-m-d', strtotime('+7 days'));
    $email=$this->input->post('email');
    $data = [
        'name' => $this->input->post('name'),
        'schedule_type' => $this->input->post('schedule_type'),
        'user_id' => $user_id,
        'end_date' => $nextWeek
    ];
    $this->db->insert('streaks', $data);    
    $streak =$this->Streak_model->streak_details($this->input->post('name'), $user_id)['streak_id'];
    // Return HTTP 201 status code for successful creation
    $this->output
        ->set_status_header(201)
        ->set_content_type('application/json')
        ->set_output(json_encode(['streak_id' => $streak]));
}
}