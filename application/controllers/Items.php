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
        $this->load->helper('form');
        $this->load->library('form_validation');

        authorization_check($this);

        //$this->output->enable_profiler(TRUE);
    }

    //show items in category & location
    public function index($locationid = NULL, $categoryid = NULL) {
        //show 404 without parameters
        if ((empty($locationid)) || (empty($categoryid))) {
            show_404();
        }

        $location = $this->location_model->get_location(array('id' => $locationid));
        $category = $this->categories_model->get_category(array('id' => $categoryid));

        //set title
        $data['title'] = $category['data']['name'].' collection';

        //set scripts
        $data['scripts'][] = base_url('js/tables/ItemsTable.js');

        //set breadcrum
        $home['href'] = site_url('home');
        $home['name'] = 'Home';
        $data['breadcrum']['items'][] = $home;

        $bread_location['href'] = site_url('categories/'.$locationid);
        $bread_location['name'] = $location['data']['name'];
        $data['breadcrum']['items'][] = $bread_location;

        $data['breadcrum']['active'] = $category['data']['name'].' collection';

        //set header
        $data['head'][0]['name'] = 'Item ID';
        $data['head'][0]['db'] = 'id';
        $data['head'][1]['name'] = 'Created On';
        $data['head'][1]['db'] = 'created_on';

        //set hiddenfield
        $data['hiddenfields'][0]['id'] = 'location_id';
        $data['hiddenfields'][0]['value'] = $locationid;
        $data['hiddenfields'][1]['id'] = 'category_id';
        $data['hiddenfields'][1]['value'] = $categoryid;

        $this->load->view('templates/header', $data);
        $this->load->view('pages/index', $data);
        $this->load->view('templates/footer', $data);
    }

    //Detailed view of one item
    public function view($id = NULL)
    {
        //when no id specified
        if (empty($id)) {
            show_404();
        }

        //load scripts
        $data['scripts'][] = base_url('js/CountScript.js');

        //collect data
        $data['item'] = $this->item_model->get_item(array('id' => $id, 'location' => TRUE, 'category' => TRUE));
        $data['usernotes'] = $this->usernote_model->get_usernotes_by_item($id);

        $data['title'] = $data['item']['data']['category'].': '.$data['item']['data']['id'];

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

        if (!authorization_check($this, 2)) {
            show_error('You are not authorized to perform this action.');
        }

        //helper & library for form
        $this->load->helper('form');
        $this->load->library('form_validation');

        if (!empty($id)) {
            $data['title'] = 'Edit Item';
            $data['item'] = $this->item_model->get_item(array('id' => $id));
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

    //handle requests for items
    public function get()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $input = json_decode($stream_clean, true);

        $items = $this->item_model->get_item($input);

        foreach ($items['data'] as $key => $item) {
            $items['data'][$key]['created_on'] = (new DateTime($item['created_on']))->format('d/m/Y h:i');
        }

        //output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($items));
    }

    //deleting item
    public function remove($id = NULL) {
        //authorization check
        if (!authorization_check($this, 2)) {
            show_error('You are not authorized to perform this action.');
        }

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
        //authorization check
        if (!authorization_check($this, 3)) {
            show_error('You are not authorized to perform this action.');
        }

        //check if not empty
        if (empty($id)) {
            show_404();
        }

        $this->item_model->restore_item($id);
    }

    //permenantly deleting item
    public function delete($id = NULL)
    {
        //authorization check
        if (!authorization_check($this, 3)) {
            show_error('You are not authorized to perform this action.');
        }

        if (empty($id)) {
            show_404();
        }

        $this->item_model->delete_item($id);
    }
}