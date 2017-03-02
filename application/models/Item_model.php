<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 7/02/2017
 * Time: 9:36
 */
class Item_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->helper('url');
    }

    //get item(s)
    public function get_item($id = FALSE, $location_id = FALSE, $category_id = FALSE, $limit = FALSE, $offset = FALSE, $sorton = FALSE, $search = FALSE)
    {
        //base query
        $this->db->select('items.id AS id, items.created_on AS created_on');
        $this->db->where('items.visible', 1);

        //if location is given
        if ($location_id !== FALSE) {
            $this->db->join('locations', 'locations.id = items.location_id', 'left outer');
            $this->db->where('locations.id', $location_id);
        }

        //if category is given
        if ($category_id !== FALSE) {
            $this->db->join('categories', 'categories.id = items.category_id', 'left outer');
            $this->db->where('categories.id', $category_id);
        }

        //return 1 if ID is set
        if ($id !== FALSE) {
            if ($location_id === FALSE) $this->db->join('locations', 'locations.id = items.location_id', 'left outer');
            if ($category_id === FALSE) $this->db->join('categories', 'categories.id = items.category_id', 'left outer');
            $this->db->select('items.attributes AS attributes');
            $this->db->select('locations.name AS location, locations.id AS location_id');
            $this->db->select('categories.name AS category, categories.id AS category_id');
            $this->db->where('items.id', $id);
        }

        //if sort is included
        if ($sorton !== FALSE) {
            $this->db->order_by($sorton['column'], $sorton['order']);
        }

        //if user wants search
        if (($search !== FALSE) && ($id === FALSE)) {
            $this->db->or_like('items.id', $search);
            $this->db->or_like('items.created_on', $search);
            $this->db->or_like('items.attributes', $search);
        }

        $count = $this->db->count_all_results('items', false);

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

            $data['data']['attributes'] = json_decode($data['data']['attributes'], true);
            //Get picture
            $picture = glob('./uploads/'.$id.'*');
            if (!empty($picture)) {
                $picture = '.'.$picture[0];
                $data['data']['image'] = $picture;
            }
            else {
                $data['data']['image'] = NULL;
            }

            return $data;
        }

        $data['data'] = $this->db->get()->result_array();
        $data['count'] = $count;

        return $data;
    }

    //Update or create new item
    public function set_item(){

        $data = $this->input->post();

        //get attributes set by user
        $attributes = Array();
        if (!empty($data['label'])) {
            foreach ($data['label'] as $index => $label) {
                $attributes[$label] = $data['value'][$index];
            }
        }

        $this->db->set('category_id', $data['category']);
        $this->db->set('location_id', $data['location']);
        $this->db->set('attributes', json_encode($attributes));

        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('items');
            return $data['id'];
        }

        $this->db->insert('items');
        return $this->db->insert_id();
    }

    //remove item
    public function remove_item($id = FALSE) {
        //delete old image
        $files = glob('./uploads/'.$id.'*');
        foreach ($files as $file) {
            unlink($file);
        }
        //query
        $this->db->set('visible', 0);
        $this->db->where('id', $id);
        $this->db->update('items');
    }

    //return all invisible items.
    public function get_deleted_items() {
        $this->db->select('items.id AS id, locations.name AS location, categories.name AS category, items.created_on AS created_on');
        $this->db->from('items');
        $this->db->join('locations', 'locations.id = items.location_id', 'right outer');
        $this->db->join('categories', 'categories.id = items.category_id', 'right outer');
        $this->db->where('items.visible', 0);

        $data = $this->db->get();

        return $data->result_array();
    }

    //restore invisible item
    public function restore_item($id = FALSE)
    {
        $data = array(
            'visible' => 1
        );

        $this->db->where('id', $id);
        return $this->db->update('items', $data);
    }

    //permanently delete item
    public function delete_item($id = FALSE)
    {
        $this->db->where('id', $id);
        return $this->db->delete('items');
    }
}