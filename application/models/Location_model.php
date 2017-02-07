<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 7/02/2017
 * Time: 8:47
 */
class Location_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_location($id = false)
    {
        if ($id === FALSE)
        {
            $query = $this->db->get('locations');
            return $query->result_array();
        }

        $query = $this->db->get_where('locations', array('id'=>$id));
        return $query->row_array();
    }

    public function set_location()
    {
        $this->load->helper('url');

        $data = array(
            'name' => $this->input->post('name')
        );

        return $this->db->insert('locations', $data);
    }
}