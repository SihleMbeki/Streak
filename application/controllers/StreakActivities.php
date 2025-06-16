<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StreakActivities extends CI_Controller {

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
    public $StreakActivities_model; // Explicitly declare the property

    public function __construct() {
        parent::__construct();
        $this->load->database(); 
        $this->load->model('StreakActivities_model');
        $this->load->helper(['url', 'form']);
        $this->load->library('form_validation');
    }

    public function create($streak_id) {

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

        // Validate the token against the database
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
        $this->form_validation->set_rules('activity_name', 'Activity Name', '');
    
        if($streak_id==null){
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Streak ID is required']));
            return;
        }
$count = count($this->StreakActivities_model->get_activities_by_streakid($streak_id))+1;
        $data = [
            'user_id' => $user['user_id'],
            'streak_id' => $streak_id, // Use streak_id from the path parameter
            'activity_name' => $count,
            'count' =>$count,
            'activity_date' => date('Y-m-d H:i:s')
        ];
log_message('debug', 'Creating activity with data: ' . json_encode($data));
        if ($this->StreakActivities_model->create_activity($data)) {
            $this->output
                ->set_status_header(201)
                ->set_content_type('application/json')
                ->set_output(json_encode(['Streak' => $count]));
        } else {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Failed to create activity']));
        }
    }

    public function get_by_user($user_id) {
        $activities = $this->StreakActivities_model->get_activities_by_user($user_id);
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode($activities));
    }

    public function update_status($activity_id) {
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[completed,pending]');

        if ($this->form_validation->run() === FALSE) {
            $errors = validation_errors();
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => $errors]));
            return;
        }

        $status = $this->input->post('status');
        if ($this->StreakActivities_model->update_activity_status($activity_id, $status)) {
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json')
                ->set_output(json_encode(['message' => 'Activity status updated successfully']));
        } else {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Failed to update activity status']));
        }
    }

    public function delete($activity_id) {
        if ($this->StreakActivities_model->delete_activity($activity_id)) {
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json')
                ->set_output(json_encode(['message' => 'Activity deleted successfully']));
        } else {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Failed to delete activity']));
        }
    }
}