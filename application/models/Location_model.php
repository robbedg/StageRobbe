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

    public function get_location($id = FALSE)
    {
        if ($id === FALSE)
        {
            $this->db->select('locations.id AS id, locations.name as name, COUNT(items.id) AS item_count');
            $this->db->from('locations');
            $this->db->join('items', 'items.location_id = locations.id', 'left outer');
            $this->db->group_by('locations.id');
            $this->db->order_by('locations.name', 'asc');
            $query = $this->db->get();
            return $query->result_array();
        }

        $query = $this->db->get_where('locations', array('id'=>$id));
        return $query->row_array();
    }

    //create or update a location
    public function set_location($id = FALSE)
    {
        $this->load->helper('url');

        //get ifo from post
        $data = array(
            'name' => $this->input->post('name')
        );

        if (empty($id)) {
            return $this->db->insert('locations', $data);
        } else {
            $this->db->where('id', $id);
            return $this->db->update('locations', $data);
        }

    }

    //delete a location
    public function delete_location($id) {
        $this->db->where('id', $id);
        return $this->db->delete('locations');
    }

    //search for location BETA
    public function search_location($query = FALSE) {
        $this->db->select('name, id');
        $this->db->from('locations');
        $this->db->like('name', $query);
        $result = $this->db->get();
        return $result->result_array();
    }

}