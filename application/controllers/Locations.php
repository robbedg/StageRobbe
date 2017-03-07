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

        //$this->output->enable_profiler(TRUE);
    }

    //List of locations.
    public function index()
    {
        //set title
        $data['title'] = 'Locations';

        //set table head
        $data['head'][0]['name'] = 'ID';
        $data['head'][0]['db'] = 'id';
        $data['head'][1]['name'] = 'Name';
        $data['head'][1]['db'] = 'name';
        $data['head'][2]['name'] = 'Amount Of Items';
        $data['head'][2]['db'] = 'item_count';

        //set scripts
        $data['scripts'][] = base_url('js/LocationsTable.js');

        $this->load->view('templates/header', $data);
        $this->load->view('pages/index', $data);
        $this->load->view('templates/footer', $data);
    }

    //Create new locations
    public function create() {
        //check permissions
        if (!authorization_check($this, 2)) {
            show_error('You are not authorized to perform this action.');
        }

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
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $input = json_decode($stream_clean, true);

        $queries = $this->location_model->get_location(false, $input['limit'], $input['offset'], $input['sorton'], $input['search']);

        $items = array();

        foreach ($queries['data'] as $query) {
            $output = array(
                'ID' => $query['id'],
                'Name' => $query['name'],
                'Amount Of Items' => $query['item_count']
            );
            $items[] = $output;
        }

        $data = array(
            'data' => $items,
            'count' => $queries['count']
        );

        //output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    //update a location
    public function update($id = NULL)
    {
        //check permissions
        if (!authorization_check($this, 3)) {
            show_error('You are not authorized to perform this action.');
        }

        if (empty($id)) {
            show_404();
        } else {
            return $this->location_model->set_location($id);
        }
    }

    //remove a location
    public function delete($id = NULL)
    {
        //check permissions
        if (!authorization_check($this, 3)) {
            show_error('You are not authorized to perform this action.');
        }

        //if empty show 404
        if (empty($id)) {
            show_404();
        } else {
            $this->location_model->delete_location($id);
        }
    }
}