<?php
/**
 * Controller for locations.
 * @package application\controllers
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @date 7/02/2017
 * @time 9:04
 * @filesource
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
        $data['title'] = 'Locaties';

        //set table head
        $data['head'][] =  array('name' => 'Locatie ID', 'db' => 'id');
        $data['head'][] = array('name' => 'Naam', 'db' => 'name');
        $data['head'][] = array('name' => 'Aantal Items', 'db' => 'item_count');

        //set scripts
        $data['scripts'][] = base_url('js/tables/MainTable.js');
        $data['scripts'][] = base_url('js/tables/LocationsTable.js');

        $this->load->view('templates/header', $data);
        $this->load->view('pages/index', $data);
        $this->load->view('templates/footer', $data);
    }

    //Create new locations
    public function create() {
        //check permissions
        if (!authorization_check($this, 2)) {
            show_error('U kan deze actie niet uitrvoeren.');
        }

        //helper & library for form
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = 'Nieuwe Locatie';

        $this->form_validation->set_rules('name', 'Name', 'required|trim|htmlspecialchars|encode_php_tags|max_length[45]');

        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/header', $data);
            $this->load->view('locations/create', $data);
            $this->load->view('templates/footer', $data);
        }
        else {
            //get info from post
            $data = array(
                'name' => $this->input->post('name')
            );

            $this->location_model->set_location($data);
            redirect('home');
        }
    }

    //get data
    public function get() {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $input = json_decode($stream_clean, true);

        $locations = [];

        $result = $this->location_model->get_location($input);

        if ($locations === FALSE) {
            $locations['success'] = FALSE;
        } else {
            $locations = $result;
            $locations['success'] = TRUE;
        }

        //output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($locations));
    }

    //update a location
    public function update($id = NULL)
    {
        //output
        $output = [];

        //check permissions
        if (!authorization_check($this, 3)) {
            show_error('U kan deze actie niet uitvoeren.');
        }

        if (empty($id)) {
            show_404();
        } else {
            //get info from post
            $data = array(
                'name' => $this->input->post('name')
            );
            $output = $this->location_model->set_location($data, $id);
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

    //remove a location
    public function delete($id = NULL)
    {
        //check permissions
        if (!authorization_check($this, 3)) {
            show_error('U kan deze actie niet uitvoeren.');
        }

        //if empty show 404
        if (empty($id)) {
            show_404();
        } else {
            $this->location_model->delete_location($id);
        }
    }
}