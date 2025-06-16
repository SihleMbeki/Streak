<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Token_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_token_by_id($user_id) {
        return $this->db->get_where('auth_tokens', ['user_id' => $user_id])->row_array();
    }

    public function delete_token($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->delete('auth_tokens');
    }

}