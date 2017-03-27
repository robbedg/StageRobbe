<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 7/02/2017
 * Time: 14:35
 */

class Categories_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_category($data = [])
    {
        $this->db->select('categories.id AS id, categories.name as name, COUNT(items.id) AS item_count');
        $this->db->join('items', 'items.category_id = categories.id', 'left outer');
        $this->db->group_by('categories.id');

        //if location is given
        if (!empty($data['location_id'])) {
            $this->db->join('locations', 'locations.id = items.location_id', 'left outer');
            $this->db->where('locations.id', $data['location_id']);
        }

        //return 1 if ID is set
        if (!empty($data['id'])) {
            $this->db->where('categories.id', $data['id']);
        }

        //if sort is included
        if (!empty($data['sort_on'])) {
            $this->db->order_by($data['sort_on']['column'], $data['sort_on']['order']);
        }

        //if user wants search
        if ((!empty($data['search'])) && (empty($data['id']))) {
            $this->db->group_start();
            $this->db->like('categories.id', $data['search']);
            $this->db->or_like('categories.name', $data['search']);
            $this->db->group_end();
        }

        $count = $this->db->count_all_results('categories', false);

        //set limit if set
        if (!empty($data['limit'])) {
            //if offset is included
            if(!empty($data['offset'])) {
                $this->db->limit($data['limit'], $data['offset']);
            } else {
                $this->db->limit($data['limit']);
            }
        }


        $query = $this->db->get();

        if ($query === FALSE) return FALSE;

        $result['data'] = $query->result_array();
        $result['count'] = $count;

        return $result;
    }

    public function set_category($id = FALSE)
    {
        $this->load->helper('url');

        //get ifo from post
        $data = array(
            'name' => $this->input->post('name')
        );

        if (empty($id)) {
            return $this->db->insert('categories', $data);
        } else {
            $this->db->where('id', $id);
            return $this->db->update('categories', $data);
        }
    }

    //delete a category
    public function delete_category($id = FALSE) {
        $this->db->where('id', $id);
        return $this->db->delete('categories');
    }
}