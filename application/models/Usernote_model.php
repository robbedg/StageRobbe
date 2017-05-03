<?php

/**
 * Model for Usernotes
 * @package application\models\Usernote_model
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @date 21/02/2017
 * @time 9:47
 * @filesource
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

    public function get_usernote($data = []) {
        $this->db->select('usernotes.id AS id, user_id, item_id, text, created_on');

        //give id
        if (!empty($data['id'])) {
            $this->db->where('usernotes.id', $data['id']);
        }

        //if give user_id
        if (!empty($data['user_id'])) {
            $this->db->where('usernotes.user_id', $data['user_id']);
        }

        //include user
        $user = FALSE;
        if (!empty($data['user']) && $data['user'] === TRUE) {
            $this->db->select('uid, firstname, lastname, role_id');
            $this->db->join('users', 'users.id = usernotes.user_id', 'inner');
            $user = TRUE;
        }

        //if given item_id
        if (!empty($data['item_id'])) {
            $this->db->where('usernotes.item_id', $data['item_id']);
        }

        //include item
        $item = FALSE;
        if (!empty($data['item']) && $data['item'] === TRUE) {
            $this->db->select('category_id, location_id, items.created_on AS item_created_on');
            $this->db->join('items', 'items.id = usernotes.item_id', 'inner');
            $this->db->select('categories.name AS category');
            $this->db->join('categories', 'categories.id = items.category_id', 'right outer');
            $this->db->select('locations.name AS location');
            $this->db->join('locations', 'locations.id = items.location_id', 'right outer');
            $item = TRUE;
        }

        //if search is given
        if (!empty($data['search'])) {
            $this->db->group_start();
            $this->db->like('usernotes.id', $data['search']);
            $this->db->or_like('usernotes.created_on', $data['search']);
            $this->db->or_like('usernotes.text', $data['search']);
            $this->db->or_like('usernotes.user_id', $data['search']);
            $this->db->or_like('usernotes.item_id', $data['search']);
            if ($user) {
                $this->db->or_like('users.firstname', $data['search']);
                $this->db->or_like('users.lastname', $data['search']);
                $this->db->or_like('users.uid', $data['search']);
            }
            if ($item) {
                $this->db->or_like('categories.name', $data['search']);
                $this->db->or_like('locations.name', $data['search']);
            }
            $this->db->group_end();
        }

        //sort included
        if (!empty($data['sort_on'])) {
            $this->db->order_by($data['sort_on']['column'], $data['sort_on']['order']);
        }

        //count results
        $count = $this->db->count_all_results('usernotes', false);



        //set limit if set
        if (!empty($data['limit'])) {
            //if offset is included
            if($data['offset'] !== FALSE) {
                $this->db->limit($data['limit'], $data['offset']);
            } else {
                $this->db->limit($data['limit']);
            }
        }

        //return results
        $results = [];
        $results['count'] = $count;

        $results['data'] = $this->db->get();

        if (!$results['data']) {
            return FALSE;
        }

        $results['data'] = $results['data']->result_array();

        foreach ($results['data'] as $key => $value) {
            if (!empty($value['created_on'])) {
                $results['data'][$key]['created_on'] = (new DateTime($value['created_on']))->format('d/m/Y H:i');
            }

            if (!empty($value['item_created_on'])) {
                $result['data'][$key]['item_created_on'] = (new DateTime($value['item_created_on']))->format('d/m/Y H:i');
            }
        }

        return $results;
    }

    public function set_usernote($input = []) {

        $data = [];

        //return FALSE when no text provided.
        if (empty($input['text']) || empty(trim($input['text']))) return FALSE;

        //protect against, trim, htmltags, php tags and convert \nl to <br />
        $input['text'] = trim($input['text']);
        $input['text'] = strip_tags($input['text']);
        $data['text'] = nl2br($input['text']);

        $data['user_id'] = $input['user_id'];
        $data['item_id'] = $input['item_id'];

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