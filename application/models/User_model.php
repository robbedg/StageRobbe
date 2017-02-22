<?php

/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 22/02/2017
 * Time: 10:54
 */
class User_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    //Set user
    public function set_user($data)
    {
        $this->db->select('id, role_id');
        $this->db->from('users');
        $this->db->where('uid', $data['uid']);
        $query = $this->db->get();
        $userinfo = $query->row_array();

        if (empty($userinfo)) {
            $this->db->insert('users', $data);
            $userinfo['id'] = (string)$this->db->insert_id();
            $userinfo['role_id'] = '1';
        }

        return $userinfo;
    }

    //get_user(s)
    public function get_user($id = FALSE)
    {
        $this->db->select('id, firstname, lastname', 'role_id');
        $this->db->from('users');

        if (empty($id)) {
            $query = $this->db->get();
            return $query->result_array();
        }
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
}