<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 7/02/2017
 * Time: 14:35
 */

class Itemtypes_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_itemtype($id = FALSE)
    {
        if ($id === FALSE){

            $itemtypes = $this->db->get('itemtypes');

            return $itemtypes->result_array();
        }

        $this->db->where('items.id', $id);
        $item = $this->db->get_where('itemtypes', array('id'=>$id));

        return $item->row_array();
    }

    public function set_itemtype($id = FALSE)
    {
        $this->load->helper('url');

        $data = array(
            'name' => $this->input->post('name')
        );

        return $this->db->insert('itemtypes', $data);
    }
}