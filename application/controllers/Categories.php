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
        $this->load->helper('url');
        $this->load->helper('authorizationcheck_helper');

        authorization_check($this);
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