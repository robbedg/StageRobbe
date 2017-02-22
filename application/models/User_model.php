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
        $this->db->select('id');
        $this->db->from('users');
        $this->db->where('uid', $data['uid']);
        $query = $this->db->get();
        $userid = $query->row_array();

        if (empty($userid)) {
            $this->db->insert('users', $data);
            $userid = (string)$this->db->insert_id();
        } else {
            $userid = $userid['id'];
        }

        return $userid;
    }
}