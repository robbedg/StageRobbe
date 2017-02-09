<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 9/02/2017
 * Time: 9:29
 */
Class Search extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('location_model');
        $this->load->model('item_model');
        $this->load->helper('url_helper');

    }
    public function index(){
        $search = $this->input->post('search');
        $type = $this->input->post('type');

        $query = $this->location_model->search_location($search);
        echo json_encode($query);
    }
}