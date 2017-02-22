<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 6/02/2017
 * Time: 11:30
 */

class Pages extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('location_model');
        $this->load->helper('url_helper');
        $this->load->helper('sessioncheck_helper');

        session_check($this);
    }


    //Home page
    public function view($page = 'home') {

        //Load form helpers
        $this->load->helper('form');
        $this->load->library('form_validation');

        //Validation form
        $this->form_validation->set_rules('location', 'Location', 'required');

        if (! file_exists(APPPATH.'views/pages/'.$page.'.php')) {
            show_404();
        }

        //Data for form
        $data['locations'] = $this->location_model->get_location();
        $data['title'] = 'Home';
        $data['scripts'][] = base_url('js/searchscript.js');


        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/header', $data);
            $this->load->view('pages/'.$page, $data);
            $this->load->view('templates/footer', $data);
        }
        else {
            //redirect
            $id = url_title($this->input->post('location'), 'dash', TRUE);
            redirect('/items/location/'.$id);
        }

    }
}