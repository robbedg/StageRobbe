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
        $this->output->enable_profiler(TRUE);
    }

    //List of Items
    public function index()
    {
        $data['items'] = $this->item_model->get_item();
        $data['title'] = 'All items';
        $data['scripts'][] = site_url('../js/searchscript.js');

        $this->load->view('templates/header', $data);
        $this->load->view('items/index', $data);
        $this->load->view('templates/footer', $data);
    }

    //List of items in specified location
    public function bylocation($id = NULL)
    {
        $data['items'] = $this->item_model->get_item_by_location($id);
        //page title
        $data['title'] = $this->location_model->get_location($id)['name'];
        $data['scripts'][] = site_url('../js/searchscript.js');

        $this->load->view('templates/header', $data);
        $this->load->view('items/index', $data);
        $this->load->view('templates/footer', $data);
    }

    //Detailed view of one item
    public function view($id = NULL)
    {
        $data['item'] = $this->item_model->get_item($id);
        $data['title'] = $data['item']['itemtype'].': '.$data['item']['id'];

        $this->load->view('templates/header', $data);
        $this->load->view('items/view', $data);
        $this->load->view('templates/footer', $data);
    }

    //all objects in defined category
    public function detail($locationid, $itemtypeid)
    {
        $data['items'] = $this->item_model->get_item_by_catagory($locationid, $itemtypeid);
        $data['location'] = $this->location_model->get_location($locationid);
        $data['itemtype'] = $this->itemtypes_model->get_itemtype($itemtypeid);
        $data['title'] = $data['itemtype']['name'].' collection';

        $this->load->view('templates/header', $data);
        $this->load->view('items/detail', $data);
        $this->load->view('templates/footer', $data);
    }

    //creating/updating item
    public function create($id = NULL) {
        //helper & library for form
        $this->load->helper('form');
        $this->load->library('form_validation');

        if (!empty($id)) {
            $data['title'] = 'Edit Item';
            $data['item'] = $this->item_model->get_item($id);
        } else {
            $data['title'] = 'New Item';
            $data['item'] = array();
        }

        //data for form
        $data['locations'] = $this->location_model->get_location();
        $data['itemtypes'] = $this->itemtypes_model->get_itemtype();
        $data['scripts'][] = site_url('../js/ItemFormScripts.js');
        $data['scripts'][] = site_url('../js/RemoveItem.js');

        //rules for form
        $this->form_validation->set_rules('itemtype', 'Itemtype', 'required');
        $this->form_validation->set_rules('location', 'Location', 'required');

        //validation
        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/header', $data);
            $this->load->view('items/create', $data);
            $this->load->view('templates/footer');
        }
        else {
            $this->item_model->set_item();
            redirect('home');
        }
    }

    //deleting item
    public function remove($id = NULL) {

        //when no id provided give 404
        if (empty($id)) {
            show_404();
        }

        $this->item_model->remove_item($id);
        redirect('home');
    }
}