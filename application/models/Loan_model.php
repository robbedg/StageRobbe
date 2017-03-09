<?php

/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 9/03/2017
 * Time: 10:28
 */
class loan_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->helper('url');
    }

    public function get_loan($data = []) {
        $this->db->select('loans.id AS id, loans.item_id AS item_id, loans.user_id AS user_id, from, until, note');

        //if id is given
        if (!empty($data['id'])) {
            $this->db->where('loans.id', $data['id']);
        }

        //if itemid is given
        if (!empty($data['item_id'])) {
            $this->db->where('loans.item_id', $data['item_id']);
        }

        //if item needs inclusion
        $item = FALSE;
        if (!empty($data['item']) && $data['item'] === TRUE) {
            $this->db->select('items.id AS item_id, items.created_on AS item_created_on');
            $this->db->join('items', 'items.id = loans.item_id', 'inner');
            $item = TRUE;
        }

        //if userid is given
        if (!empty($data['user_id'])) {
            $this->db->where('loans.user_id', $data['user_id']);
        }

        //if user needs inclusion
        $user = FALSE;
        if (!empty($data['user']) && $data['user'] === TRUE) {
            $this->db->select('users.id AS user_id, users.firstname AS firstname, users.lastname AS lastname, users.uid AS uid, users.role_id AS role_id');
            $this->db->join('users', 'users.id = loans.user_id', 'inner');
            $user = TRUE;
        }

        //if sort is included
        if (!empty($data['sort_on'])) {
            $this->db->order_by($data['sort_on']['column'], $data['sort_on']['order']);
        }

        //if user wants search
        if ((!empty($data['search'])) && (empty($data['id']))) {
            $this->db->group_start();
            $this->db->like('loans.id', $data['search']);
            $this->db->or_like('loans.item_id', $data['search']);
            $this->db->or_like('loans.user_id', $data['search']);
            $this->db->or_like('loans.from', $data['search']);
            $this->db->or_like('loans.until', $data['search']);
            $this->db->or_like('loans.note', $data['search']);
            if ($user) {
                $this->db->or_like('users.firstname', $data['search']);
                $this->db->or_like('users.lastname', $data['search']);
                $this->db->or_like('users.uid', $data['search']);
            }
            $this->db->group_end();
        }

        //count results
        $count = $this->db->count_all_results('loans', false);

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

            return $result;
        }

        $result['data'] = $this->db->get()->result_array();
        $result['count'] = $count;

        return $result;
    }

}