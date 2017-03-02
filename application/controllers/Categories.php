<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 7/02/2017
 * Time: 15:16
 */

class Categories extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('categories_model');
        $this->load->model('location_model');
        $this->load->helper('url');
        $this->load->helper('authorizationcheck_helper');

        authorization_check($this);

        //$this->output->enable_profiler(TRUE);
    }

    public function index($id = NULL) {

        if (empty($id)) {
            show_404();
        }

        $location = $this->location_model->get_location($id)['data']['name'];

        //set title
        $data['title'] = $location;

        //scripts
        $data['scripts'][] = base_url('js/CategoriesTable.js');

        //set breadcrum
        $home['href'] = site_url('home');
        $home['name'] = 'Home';

        $data['breadcrum']['items'][] = $home;
        $data['breadcrum']['active'] = $data['title'];

        //set header
        $data['head'][0]['name'] = 'Category ID';
        $data['head'][0]['db'] = 'id';
        $data['head'][1]['name'] = 'Category';
        $data['head'][1]['db'] = 'name';
        $data['head'][2]['name'] = 'Amount Of Items';
        $data['head'][2]['db'] = 'item_count';

        //set hiddenfield
        $data['hiddenfields'][0]['id'] = 'location_id';
        $data['hiddenfields'][0]['value'] = $id;

        $this->load->view('templates/header', $data);
        $this->load->view('pages/index', $data);
        $this->load->view('templates/footer', $data);
    }

    //Create new Itemtype
    public function create() {
        //helper & library for form
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = 'New Category';

        $this->form_validation->set_rules('name', 'Name', 'required|trim|htmlspecialchars|encode_php_tags|max_length[45]');

        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/header', $data);
            $this->load->view('categories/create', $data);
            $this->load->view('templates/footer');
        }
        else {
            $this->categories_model->set_category();
            redirect('home');
        }
    }

    //handle requests for locations
    public function get()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $input = json_decode($stream_clean, true);

        $queries = $this->categories_model->get_category(false, $input['location_id'], $input['limit'], $input['offset'], $input['sorton'], $input['search']);

        $items = array();

        foreach ($queries['data'] as $query) {
            $output = array(
                'Category ID' => $query['id'],
                'Category' => $query['name'],
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

    //update a category
    public function update($id = NULL)
    {
        if (empty($id)) {
            show_404();
        } else {
            return $this->categories_model->set_category($id);
        }
    }

    //remove a location
    public function delete($id = NULL)
    {
        //if empty show 404
        if (empty($id)) {
            show_404();
        } else {
            $this->categories_model->delete_category($id);
        }
    }
}