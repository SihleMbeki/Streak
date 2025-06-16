<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Streak_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function create_streak($data) {
        return $this->db->insert('streaks', $data);
    }

    public function get_user_by_id($id) {
        return $this->db->get_where('streaks', ['id' => $id])->row_array();
    }

    public function get_all_users() {
        return $this->db->get('streaks')->result_array();
    }

    public function update_user($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('streaks', $data);
    }

    public function delete_user($id) {
        $this->db->where('id', $id);
        return $this->db->delete('streaks');
    }

    public function streak_exists($streak, $user_id) {
        return $this->db->get_where('streaks', ['name' => $streak, 'user_id'=>$user_id])->num_rows() > 0;
    }

    public function streak_details($streak, $user_id) {
        return $this->db->get_where('streaks', ['name' => $streak, 'user_id'=>$user_id])->row_array();
    }
}