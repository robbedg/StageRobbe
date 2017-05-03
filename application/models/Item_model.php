<?php
/**
 * Model for items.
 * @package application\models\Item_model
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @Date 7/02/2017
 * @time 9:36
 * @filesource
 */
class Item_model extends CI_Model
{
    /**
     * Item_model constructor.
     */
    public function __construct()
    {
        $this->load->database();
        $this->load->helper('url');
    }

    /**
     * Get items based on given data.
     * @param array $data search parameters
     * @return mixed result or FALSE
     */
    public function get_item($data = [])
    {
        //base query
        $this->db->select('items.id AS id, format_date(items.created_on) AS created_on');

        //deleted items?
        if (!empty($data['deleted']) && $data['deleted'] === TRUE) {
            $this->db->where('items.visible', 0);
        } else {
            $this->db->where('items.visible', 1);
        }

        //if location is given
        if (!empty($data['location_id'])) {
            $this->db->where('items.location_id', $data['location_id']);
        }

        //display location
        $location = FALSE;
        if (!empty($data['location']) && $data['location'] === TRUE) {
            $this->db->select('locations.id AS location_id, locations.name AS location');
            $this->db->join('locations', 'locations.id = items.location_id', 'left outer');
            $location = TRUE;
        }

        //if category is given
        if (!empty($data['category_id'])) {
            $this->db->where('items.category_id', $data['category_id']);
        }

        //display category
        $category = FALSE;
        if (!empty($data['category']) && $data['category'] === TRUE) {
            $this->db->select('categories.id AS category_id, categories.name AS category');
            $this->db->join('categories', 'categories.id = items.category_id', 'left outer');
            $category = TRUE;
        }

        //display attributes (string)
        if (!empty($data['attributes']) && $data['attributes'] === TRUE) {
            $this->db->select('items.attributes AS attributes');
        }

        //include last loans
        if (!empty($data['last_loans']) && $data['last_loans'] === TRUE) {
            $this->db->select('format_date(last_loans.from) AS last_loan_from, format_date(last_loans.until) AS last_loan_until');
            $this->db->select('last_loans.firstname AS last_loan_firstname, last_loans.lastname AS last_loan_lastname');
            $this->db->join('last_loans', 'last_loans.item_id = items.id', 'left');
        }

        //return 1 if ID is set
        if (!empty($data['id'])) {
            $this->db->select('items.attributes AS attributes');
            $this->db->where('items.id', $data['id']);
        }

        //if sort is included
        if (!empty($data['sort_on'])) {
            $this->db->order_by($data['sort_on']['column'], $data['sort_on']['order']);
        }

        //if user wants search
        if ((!empty($data['search'])) && (empty($data['id']))) {
            $this->db->group_start();
            $this->db->like('items.id', $data['search']);
            $this->db->or_like('format_date(items.created_on)', $data['search']);
            $this->db->or_like('items.attributes', $data['search']);
            if ($location) {
                $this->db->or_like('locations.id', $data['search']);
                $this->db->or_like('locations.name', $data['search']);
            }
            if ($category) {
                $this->db->or_like('categories.id', $data['search']);
                $this->db->or_like('categories.name', $data['search']);
            }
            $this->db->group_end();
        }

        $count = $this->db->count_all_results('items', false);

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
            $result['data'] = $this->db->get();

            //check if valid
            if ($result['data'] === FALSE) return FALSE;
            $result['data'] = $result['data']->row_array();

            $result['count'] = $count;

            $result['data']['attributes'] = json_decode($result['data']['attributes'], true);
            //Get picture
            $picture = glob('./uploads/'.$data['id'].'*');
            if (!empty($picture)) {
                $picture = '.'.$picture[0];
                $result['data']['image'] = $picture;
            }
            else {
                $result['data']['image'] = NULL;
            }

            return $result;
        }

        $result['data'] = $this->db->get();

        //check if valid
        if ($result['data'] === FALSE) return FALSE;
        $result['data'] = $result['data']->result_array();

        if (!empty($data['attributes']) && $data['attributes'] === TRUE) {
            foreach ($result['data'] as $key => $value) {
                $result['data'][$key]['attributes'] = json_decode($result['data'][$key]['attributes']);
            }
        }

        $result['count'] = $count;

        return $result;
    }

    /**
     * Update or create item with given data.
     * @param $data user given data
     * @return mixed id of item
     */
    public function set_item($data){

        //set
        $this->db->set('category_id', $data['category_id']);
        $this->db->set('location_id', $data['location_id']);
        $this->db->set('attributes', json_encode($data['attributes']));

        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('items');
            return $data['id'];
        }

        $this->db->insert('items');
        return $this->db->insert_id();
    }

    /**
     * Puts visibility of item to 0.
     * @param bool $id id of item
     */
    public function remove_item($id = FALSE) {
        //delete old image
        $files = glob('./uploads/'.$id.'*');
        foreach ($files as $file) {
            unlink($file);
        }
        //query
        $this->db->set('visible', 0);
        $this->db->where('id', $id);
        $this->db->update('items');
    }

    /**
     * Puts visibility of item to 1.
     * @param bool $id id of item
     * @return mixed id of item
     */
    public function restore_item($id = FALSE)
    {
        $data = array(
            'visible' => 1
        );

        $this->db->where('id', $id);
        return $this->db->update('items', $data);
    }

    /**
     * Permanently delete item from database.
     * @param bool $id item id
     * @return mixed id of item
     */
    public function delete_item($id = FALSE)
    {
        $this->db->where('id', $id);
        return $this->db->delete('items');
    }
}