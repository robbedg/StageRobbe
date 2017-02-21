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
        $this->db->select('username, text, created_on');
        $this->db->from('usernotes');
        $this->db->where('item_id', $id);
        $this->db->order_by('created_on', 'desc');
        $query = $this->db->get();

        return $query->result_array();
    }

    public function set_usernote() {
        $this->load->helper('url');

        $data = array(
            'username' => $this->input->post('username'),
            'text' => nl2br($this->input->post('comment')),
            'item_id' => $this->input->post('item_id')
        );

        return $this->db->insert('usernotes', $data);
    }
}