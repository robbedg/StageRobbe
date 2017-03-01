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

    public function get_location($id = FALSE, $limit = FALSE, $offset = FALSE, $sorton = FALSE, $search = FALSE)

    {   $this->db->select('locations.id AS id, locations.name as name, COUNT(items.id) AS item_count');
        $this->db->from('locations');
        $this->db->join('items', 'items.location_id = locations.id', 'left outer');
        $this->db->group_by('locations.id');

        //return 1 if ID is set
        if ($id !== FALSE) {
            $this->db->where('locations.id', $id);
        }

        //set limit if set
        if ($limit !== FALSE) {
            //if offset is included
            if($offset !== FALSE) {
                $this->db->limit($limit, $offset);
            } else {
                $this->db->limit($limit);
            }
        }

        //if sort is included
        if ($sorton !== FALSE) {
            $this->db->order_by('locations'.$sorton['column'], $sorton['order']);
        }

        //if user wants search
        if (($search !== FALSE) && ($id === FALSE)) {
            $this->db->like('locations.id', $search);
            $this->db->or_like('locations.name', $search);
        }

        //return result
        if ($id !== FALSE) {
            return $this->db->get()->row_array();
        }

        return $this->db->get()->result_array();
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
    public function delete_location($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('locations');
    }

    //count location
    public function count_locations()
    {
        return $this->db->count_all('locations');
    }

}