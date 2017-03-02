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

    public function get_category($id = FALSE, $location_id = FALSE, $limit = FALSE, $offset = FALSE, $sorton = FALSE, $search = FALSE)
    {
        $this->db->select('categories.id AS id, categories.name as name, COUNT(items.id) AS item_count');
        $this->db->join('items', 'items.category_id = categories.id', 'left outer');
        $this->db->group_by('categories.id');

        //if location is given
        if ($location_id !== FALSE) {
            $this->db->join('locations', 'locations.id = items.location_id', 'left outer');
            $this->db->where('locations.id', $location_id);
        }

        //return 1 if ID is set
        if ($id !== FALSE) {
            $this->db->where('categories.id', $id);
        }

        //if sort is included
        if ($sorton !== FALSE) {
            $this->db->order_by($sorton['column'], $sorton['order']);
        }

        //if user wants search
        if (($search !== FALSE) && ($id === FALSE)) {
            $this->db->or_like('categories.id', $search);
            $this->db->or_like('categories.name', $search);
        }

        $count = $this->db->count_all_results('categories', false);

        //set limit if set
        if ($limit !== FALSE) {
            //if offset is included
            if($offset !== FALSE) {
                $this->db->limit($limit, $offset);
            } else {
                $this->db->limit($limit);
            }
        }

        //return result
        if ($id !== FALSE) {
            $data['data'] = $this->db->get()->row_array();
            $data['count'] = $count;
            return $data;
        }

        $data['data'] = $this->db->get()->result_array();
        $data['count'] = $count;

        return $data;
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