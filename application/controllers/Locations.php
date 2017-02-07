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
    }

    //List of locations.
    public function index()
    {
        $data['locations'] = $this->location_model->get_location();
        $data['title'] = 'Locations';

        $this->load->view('templates/header', $data);
        $this->load->view('locations/index', $data);
        $this->load->view('templates/footer', $data);
    }

}