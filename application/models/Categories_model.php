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

    public function get_category($id = FALSE)
    {
        if ($id === FALSE){

            $category = $this->db->get('categories');

            return $category->result_array();
        }

        $item = $this->db->get_where('categories', array('categories.id'=>$id));

        return $item->row_array();
    }

    public function set_category($id = FALSE)
    {
        $this->load->helper('url');

        $data = array(
            'name' => $this->input->post('name')
        );

        return $this->db->insert('categories', $data);
    }
}