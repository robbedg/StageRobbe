<?php

/**
 * Model for user object.
 * @package application\models
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @date 22/02/2017
 * @time 10:54
 * @filesource
 */
class User_model extends CI_Model
{
    /**
     * User_model constructor.
     */
    public function __construct()
    {
        $this->load->database();
    }

    /**
     * returns userdata, creates new user if user doesn't exist yet.
     * @param $data Data of user
     * @return mixed data of user
     */
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

    /**
     * Get data of user based upon filter options
     * @param array $data Filter options
     * @return array|bool users that fit criteria or FALSE in case of an error
     */
    public function get_user($data = [])
    {
        $this->db->select('users.id AS id, uid, firstname, lastname, role_id, roles.name AS role');
        $this->db->join('roles', 'roles.id = users.role_id', 'inner');

        //if id is given
        if (!empty($data['id'])) {
            $this->db->where('users.id', $data['id']);
        }

        // if uid is given
        if (!empty($data['uid'])) {
            $this->db->where('users.uid', $data['uid']);
        }

        //if role is given
        if (!empty($data['role_id'])) {
            $this->db->where('role_id', $data['role_id']);
        }

        //if sort is included
        if (!empty($data['sort_on'])) {
            $this->db->order_by($data['sort_on']['column'], $data['sort_on']['order']);
        }

        //if search is given
        if (!empty($data['search'])) {
            $this->db->group_start();
            $this->db->like('users.uid', $data['search']);
            $this->db->or_like('users.firstname', $data['search']);
            $this->db->or_like('users.lastname', $data['search']);
            $this->db->or_like('roles.name', $data['search']);
            $this->db->group_end();
        }

        //count results
        $count = $this->db->count_all_results('users', false);

        //set limit if set
        if (!empty($data['limit'])) {
            //if offset is included
            if($data['offset'] !== FALSE) {
                $this->db->limit($data['limit'], $data['offset']);
            } else {
                $this->db->limit($data['limit']);
            }
        }

        //return results
        $results = [];
        $results['count'] = $count;

        $results['data'] = $this->db->get();

        //check if successful
        if (!$results['data']) return FALSE;

        $results['data'] = $results['data']->result_array();


        return $results;
    }

    /**
     * Updates user with given user data.
     * @param array $user user data
     * @return bool success
     */
    public function update_user($user = [])
    {
        if (!empty($user) && !empty($user['id'])) {
            if (!empty($user['firstname'])) $this->db->set('firstname', $user['firstname']);
            if (!empty($user['lastname'])) $this->db->set('lastname', $user['lastname']);
            if (!empty($user['role_id'])) $this->db->set('role_id', $user['role_id']);
            if (!empty($user['password'])) $this->db->set('password', password_hash($user['password'], PASSWORD_DEFAULT));
            $this->db->where('id', $user['id']);
            $this->db->update('users');
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Verifies password.
     * @param $username uid of user object
     * @param $password password of attempt
     * @return bool TRUE or FALSE (success)
     */
    function login($username, $password)
    {
        $this->db->select('id, uid, password');
        $this->db->from('users');
        $this->db->where('uid', $username);

        $query = $this->db->get();

        if ($query === FALSE) return FALSE;

        $user = $query->row_array();

        return password_verify($password, $user['password']);
    }
}