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
        $this->db->join('itemtypes', 'items.itemtype_id = itemtypes.id', 'left outer');
        $this->db->join('locations', 'items.location_id = locations.id', 'left outer');
        $this->db->where('items.visible', 1);
        $this->db->order_by('itemtypes.name', 'asc');

        if ($id === FALSE){
            $this->db->select('items.id AS id, itemtypes.name AS itemtype, itemtypes.id AS itemtype_id, locations.name AS location, locations.id AS location_id, items.created_on AS created_on');
            $items = $this->db->get();

            return $items->result_array();
        }

        $this->db->select('items.id AS id, itemtypes.name AS itemtype, itemtypes.id AS itemtype_id, locations.name AS location, locations.id AS location_id, items.attributes AS attributes, items.created_on AS created_on');
        $this->db->where('items.id', $id);
        $item = $this->db->get();
        $item = $item->row_array();

        $item['attributes'] = json_decode($item['attributes'], true);

        return $item;
    }

    //Give items on location
    public function get_item_by_location($id = FALSE)
    {
        //query
        $this->db->select('locations.id AS location_id, itemtypes.id AS itemtype_id, itemtypes.name AS itemtype, locations.name AS location, count(itemtypes.name) AS count');
        $this->db->from('items');
        $this->db->join('itemtypes', 'items.itemtype_id = itemtypes.id', 'left outer');
        $this->db->join('locations', 'items.location_id = locations.id', 'left outer');
        $this->db->where('locations.id', $id);
        $this->db->where('items.visible', 1);
        $this->db->group_by('itemtypes.name');
        $this->db->order_by('itemtypes.name', 'asc');

        $items = $this->db->get();

        return $items->result_array();
    }

    //give items of catagory in location
    public function get_item_by_catagory($locationid = FALSE, $itemtypeid =FALSE) {

        //query
        $this->db->select('items.id AS item_id, itemtypes.name AS itemtype, locations.name AS location, items.created_on AS created_on');
        $this->db->from('items');
        $this->db->join('itemtypes', 'items.itemtype_id = itemtypes.id', 'left outer');
        $this->db->join('locations', 'items.location_id = locations.id', 'left outer');
        $this->db->where('locations.id', $locationid);
        $this->db->where('itemtypes.id', $itemtypeid);
        $this->db->where('items.visible', 1);
        $this->db->order_by('itemtypes.name', 'asc');

        $items = $this->db->get();

        return $items->result_array();
    }

    //Update or create new item
    public function set_item(){
        $this->load->helper('url');

        $data = $this->input->post();
        var_dump($data);
        //get attributes set by user
        $attributes = Array();
        foreach ($data['label'] as $index => $label) {
            $attributes[$label] = $data['value'][$index];
        }

        $this->db->set('itemtype_id', $data['itemtype']);
        $this->db->set('location_id', $data['location']);
        $this->db->set('attributes', json_encode($attributes));

        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            return $this->db->update('items');
        }


        return $this->db->insert('items');
    }

    //remove item
    public function remove_item($id = FALSE) {

        //query
        $this->db->set('visible', 0);
        $this->db->where('id', $id);
        $this->db->update('items');
    }
}