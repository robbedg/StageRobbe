<?php
/**
 * Controller for categories
 * @package application\controllers
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @date 7/02/2017
 * @time 15:16
 * @filesource
 */
class Categories extends CI_Controller
{
    /**
     * Categories constructor.
     */
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

    /**
     * Shows list of categories available in certain location.
     * @param mixed $id Id of location
     */
    public function index($id = NULL) {

        if (empty($id)) {
            show_404();
        }

        //location form DB.
        $location = $this->location_model->get_location(array('id' => $id));

        //check if exists
        if ($location['count'] !== 1) {
            show_404();
            die();
        }

        //get name
        $location = $location['data'][0]['name'];

        //set title
        $data['title'] = 'Locatie: '.$location;

        //scripts
        $data['scripts'][] = base_url('js/tables/MainTable.js');
        $data['scripts'][] = base_url('js/tables/CategoriesTable.js');

        //set breadcrum
        $home['href'] = site_url('home');
        $home['name'] = '<span class="fa fa-home"></span>';

        $data['breadcrum']['items'][] = $home;
        $data['breadcrum']['active'] = $data['title'];

        //set header
        $data['head'][] = array('name' => 'Categorie ID', 'db' => 'id');
        $data['head'][] = array('name' => 'Naam', 'db' => 'name');
        $data['head'][] = array('name' => 'Aantal Items', 'db' => 'item_count');

        //set hiddenfield
        $data['hiddenfields'][0]['id'] = 'location_id';
        $data['hiddenfields'][0]['value'] = $id;

        $this->load->view('templates/header', $data);
        $this->load->view('pages/index', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Create a new category
     */
    public function create() {
        //check permissions
        if (!authorization_check($this, 2)) {
            show_error('U kan deze actie niet uitvoeren.');
        }

        //helper & library for form
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = 'Nieuwe Categorie';

        $this->form_validation->set_rules('name', 'Name', 'required|trim|htmlspecialchars|encode_php_tags|max_length[45]');

        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/header', $data);
            $this->load->view('categories/create', $data);
            $this->load->view('templates/footer');
        }
        else {
            //get info from post
            $data = array(
                'name' => $this->input->post('name')
            );

            $this->categories_model->set_category($data);
            redirect('home');
        }
    }

    /**
     * Handle requests to get lists of categories.
     */
    public function get()
    {
        $categories = [];
        try {
            $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
            $input = json_decode($stream_clean, true);

            $categories = $this->categories_model->get_category($input);

            if ($categories === FALSE) throw new Exception();

            $categories['success'] = TRUE;
        } catch (Exception $e) {
            $categories['success'] = FALSE;
        }

        //output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($categories));
    }

    /**
     * Rename a category.
     * @param mixed $id ID of category
     * @return mixed success
     */
    public function update($id = NULL)
    {
        //output
        $output = [];

        //authorization check
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

            $output = $this->categories_model->set_category($data, $id);
        }

        //output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

    /**
     * Delete a category
     * @param mixed $id ID of category
     */
    public function delete($id = NULL)
    {
        //authorization check
        if (!authorization_check($this, 3)) {
            show_error('U kan deze actie niet uitvoeren.');
        }

        //if empty show 404
        if (empty($id)) {
            show_404();
        } else {
            $this->categories_model->delete_category($id);
        }
    }
}