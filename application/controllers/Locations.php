<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 7/02/2017
 * Time: 9:04
 */
class Locations extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('location_model');
        $this->load->helper('url_helper');
        $this->load->helper('authorizationcheck_helper');

        authorization_check($this);

    }

    //List of locations.
    public function index()
    {
        //set title
        $data['title'] = 'Locations';

        //set table head
        $data['head'][] = 'ID';
        $data['head'][] = 'Name';
        $data['head'][] = 'Amount Of Items';

        //set styles
        $data['styles'][] = 'https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css';

        //set scripts
        $data['scripts'][] = 'https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js';
        $data['scripts'][] = base_url('js/Tables.js');
        //$data['scripts'][] = base_url('js/searchscript.js');



        $this->load->view('templates/header', $data);
        $this->load->view('pages/index', $data);
        $this->load->view('templates/footer', $data);
    }

    //Create new locations
    public function create() {
        //helper & library for form
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = 'New Location';

        $this->form_validation->set_rules('name', 'Name', 'required|trim|htmlspecialchars|encode_php_tags|max_length[45]');

        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/header', $data);
            $this->load->view('locations/create', $data);
            $this->load->view('templates/footer', $data);
        }
        else {
            $this->location_model->set_location();
            redirect('home');
        }
    }

    //get data
    public function get() {
        $input = implode($this->input->post());
        $input = json_decode($input, true);

        $queries = $this->location_model->get_location(false, $input['limit'], $input['offset']);

        $items = array();

        foreach ($queries as $query) {
            $output = array(
                'ID' => $query['id'],
                'Name' => $query['name'],
                'Amount Of Items' => $query['item_count']
            );
            $items[] = $output;
        }

        $data = array('data' => $items);

        echo json_encode($data);
    }

    //update a location
    public function update($id = NULL)
    {
        if (empty($id)) {
            show_404();
        } else {
            return $this->location_model->set_location($id);
        }
    }

    //remove a location
    public function delete($id = NULL)
    {
        //if empty show 404
        if (empty($id)) {
            show_404();
        } else {
            $this->location_model->delete_location($id);
        }
    }
}