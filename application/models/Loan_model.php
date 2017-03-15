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
        $this->db->select('loans.id AS id, loans.item_id AS item_id, loans.user_id AS user_id, from, until, note');

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
            $this->db->select('items.created_on AS item_created_on');
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
            if ($item) {
                $this->db->or_like('categories.name');
                $this->db->or_like('locations.name');
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

    //set loan
    public function set_loan($data){
        $this->db->insert('loans', $data);
    }

    //check availability of item
    public function check_availability($data = [])
    {
        $this->db->select('from, until');
        $this->db->from('loans');
        $this->db->where('item_id', $data['item_id']);

        $result = $this->db->get()->result_array();

        //return object
        $valid = [];

        //get dates
        $from = date_create($data['from']);
        $until = date_create($data['until']);
        $now = date_create();

        //check if current
        $current = TRUE;
        if (
            !(
                ($from >= $now) &&
                ($until >= $now)
            )
        ) {
            $valid['errors'][] = 'Cannot loan object in the past.';
            $current = FALSE;
        }

        //check if order is valid
        $order = TRUE;
        if (
            !($from < $until)
        ) {
            $valid['errors'][] = 'From needs to be before Until.';
            $order = FALSE;
        }

        //check if no interference with existing.
        $noClip = TRUE;
        foreach ($result as $timespan) {
            $startSpan = date_create($timespan['from']);
            $endSpan = date_create($timespan['until']);

            if (
                !(
                    ($from >= $endSpan && $until > $endSpan) ||
                    ($from < $startSpan && $until <= $startSpan)
                )
            ) {
                $valid['errors'][] = 'Item is not available in chosen time frame.';
                $noClip = FALSE;
                break;
            }
        }

        //return result
        $valid['success'] = ($current && $order && $noClip);
        return $valid;
    }
}