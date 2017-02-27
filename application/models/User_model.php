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
        $this->db->select('users.id AS id, firstname, lastname, role_id, roles.name AS role');
        $this->db->from('users');
        $this->db->join('roles', 'roles.id = users.role_id', 'inner');

        if (empty($id)) {
            $this->db->order_by('users.lastname', 'ASC');
            $query = $this->db->get();
            return $query->result_array();
        }
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    //update user
    public function update_user($user)
    {
        if (!empty($user)) {
            $this->db->set('firstname', $user['firstname']);
            $this->db->set('lastname', $user['lastname']);
            $this->db->set('role_id', $user['role_id']);
            $this->db->where('id', $user['id']);
            $this->db->update('users');
        } else {
            show_error('No user specified');
        }
    }
}