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
        $this->load->helper('date');
        //timezone = CET
        date_default_timezone_set('CET');
    }

    //get loans
    public function get_loan($data = []) {
        $this->db->select('loans.id AS id, loans.item_id AS item_id, loans.user_id AS user_id, format_date(loans.from) AS "from_string", loans.from, format_date(loans.until) AS "until_string", loans.until, loans.note AS note');

        //if id is given
        if (!empty($data['id'])) {
            $this->db->where('loans.id', $data['id']);
        }

        //if itemid is given
        if (!empty($data['item_id'])) {
            $this->db->where('loans.item_id', $data['item_id']);
        }

        //if only current
        if (!empty($data['current']) && $data['current'] === TRUE)
        {
            if (!empty($data['date_offset'])) {
                $this->db->where('loans.until >', date_modify(new DateTime(), $data['date_offset'])->format('y-m-d H:i:s'));
            } else {
                $this->db->where('loans.until >', date('y-m-d H:i:s'));
            }
        }

        //if start_date
        if (!empty($data['start_date'])) {
            $this->db->where('loans.until >', date_modify(new DateTime($data['start_date']), $data['start_date'])->format('y-m-d H:i:s'));
        }

        //if end_date
        if (!empty($data['end_date'])) {
            $this->db->where('loans.from <', date_modify(new DateTime($data['end_date']), $data['end_date'])->format('y-m-d H:i:s'));
        }

        //if item needs inclusion
        $item = FALSE;
        if (!empty($data['item']) && $data['item'] === TRUE) {
            $this->db->select('format_date(items.created_on) AS item_created_on');
            $this->db->join('items', 'items.id = loans.item_id', 'inner');
            $this->db->select('locations.name AS location, locations.id AS location_id');
            $this->db->join('locations', 'locations.id = items.location_id', 'right outer');
            $this->db->select('categories.name AS category, categories.id AS category_id');
            $this->db->join('categories', 'categories.id = items.category_id', 'right outer');
            $item = TRUE;
        }

        //if userid is given
        if (!empty($data['user_id'])) {
            $this->db->where('loans.user_id', $data['user_id']);
        }

        //if user needs inclusion
        $user = FALSE;
        if (!empty($data['user']) && $data['user'] === TRUE) {
            $this->db->select('users.firstname AS firstname, users.lastname AS lastname, users.uid AS uid, users.role_id AS role_id');
            $this->db->join('users', 'users.id = loans.user_id', 'inner');
            $user = TRUE;
        }

        //if sort is included
        if (!empty($data['sort_on'])) {
            $this->db->order_by($data['sort_on']['column'], $data['sort_on']['order']);
        }

        //give class
        if (!empty($data['class']) && $data['class'] === TRUE) {
            $this->db->select('get_class(loans.from, loans.until) AS "class"');
        }

        //if user wants search
        if ((!empty($data['search'])) && (empty($data['id']))) {
            $this->db->group_start();
            $this->db->like('loans.id', $data['search']);
            $this->db->or_like('loans.item_id', $data['search']);
            $this->db->or_like('loans.user_id', $data['search']);
            $this->db->or_like('format_date(loans.from)', $data['search']);
            $this->db->or_like('format_date(loans.until)', $data['search']);
            $this->db->or_like('loans.note', $data['search']);
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
        $result['data'] = $this->db->get();

        //check result
        if ($result['data'] === FALSE) return FALSE;
        $result['data'] = $result['data']->result_array();
        $result['count'] = $count;

        return $result;
    }

    //set loan
    public function set_loan($data){
        $this->db->insert('loans', $data);
    }

    //check availability of item
    public function check_availability($data = [])
    {
        //return object
        $valid = [];

        $query = $this->db->query("CALL check_availability(? , ?, ?)", array($data['item_id'], $data['from'], $data['until']));

        //check if query succeeded
        if ($query === FALSE) {
            $valid['success'] = FALSE;
        }

        //check response of DB
        if ($query->row_array()['success'] == TRUE) {
            $valid['success'] = TRUE;
        } else {
            $valid['success'] = FALSE;
        }

        //free up (FIX) (stored procedure)
        $this->db->flush_cache();
        mysqli_next_result($this->db->conn_id);
        $query->free_result();

        return $valid;
    }
}