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
        $this->load->model('location_model');
        $this->load->model('itemtypes_model');
        $this->load->helper('url_helper');
    }

    //List of Items
    public function index()
    {
        $data['items'] = $this->item_model->get_item();
        $data['title'] = 'All items';

        $this->load->view('templates/header', $data);
        $this->load->view('items/index', $data);
        $this->load->view('templates/footer', $data);
    }

    //List of items in specified location
    public function bylocation($id = NULL)
    {
        $data['items'] = $this->item_model->get_item_by_location($id);

        if (empty($data['items'])) {
            show_404();
        }

        $data['title'] = array_values(array_slice($data['items'], -1))[0]['location'];

        $this->load->view('templates/header', $data);
        $this->load->view('items/index', $data);
        $this->load->view('templates/footer', $data);
    }

    //Detailed view of one item
    public function view($id = NULL)
    {
        $data['item'] = $this->item_model->get_item($id);
        $data['title'] = $data['item']['itemtype'];

        $this->load-> view('templates/header', $data);
        $this->load->view('items/view', $data);
        $this->load->view('templates/footer', $data);
    }

    public function create() {
        //helper & library for form
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = 'New Item';
        $data['locations'] = $this->location_model->get_location();
        $data['itemtypes'] = $this->itemtypes_model->get_itemtype();

        $this->form_validation->set_rules('itemtype', 'Itemtype', 'required');
        $this->form_validation->set_rules('location', 'Location', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/header', $data);
            $this->load->view('items/create');
            $this->load->view('templates/footer');
        }
        else {
            $this->item_model->set_item();
            redirect('home');
        }
    }
}