<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
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

    public function __construct() {
        parent::__construct();
        $this->load->model('Users_model');
        $this->load->helper(['url', 'form']);
        $this->load->library('form_validation');
    }

    public function index() {
        $data['users'] = $this->Users_model->get_all_users();
        $this->load->view('users/index', $data);
    }

public function create() {
    $this->form_validation->set_rules('username', 'Username', 'min_length[4]');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    $this->form_validation->set_rules('name', 'Name', 'required|min_length[3]');
    $this->form_validation->set_rules('surname', 'Surname', 'required|min_length[3]');
    $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');

    if ($this->form_validation->run() === FALSE) {
        // Return validation errors as JSON
        $errors = validation_errors();
        $this->output
            ->set_status_header(400)
            ->set_content_type('application/json')
            ->set_output(json_encode(['error' => $errors]));
        return;
    }
    if ($this->Users_model->email_exists($this->input->post('email'))  ) {
        // Return HTTP 409 status code for conflict
        $this->output
            ->set_status_header(400)
            ->set_content_type('application/json')
            ->set_output(json_encode(['error' => '400-21-1 ']));
        return;

    }
    $data = [
        'name' => $this->input->post('name'),
        'surname' => $this->input->post('surname'),
        'email' => $this->input->post('email'),
        'password_hash' => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
    ];
    $this->Users_model->create_user($data);

    // Return HTTP 201 status code for successful creation
    $this->output
        ->set_status_header(201)
        ->set_content_type('application/json')
        ->set_output(json_encode(['message' => 'User created successfully']));
}

public function login(){
    $this->form_validation->set_rules('email', 'email', 'required|min_length[3]');
    $this->form_validation->set_rules('password', 'password ', 'required|min_length[3]');

    if ($this->form_validation->run() === FALSE) {
        $errors = validation_errors();
        $this->output
            ->set_status_header(400)
            ->set_content_type('application/json')
            ->set_output(json_encode(['error' => $errors]));
        return;
    }

    $email=$this->input->post('email');
    $password=$this->input->post('password');


    $query = $this->db->query("SELECT * FROM users WHERE email = '".$email."'");
    $profiles = $query->row_array(); 

    if ($query->num_rows() === 1) { 
        if ($profiles && password_verify($password, $profiles["password_hash"])) {
        // Generate a token
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $token = substr(str_shuffle($characters), 0, 32);
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expires in 1 hour
            // Insert token into auth_tokens table
            $token_data = [
                'user_id' => $profiles['user_id'],
                'token' => $token,
                'expires_at' => $expires_at,
                'is_active' => 1
            ];
            $this->db->insert('auth_tokens', $token_data);

            // Return the token in the response
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json')
                ->set_output(json_encode(['token' => $token]));
            return;
        }else{
            $this->output
            ->set_status_header(401)
            ->set_content_type('application/json')
            ->set_output(json_encode(['error' => "Invalid email or password"]));
            exit;
        }
    }else{
        $this->output
        ->set_status_header(400)
        ->set_content_type('application/json')
        ->set_output(json_encode(['token' => "Account does not exists"]));
    return;
    }
}
public function edit($id) {
    $data['user'] = $this->Users_model->get_user_by_id($id);

    if (empty($data['user'])) {
        show_404();
    }

    $this->form_validation->set_rules('username', 'Username', 'required|min_length[4]');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

    if ($this->form_validation->run() === FALSE) {
        show_error('Invalid input data', 400); // Return HTTP 400 with an error message
    } else {
        $update_data = [
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email')
        ];
        $this->Users_model->update_user($id, $update_data);

        // Return HTTP 200 status code for successful update
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode(['message' => 'User updated successfully']));
    }
}

public function delete($id) {
    $this->Users_model->delete_user($id);

    // Return HTTP 200 status code for successful deletion
    $this->output
        ->set_status_header(200)
        ->set_content_type('application/json')
        ->set_output(json_encode(['message' => 'User deleted successfully']));
}
}