<?php
/**
 * Controller for items.
 * @package application\controllers
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @date 7/02/2017
 * @time 10:19
 * @filesource
 */
class Items extends CI_Controller
{
    /**
     * Items constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('item_model');
        $this->load->model('location_model');
        $this->load->model('categories_model');
        $this->load->model('setting_model');
        $this->load->helper('url_helper');
        $this->load->helper('authorizationcheck_helper');

        //check auth
        authorization_check($this);

        //$this->output->enable_profiler(TRUE);
    }

    /**
     * Show items in given category & location.
     * @param mixed $locationid ID of location
     * @param mixed $categoryid ID of category
     */
    public function index($locationid = NULL, $categoryid = NULL) {

        //show 404 without parameters
        if ((empty($locationid)) || (empty($categoryid))) {
            show_404();
        }

        //get names from database
        $location = $this->location_model->get_location(array('id' => $locationid));
        $category = $this->categories_model->get_category(array('id' => $categoryid));

        //check if exists
        if ($location['count'] !== 1 || $category['count'] !== 1) {
            show_404();
            die();
        }

        //set title
        $data['title'] = 'Categorie: '.$category['data'][0]['name'];

        //set scripts
        //$data['scripts'][] = base_url('js/jquery-qrcode-0.14.0.min.js');
        $data['scripts'][] = base_url('js/qrcode.min.js');
        $data['scripts'][] = base_url('js/tables/MainTable.js');
        $data['scripts'][] = base_url('js/tables/ItemsTable.js');

        //set breadcrum
        $home['href'] = site_url('home');
        $home['name'] = '<span class="fa fa-home"></span>';
        $data['breadcrum']['items'][] = $home;

        $bread_location['href'] = site_url('categories/'.$locationid);
        $bread_location['name'] = 'Locatie: '.$location['data'][0]['name'];
        $data['breadcrum']['items'][] = $bread_location;

        $data['breadcrum']['active'] = 'Categorie: '.$category['data'][0]['name'];

        //set header
        $data['head'][] = array('name' => 'Item ID', 'db' => 'id');
        $data['head'][] = array('name' => 'Naam', 'db' => 'name');
        $data['head'][] = array('name' => 'Aangemaakt Op', 'db' => 'created_on');

        //set hiddenfield
        $data['hiddenfields'][] = array('id' => 'location_id', 'value' => $locationid);
        $data['hiddenfields'][] = array('id' => 'category_id', 'value' => $categoryid);

        $this->load->view('templates/header', $data);
        $this->load->view('pages/index', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Detailed view of an item.
     * @param mixed $id ID of item
     */
    public function view($id = NULL)
    {
        //when no id specified
        if (empty($id)) {
            show_404();
        }

        //load styles
        $data['styles'][] = base_url('css/bootstrap-datetimepicker.min.css');
        $data['styles'][] = base_url('vis/vis.min.css');

        //load scripts
        $data['scripts'][] = base_url('js/moment/moment-with-locales.min.js');
        $data['scripts'][] = base_url('js/bootstrap-datetimepicker.min.js');
        $data['scripts'][] = base_url('vis/vis.min.js');

        $data['scripts'][] = base_url('js/qrcode.min.js');

        $data['scripts'][] = base_url('js/itemview/ItemViewDateTimePicker.js');
        $data['scripts'][] = base_url('js/itemview/ItemView.js');
        $data['scripts'][] = base_url('js/itemview/UserNote.js');
        $data['scripts'][] = base_url('js/itemview/SetLoan.js');
        $data['scripts'][] = base_url('js/itemview/ReportIssue.js');

        //collect data
        $data['item'] = $this->item_model->get_item(array('id' => $id, 'location' => TRUE, 'category' => TRUE));

        //check
        if ($data['item']['count'] !== 1) {
            show_404();
            die();
        }

        $data['title'] = $data['item']['data']['name'].': '.$data['item']['data']['id'];

        $data['database_lock'] = $this->setting_model->get_setting('database_lock');

        //load view
        $this->load->view('templates/header', $data);
        $this->load->view('items/view', $data);
        $this->load->view('templates/footer', $data);


    }

    /**
     * Create or update item.
     * @param null $id id of item
     */
    public function create($id = NULL) {
        //database lock
        $database_lock = $this->setting_model->get_setting('database_lock');

        if (!authorization_check($this, 2) || $database_lock) {
            show_error('U kan deze actie niet uitvoeren.');
        }

        //helper & library for form
        $this->load->helper('form');
        $this->load->library('form_validation');

        if (!empty($id)) {
            $data['title'] = 'Item Bewerken';

            //get item
            $data['item'] = $this->item_model->get_item(array('id' => $id, 'category' => TRUE, 'location' => TRUE));

            //check if item exists
            if ($data['item']['count'] !== 1) {
                show_404();
                die();
            }

        } else {
            $data['title'] = 'Nieuw Item';
            $data['item'] = array();
        }

        //data for form
        $data['locations'] = $this->location_model->get_location();
        $data['categories'] = $this->categories_model->get_category();

        $data['scripts'][] = base_url('jquery.validation/jquery.validate.min.js');
        $data['scripts'][] = base_url('jquery.validation/localization/messages_nl.min.js');
        $data['scripts'][] = base_url('dropzone/dropzone.min.js');
        $data['scripts'][] = base_url('js/Upload.js');
        $data['scripts'][] = base_url('js/ItemFormScripts.js');
        $data['scripts'][] = base_url('js/RemoveItem.js');

        $data['styles'][] = 'https://cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/theme-default.min.css';
        $data['styles'][] = base_url('dropzone/basic.min.css');
        $data['styles'][] = base_url('dropzone/dropzone.min.css');

        //rules for form
        $this->form_validation->set_rules('name', 'Name', 'required|trim|htmlspecialchars|encode_php_tags|max_length[45]');
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
            $data = $this->input->post();

            //get attributes set by user
            $attributes = Array();
            if (!empty($data['label'])) {
                foreach ($data['label'] as $index => $label) {
                    $attributes[$label] = $data['value'][$index];
                }
            }

            $input = array('id' => $data['id'], 'name' => $data['name'], 'category_id' => $data['category'], 'location_id' => $data['location'], 'attributes' => $attributes);

            //to db
            $id  = $this->item_model->set_item($input);

            redirect('items/create/'.$id);
        }
    }

    /**
     * Handle requests to get list of items.
     */
    public function get()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $input = json_decode($stream_clean, true);

        $result = [];
        $items = $this->item_model->get_item($input);

        if ($items === FALSE) {
            $result['success'] = FALSE;
        } else {
            $result = $items;
            $result['success'] = TRUE;
        }

        //output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    /**
     * Make item invisible, delete picture if exists.
     * @param mixed $id ID of item
     */
    public function remove($id = NULL) {
        //authorization check
        if (!authorization_check($this, 2)) {
            show_error('U kan deze actie niet uitvoeren.');
        }

        //when no id provided give 404
        if (empty($id)) {
            show_404();
        }

        $this->item_model->remove_item($id);
        redirect('home');
    }

    /**
     * Make item visible again.
     * @param mixed $id ID of item
     */
    public function  restore($id = NULL)
    {
        //authorization check
        if (!authorization_check($this, 3)) {
            show_error('U kan deze actie niet uitvoeren.');
        }

        //check if not empty
        if (empty($id)) {
            show_404();
        }

        $this->item_model->restore_item($id);
    }

    /**
     * Permanently delete item (cascade)
     * @param mixed $id ID of item
     */
    public function delete($id = NULL)
    {
        //authorization check
        if (!authorization_check($this, 3)) {
            show_error('U kan deze actie niet uitvoeren.');
        }

        if (empty($id)) {
            show_404();
        }

        $this->item_model->delete_item($id);
    }

    /**
     * Report item if there is a problem.
     * @param mixed $id ID of item
     * @param int $set Normally 1, when 0 item gets unreported
     */
    public function report($id = NULL, $set = 1) {
        //check if 0
        if ($set === "0") $set = 0;

        //check if valid
        if (empty($id)) show_404();

        //regular
        if ($set === 1) {
            //update
            $this->item_model->set_item(array('id' => $id, 'issue' => 1));

            //output
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('success' => true)));
        }

        //reverse
        else if ($set === 0) {
            //auth
            if (!authorization_check($this, 2)) show_error('U kan deze actie niet uitvoeren.');
            //update
            $this->item_model->set_item(array('id' => $id, 'issue' => 0));

            //output
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('success' => true)));
        }

        //none
        else {
            //output
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('success' => false)));
        }
    }

}