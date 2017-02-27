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
        $this->load->helper('authorizationcheck_helper');

        authorization_check($this);

        $this->output->enable_profiler(TRUE);
    }

    //List of locations.
    public function index()
    {
        //set title
        $data['title'] = 'Locations';

        //set table head
        $data['head'][] = 'ID';
        $data['head'][] = 'Name';
        $data['head'][] = 'Amount Of Items';

        //set styles
        $data['styles'][] = 'https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css';

        //set scripts
        $data['scripts'][] = 'https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js';
        $data['scripts'][] = base_url('js/Tables.js');
        //$data['scripts'][] = base_url('js/searchscript.js');

        //set rows
        $query = $this->location_model->get_location();

        foreach ($query as $location) {
            //url for row
            $row['href'] = site_url('items/location/'.$location['id']);
            //searchable data
            $row['search'] = $location['name'];
            //data for table (eg 'head']
            $row['ID'] = $location['id'];
            $row['Name'] = $location['name'];
            $row['Amount Of Items'] = $location['item_count'];

            //insert row
            $data['rows'][] = $row;
        }

        $this->load->view('templates/header', $data);
        $this->load->view('pages/index', $data);
        $this->load->view('templates/footer', $data);
    }

    //Create new locations
    public function create() {
        //helper & library for form
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = 'New Location';

        $this->form_validation->set_rules('name', 'Name', 'required|trim|htmlspecialchars|encode_php_tags|max_length[45]');

        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/header', $data);
            $this->load->view('locations/create', $data);
            $this->load->view('templates/footer', $data);
        }
        else {
            $this->location_model->set_location();
            redirect('home');
        }
    }
}