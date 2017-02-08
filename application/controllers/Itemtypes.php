<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 7/02/2017
 * Time: 15:16
 */

class Itemtypes extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('itemtypes_model');
    }

    //Create new Itemtype
    public function create() {
        //helper & library for form
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = 'New Itemtype';

        $this->form_validation->set_rules('name', 'Name', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/header', $data);
            $this->load->view('itemtypes/create', $data);
            $this->load->view('templates/footer');
        }
        else {
            $this->itemtypes_model->set_itemtype();
            redirect('home');
        }
    }
}