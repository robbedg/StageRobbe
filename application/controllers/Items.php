<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 7/02/2017
 * Time: 10:19
 */
class Items extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('item_model');
        $this->load->helper('url_helper');
    }

    public function index()
    {
        $data['items'] = $this->item_model->get_item();
        $data['title'] = 'All items';

        $this->load->view('templates/header', $data);
        $this->load->view('items/index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function bylocation($id = NULL)
    {
        $data['items'] = $this->item_model->get_item_by_location($id);
        $data['title'] = array_values(array_slice($data['items'], -1))[0]['location'];

        $this->load->view('templates/header', $data);
        $this->load->view('items/index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function view($id = NULL)
    {
        $data['item'] = $this->item_model->get_item($id);
        $data['title'] = $data['item']['itemtype'];

        $this->load-> view('templates/header', $data);
        $this->load->view('items/view', $data);
        $this->load->view('templates/footer', $data);
    }
}