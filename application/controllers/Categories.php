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

    //page with categories
    public function index($id = NULL) {

        if (empty($id)) {
            show_404();
        }

        $location = $this->location_model->get_location(array('id' => $id))['data']['name'];

        //set title
        $data['title'] = $location;

        //scripts
        $data['scripts'][] = base_url('js/tables/CategoriesTable.js');

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

    //Create new Category
    public function create() {
        //check permissions
        if (!authorization_check($this, 2)) {
            show_error('You are not authorized to perform this action.');
        }

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

        $categories = $this->categories_model->get_category($input);

        //output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($categories));
    }

    //update a category
    public function update($id = NULL)
    {
        //authorization check
        if (!authorization_check($this, 3)) {
            show_error('You are not authorized to perform this action.');
        }

        if (empty($id)) {
            show_404();
        } else {
            return $this->categories_model->set_category($id);
        }
    }

    //remove a location
    public function delete($id = NULL)
    {
        //authorization check
        if (!authorization_check($this, 3)) {
            show_error('You are not authorized to perform this action.');
        }

        //if empty show 404
        if (empty($id)) {
            show_404();
        } else {
            $this->categories_model->delete_category($id);
        }
    }
}