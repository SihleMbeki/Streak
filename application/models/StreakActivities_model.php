<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StreakActivities_model extends CI_Model {
    public function create_activity($data) {
        return $this->db->insert('streak_activities', $data);
    }

    public function get_activities_by_user($user_id) {
        return $this->db->get_where('streak_activities', ['user_id' => $user_id])->result_array();
    }

    public function get_activities_by_streakid($streak_id) {
        return $this->db->get_where('streak_activities', ['streak_id' => $streak_id])->result_array();
    }

    public function update_activity_status($activity_id, $status) {
        $this->db->where('activity_id', $activity_id);
        return $this->db->update('streak_activities', ['status' => $status]);
    }

    public function delete_activity($activity_id) {
        $this->db->where('activity_id', $activity_id);
        return $this->db->delete('streak_activities');
    }
}