<?php
/**
 * Model for Categories.
 * @package application\models
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @date 7/02/2017
 * @time 14:35
 * @filesource
 */
class Categories_model extends CI_Model
{
    /**
     * Categories_model constructor.
     */
    public function __construct()
    {
        $this->load->database();
    }

    /**
     * Get categories that meet conditions specified by the request data.
     * @param array $data request data
     * @return array resulting categories
     */
    public function get_category($data = [])
    {
        $this->db->select('categories.id AS id, categories.name as name, COUNT(items.id) AS item_count');
        $this->db->join('items', 'items.category_id = categories.id', 'left outer');
        $this->db->group_by('categories.id');

        //if location is given
        if (!empty($data['location_id'])) {
            $this->db->join('locations', 'locations.id = items.location_id', 'left outer');
            $this->db->where('locations.id', $data['location_id']);
        }

        //return 1 if ID is set
        if (!empty($data['id'])) {
            $this->db->where('categories.id', $data['id']);
        }

        //if name is given
        if (!empty($data['name'])) {
            $this->db->where('categories.name', $data['name']);
        }

        //if sort is included
        if (!empty($data['sort_on'])) {
            $this->db->order_by($data['sort_on']['column'], $data['sort_on']['order']);
        }

        //if user wants search
        if ((!empty($data['search'])) && (empty($data['id']))) {
            $this->db->group_start();
            $this->db->like('categories.id', $data['search']);
            $this->db->or_like('categories.name', $data['search']);
            $this->db->group_end();
        }

        $count = $this->db->count_all_results('categories', false);

        //set limit if set
        if (!empty($data['limit'])) {
            //if offset is included
            if(!empty($data['offset'])) {
                $this->db->limit($data['limit'], $data['offset']);
            } else {
                $this->db->limit($data['limit']);
            }
        }


        $query = $this->db->get();

        if ($query === FALSE) return FALSE;

        $result['data'] = $query->result_array();
        $result['count'] = $count;

        return $result;
    }

    /**
     * @param mixed $data Updating data
     * @param mixed $id ID of category
     * @return array ID of updated category
     */
    public function set_category($data = FALSE, $id = FALSE)
    {

        if (!empty($data)) {
            //filter out
            $data = array('name' => $data['name']);

            //check if unique
            $this->db->select('name');
            $this->db->where('name', $data['name']);
            $count = $this->db->count_all_results('categories');

            if ($count === 0) {
                if (empty($id)) {
                    return $this->db->insert('categories', $data);
                } else {
                    $this->db->where('id', $id);
                    $this->db->update('categories', $data);
                    return array('success' => true);
                }
            } else {
                return array('success' => false, 'errors' => array('Deze categorie bestaat al.'));
            }
        } else {
            return array('success' => false, 'errors' => array('Geen data gegeven.'));
        }
    }

    /**
     * Delete category.
     * @param mixed $id ID of category
     * @return mixed Result
     */
    public function delete_category($id = FALSE) {
        $this->db->where('id', $id);
        return $this->db->delete('categories');
    }
}