<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 23/02/2017
 * Time: 13:31
 */
class Role_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_role()
    {
        $this->db->select('roles.id AS id, roles.name AS name, COUNT(users.id) AS count');
        $this->db->from('roles');
        $this->db->join('users', 'users.role_id = roles.id', 'inner');
        $this->db->group_by('roles.id');
        $this->db->order_by('roles.id', 'ASC');
        return $this->db->get()->result_array();
    }
}