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
        $this->load->model('categories_model');
        $this->load->model('usernote_model');
        $this->load->helper('url_helper');
        $this->load->helper('authorizationcheck_helper');

        authorization_check($this);

        $this->output->enable_profiler(TRUE);
    }

    //List of Items
    public function index()
    {
        $data['items'] = $this->item_model->get_item();
        $data['title'] = 'All items';
        $data['scripts'][] = base_url('js/searchscript.js');

        $this->load->view('templates/header', $data);
        $this->load->view('items/index', $data);
        $this->load->view('templates/footer', $data);
    }

    //List of items in specified location
    public function bylocation($id = NULL)
    {
        //get items
        $query = $this->item_model->get_item_by_location($id);
        $location = $this->location_model->get_location($id)['name'];

        //set title
        $data['title'] = $location;

        //scripts
        $data['scripts'][] = base_url('js/searchscript.js');

        //set breadcrum
        $home['href'] = site_url('home');
        $home['name'] = 'Home';

        $data['breadcrum']['items'][] = $home;
        $data['breadcrum']['active'] = $data['title'];

        //set header
        $data['head'][] = '#';
        $data['head'][] = 'Category';
        $data['head'][] = 'Location';

        foreach ($query as $category) {
            //set link
            $row['href'] = site_url('items/detail/'.$category['location_id'].'/'.$category['category_id']);
            //set searchable string
            $row['search'] = $category['category'];
            //set data
            $row['#'] = $category['count'];
            $row['Category'] = $category['category'];
            $row['Location'] = $category['location'];

            //add to rows
            $data['rows'][] = $row;
        }


        $this->load->view('templates/header', $data);
        $this->load->view('pages/index', $data);
        $this->load->view('templates/footer', $data);
    }

    //all objects in defined category
    public function detail($locationid, $categoryid)
    {
        //get items
        $query = $data['items'] = $this->item_model->get_item_by_catagory($locationid, $categoryid);
        $location = $this->location_model->get_location($locationid);
        $category = $this->categories_model->get_category($categoryid);

        //set title
        $data['title'] = $category['name'];

        //set scripts
        $data['scripts'][] = base_url('js/searchscript.js');

        //set breadcrum
        $home['href'] = site_url('home');
        $home['name'] = 'Home';
        $data['breadcrum']['items'][] = $home;

        $bread_location['href'] = site_url('items/location/'.$locationid);
        $bread_location['name'] = $location['name'];
        $data['breadcrum']['items'][] = $bread_location;

        $data['breadcrum']['active'] = $category['name'].' collection';

        //set header
        $data['head'][] = 'ID';
        $data['head'][] = 'Created on';
        $data['head'][] = 'Category';
        $data['head'][] = 'Location';

        //set rows
        foreach ($query as $item) {
            //row link
            $row['href'] = site_url('items/view/'.$item['item_id']);
            //set searchable string
            $row['search'] = $item['item_id'];
            //set data
            $row['ID'] = $item['item_id'];
            $row['Created on'] = (new DateTime($item['created_on']))->format('d/m/Y H:i');
            $row['Category'] = $item['category'];
            $row['Location'] = $item['location'];
            //add row to rows
            $data['rows'][] = $row;
        }

        $this->load->view('templates/header', $data);
        $this->load->view('pages/index', $data);
        $this->load->view('templates/footer', $data);
    }

    //Detailed view of one item
    public function view($id = NULL)
    {
        //helper & library for form
        $this->load->helper('form');
        $this->load->library('form_validation');

        //load scripts
        $data['scripts'][] = base_url('js/CountScript.js');

        //collect data
        $data['item'] = $this->item_model->get_item($id);
        $data['usernotes'] = $this->usernote_model->get_usernotes_by_item($id);

        $data['title'] = $data['item']['category'].': '.$data['item']['id'];

        //validation rules
        $this->form_validation->set_rules('item_id', 'Item ID', 'required|trim|htmlspecialchars|encode_php_tags');
        $this->form_validation->set_rules('comment', 'Text', 'required|trim|htmlspecialchars|encode_php_tags|max_length[1024]');

        if ($this->form_validation->run() === FALSE)
        {
            //load view
            $this->load->view('templates/header', $data);
            $this->load->view('items/view', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {
            $this->usernote_model->set_usernote();
            redirect('items/view/'.$id);
        }
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
        $data['categories'] = $this->categories_model->get_category();

        $data['scripts'][] = base_url('jquery.validation/jquery.validate.min.js');
        $data['scripts'][] = base_url('dropzone/dropzone.min.js');
        $data['scripts'][] = base_url('js/Upload.js');
        $data['scripts'][] = base_url('js/ItemFormScripts.js');
        $data['scripts'][] = base_url('js/RemoveItem.js');

        $data['styles'][] = 'https://cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/theme-default.min.css';
        $data['styles'][] = base_url('dropzone/basic.min.css');
        $data['styles'][] = base_url('dropzone/dropzone.min.css');

        //rules for form
        $this->form_validation->set_rules('category', 'Category', 'required|trim|htmlspecialchars|encode_php_tags');
        $this->form_validation->set_rules('location', 'Location', 'required|trim|htmlspecialchars|encode_php_tags');
        $this->form_validation->set_rules('label[]', 'Label', 'trim|htmlspecialchars|encode_php_tags');
        $this->form_validation->set_rules('value[]', 'Value', 'trim|htmlspecialchars|encode_php_tags');

        //validation
        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/header', $data);
            $this->load->view('items/create', $data);
            $this->load->view('templates/footer');
        }
        else {
            $id  = $this->item_model->set_item();
            redirect('items/create/'.$id);
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

    //restoring item
    public function  restore($id = NULL)
    {
        if (empty($id)) {
            show_404();
        }

        $this->item_model->restore_item($id);
    }

    //permenantly deleting item
    public function delete($id = NULL)
    {
        if (empty($id)) {
            show_404();
        }

        $this->item_model->delete_item($id);
    }
}