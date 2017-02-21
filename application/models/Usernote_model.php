<?php

/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 21/02/2017
 * Time: 9:47
 */
class Usernote_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_usernotes_by_item($id = FALSE)
    {
        $this->db->select('username, text');
        $this->db->from('usernotes');
        $this->db->where('item_id', $id);
        $query = $this->db->get();

        return $query->result_array();
    }
}