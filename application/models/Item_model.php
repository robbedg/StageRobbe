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
    public function get_item($data = [])
    {
        //base query
        $this->db->select('items.id AS id, items.created_on AS created_on');

        //deleted items?
        if (!empty($data['deleted']) && $data['deleted'] === TRUE) {
            $this->db->where('items.visible', 0);
        } else {
            $this->db->where('items.visible', 1);
        }

        //if location is given
        if (!empty($data['location_id'])) {
            $this->db->where('items.location_id', $data['location_id']);
        }

        //display location
        $location = FALSE;
        if (!empty($data['location']) && $data['location'] === TRUE) {
            $this->db->select('locations.id AS location_id, locations.name AS location');
            $this->db->join('locations', 'locations.id = items.location_id', 'left outer');
            $location = TRUE;
        }

        //if category is given
        if (!empty($data['category_id'])) {
            $this->db->where('items.category_id', $data['category_id']);
        }

        //display category
        $category = FALSE;
        if (!empty($data['category']) && $data['category'] === TRUE) {
            $this->db->select('categories.id AS category_id, categories.name AS category');
            $this->db->join('categories', 'categories.id = items.category_id', 'left outer');
            $category = TRUE;
        }

        //return 1 if ID is set
        if (!empty($data['id'])) {
            $this->db->select('items.attributes AS attributes');
            $this->db->where('items.id', $data['id']);
        }

        //if sort is included
        if (!empty($data['sort_on'])) {
            $this->db->order_by($data['sort_on']['column'], $data['sort_on']['order']);
        }

        //if user wants search
        if ((!empty($data['search'])) && (empty($data['id']))) {
            $this->db->group_start();
            $this->db->like('items.id', $data['search']);
            $this->db->or_like('items.created_on', $data['search']);
            $this->db->or_like('items.attributes', $data['search']);
            if ($location) {
                $this->db->or_like('locations.id', $data['search']);
                $this->db->or_like('locations.name', $data['search']);
            }
            if ($category) {
                $this->db->or_like('category.id', $data['search']);
                $this->db->or_like('category.name', $data['search']);
            }
            $this->db->group_end();
        }

        $count = $this->db->count_all_results('items', false);

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
        if (!empty($data['id'])) {
            $result['data'] = $this->db->get()->row_array();
            $result['count'] = $count;

            $result['data']['attributes'] = json_decode($result['data']['attributes'], true);
            //Get picture
            $picture = glob('./uploads/'.$data['id'].'*');
            if (!empty($picture)) {
                $picture = '.'.$picture[0];
                $result['data']['image'] = $picture;
            }
            else {
                $result['data']['image'] = NULL;
            }

            return $result;
        }

        $result['data'] = $this->db->get()->result_array();
        $result['count'] = $count;

        return $result;
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