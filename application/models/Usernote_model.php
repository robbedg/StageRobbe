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
        $this->load->helper('url');
        $this->load->helper('authorizationcheck_helper');
        $this->load->library('session');
    }

    public function get_usernotes_by_item($id = FALSE)
    {
        $this->db->select('usernotes.id AS id, firstname, lastname, text, created_on, users.id AS user_id');
        $this->db->from('usernotes');
        $this->db->join('users', 'users.id = usernotes.user_id', 'inner');
        $this->db->where('item_id', $id);
        $this->db->order_by('created_on', 'desc');
        $query = $this->db->get();

        return $query->result_array();
    }

    public function set_usernote() {

        $data = array(
            'user_id' => $this->session->userdata('id'),
            'text' => nl2br($this->input->post('comment')),
            'item_id' => $this->input->post('item_id')
        );

        return $this->db->insert('usernotes', $data);
    }

    //Delete note when authorized.
    public function remove_usernote($id) {
        $this->db->select('user_id');
        $this->db->where('id', $id);
        $query = $this->db->get('usernotes');
        $userid = (string)$query->row_array()['user_id'];

        if (($userid === $this->session->userdata('id')) || authorization_check($this, 3)) {
            $this->db->flush_cache();
            $this->db->where('id', $id);
            return $this->db->delete('usernotes');
        } else {
            show_error('Not authorized.');
        }
    }
}