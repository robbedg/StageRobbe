<?php
/**
 * Model for Roles
 * @package application\models\Role_model
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @date 23/02/2017
 * @time 13:31
 * @filesource
 */
class Role_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    //get roles
    public function get_role($data = [])
    {
        $this->db->select('roles.id AS id, roles.name AS name');

        //if usercount is wanted
        if ((!empty($data['user_count'])) && ($data['user_count'] === TRUE)) {
            $this->db->join('users', 'users.role_id = roles.id', 'left outer');
            $this->db->select('COUNT(users.id) AS user_count');
            $this->db->group_by('roles.id');
        }

        //if id is given
        if (!empty($data['id'])) {
            $this->db->where('roles.id', $data['id']);
        }

        //if sort is included
        if (!empty($data['sort_on'])) {
            $this->db->order_by($data['sort_on']['column'], $data['sort_on']['order']);
        }

        //if search is given
        if (!empty($data['search'])) {
            $this->db->group_start();
            $this->db->like('roles.id', $data['search']);
            $this->db->or_like('roles.name', $data['search']);
            $this->db->group_end();
        }

        //search on users
        if ((!empty($data['user_search'])) && (!empty($data['user_count'])) && ($data['user_count'] === TRUE)) {
            $this->db->group_start();
            $this->db->like('users.uid', $data['user_search']);
            $this->db->or_like('users.firstname', $data['user_search']);
            $this->db->or_like('users.lastname', $data['user_search']);
            $this->db->or_like('roles.name', $data['user_search']);
            $this->db->group_end();
        }

        //count results
        $count = $this->db->count_all_results('roles', false);

        //return results
        $results = [];
        $results['count'] = $count;
        if (array_key_exists('id', $data) && !empty($data['id'])) {
            $results['data'] = $this->db->get()->row_array();
        } else {
            $results['data'] = $this->db->get()->result_array();
        }

        return $results;
    }
}