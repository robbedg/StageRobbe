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
    }

    //get item(s)
    public function get_item($id = FALSE)
    {
        //query
        $this->db->from('items');
        $this->db->join('categories', 'items.category_id = categories.id', 'left outer');
        $this->db->join('locations', 'items.location_id = locations.id', 'left outer');
        $this->db->where('items.visible', 1);
        $this->db->order_by('categories.name', 'asc');

        if ($id === FALSE){
            $this->db->select('items.id AS id, categories.name AS category, categories.id AS category_id, locations.name AS location, locations.id AS location_id, items.created_on AS created_on');
            $items = $this->db->get();

            return $items->result_array();
        }

        $this->db->select('items.id AS id, categories.name AS category, categories.id AS category_id, locations.name AS location, locations.id AS location_id, items.attributes AS attributes, items.created_on AS created_on');
        $this->db->where('items.id', $id);
        $item = $this->db->get();
        $item = $item->row_array();

        $item['attributes'] = json_decode($item['attributes'], true);

        //Get picture
        $picture = glob('./uploads/'.$id.'*');
        if (!empty($picture)) {
            $picture = '.'.$picture[0];
            $item['image'] = $picture;
        }
        else {
            $item['image'] = NULL;
        }

        return $item;
    }

    //Give items on location
    public function get_item_by_location($id = FALSE)
    {
        //query
        $this->db->select('locations.id AS location_id, categories.id AS category_id, categories.name AS category, locations.name AS location, count(categories.name) AS count');
        $this->db->from('items');
        $this->db->join('categories', 'items.category_id = categories.id', 'left outer');
        $this->db->join('locations', 'items.location_id = locations.id', 'left outer');
        $this->db->where('locations.id', $id);
        $this->db->where('items.visible', 1);
        $this->db->group_by('categories.name');
        $this->db->order_by('categories.name', 'asc');

        $items = $this->db->get();

        return $items->result_array();
    }

    //give items of catagory in location
    public function get_item_by_catagory($locationid = FALSE, $categoryid = FALSE) {

        //query
        $this->db->select('items.id AS item_id, categories.name AS category, locations.name AS location, items.created_on AS created_on');
        $this->db->from('items');
        $this->db->join('categories', 'items.category_id = categories.id', 'left outer');
        $this->db->join('locations', 'items.location_id = locations.id', 'left outer');
        $this->db->where('locations.id', $locationid);
        $this->db->where('categories.id', $categoryid);
        $this->db->where('items.visible', 1);
        $this->db->order_by('categories.name', 'asc');

        $items = $this->db->get();

        return $items->result_array();
    }

    //Update or create new item
    public function set_item(){
        $this->load->helper('url');

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
}