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

    public function get_location($data = [])
    {
        $this->db->select('locations.id AS id, locations.name as name, COUNT(items.id) AS item_count');
        $this->db->join('items', 'items.location_id = locations.id', 'left outer');
        $this->db->group_by('locations.id');

        //return 1 if ID is set
        if (!empty($data['id'])) {
            $this->db->where('locations.id', $data['id']);
        }

        //if sort is included
        if (!empty($data['sort_on'])) {
            $this->db->order_by($data['sort_on']['column'], $data['sort_on']['order']);
        }

        //if user wants search
        if ((!empty($data['search'])) && (empty($data['id']))) {
            $this->db->group_start();
            $this->db->like('locations.id', $data['search']);
            $this->db->or_like('locations.name', $data['search']);
            $this->db->group_end();
        }

        $count = $this->db->count_all_results('locations', false);

        //set limit if set
        if (!empty($data['limit'])) {
            //if offset is included
            if(!empty($data['offset'])) {
                $this->db->limit($data['limit'], $data['offset']);
            } else {
                $this->db->limit($data['limit']);
            }
        }

        //return result
        $result['data'] = $this->db->get();

        if ($result['data'] === FALSE) return FALSE;

        $result['data'] = $result['data']->result_array();
        $result['count'] = $count;

        return $result;
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

}