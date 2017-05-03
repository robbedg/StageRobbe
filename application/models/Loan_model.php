<?php

/**
 * Model for loan objects.
 * @package models
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @date 9/03/2017
 * @time 10:28
 * @filesource
 */
class Loan_model extends CI_Model
{
    /**
     * loan_model constructor.
     */
    public function __construct()
    {
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('date');
        //timezone = CET
        date_default_timezone_set('CET');
    }

    /**
     * Get loans based on data from user.
     * @param array $data data from user
     * @return mixed result or FALSE
     */
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

    /**
     * Set new loan.
     * @param $data user data for loan
     */
    //set loan
    public function set_loan($data){
        $this->db->insert('loans', $data);
    }

    /**
     * Checks if loan proposal of user is available
     * @param array $data data for loan
     * @return array TRUE or FALSE
     */
    public function check_availability($data = [])
    {
        //return object
        $valid = [];

        //query
        $this->db->select('loans.from, loans.until');
        $this->db->where('loans.item_id', $data['item_id']);
        $this->db->where('loans.until >=', date_format(new DateTime(), 'Y-m-d H:i:s'));

        //check if time window OK
        $this->db->not_group_start();
        $this->db->group_start();
        $this->db->where('loans.until <=', $data['from']);
        $this->db->where('loans.until <', $data['until']);
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('loans.from >', $data['from']);
        $this->db->where('loans.from >=', $data['until']);
        $this->db->group_end();
        $this->db->group_end();

        $conflicts = $this->db->count_all_results('loans');

        $noconfilcts = FALSE;
        if ($conflicts === 0) $noconfilcts = TRUE;

        $correctorder = FALSE;
        if (date_create($data['from']) < date_create($data['until'])) $correctorder = TRUE;

        $current = FALSE;
        if (date_create($data['from']) >= date_create()) $current = TRUE;

        $valid['success']  = $noconfilcts && $correctorder && $current;

        //return value
        return $valid;
    }


    /**
     * End loan when loan still active.
     * @param $id id of loan
     * @return array success
     */
    public function close_loan($id)
    {
        //set result
        $result = [];

        $this->db->select('id, until, from');
        $this->db->where('id', $id);

        $loanquery = $this->db->get('loans');

        if ($loanquery === FALSE) {
            $result['success'] = FALSE;
            $result['errors'][] = 'ID does not exist';
            return $result;
        }

        //get loan
        $loan = $loanquery->row_array();

        //check user
        if (($_SESSION['id'] !== $id) && !($_SESSION['role_id'] >= 2)) {
            $result['success'] = FALSE;
            $result['errors'][] = 'You are not authorize to perform this action.';
            return $result;
        }

        //check date
        if (!(date_create($loan['from']) < date_create() && date_create($loan['until']) > date_create())) {
            $result['success'] = FALSE;
            $result['errors'][] = 'The loan is not active.';
            return $result;
        }

        //remove loan
        $this->db->where('id', $id);
        $this->db->update('loans', array('until' => date_create()->format('Y-m-d H:i:s')));
    }

    /**
     * Delete loan before use.
     * @param $id id of loan
     * @return array success
     */
    public function delete_loan($id)
    {
        //set result
        $result = [];

        $this->db->select('id', 'until');
        $this->db->where('id', $id);

        $loanquery = $this->db->get('loans');

        if ($loanquery === FALSE) {
            $result['success'] = FALSE;
            $result['errors'][] = 'ID does not exist';
            return $result;
        }

        //get loan
        $loan = $loanquery->row_array();

        //check user
        if (($_SESSION['id'] !== $id) && !($_SESSION['role_id'] >= 2)) {
            $result['success'] = FALSE;
            $result['errors'][] = 'You are not authorize to perform this action.';
            return $result;
        }

        //check date
        if (date_create($loan['from']) < date_create()) {
            $result['success'] = FALSE;
            $result['errors'][] = 'Loan already past or is already active';
            return $result;
        }

        //remove loan
        $this->db->delete('loans', array('id' => $id));
    }
}