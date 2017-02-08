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
        $this->db->select('items.id, itemtypes.name AS itemtype, locations.name AS location');
        $this->db->from('items');
        $this->db->join('itemtypes', 'items.itemtype_id = itemtypes.id', 'left outer');
        $this->db->join('locations', 'items.location_id = locations.id', 'left outer');
        $this->db->order_by('itemtypes.name', 'asc');

        if ($id === FALSE){

            $items = $this->db->get();

            return $items->result_array();
        }

        $this->db->where('items.id', $id);
        $item = $this->db->get();

        return $item->row_array();
    }

    //Give items on location
    public function get_item_by_location($id = FALSE)
    {
        //query
        $this->db->select('items.id, itemtypes.name AS itemtype, locations.name AS location');
        $this->db->from('items');
        $this->db->join('itemtypes', 'items.itemtype_id = itemtypes.id', 'left outer');
        $this->db->join('locations', 'items.location_id = locations.id', 'left outer');
        $this->db->where('locations.id', $id);
        $this->db->order_by('itemtypes.name', 'asc');

        $items = $this->db->get();

        return $items->result_array();
    }

    //Update or create new item
    public function set_item(){
        $this->load->helper('url');

        $data = array(
            'itemtype_id' => $this->input->post('itemtype'),
            'location_id' => $this->input->post('location')
        );

        //add id for update
        $id = $this->input->post('id');

        if (!empty($id)) {
            $this->db->set($data);
            $this->db->where('id', $id);
            return $this->db->update('items');
        }

        return $this->db->insert('items', $data);
    }
}