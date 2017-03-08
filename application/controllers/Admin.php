<?php

/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 22/02/2017
 * Time: 15:18
 */
class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('item_model');
        $this->load->model('user_model');
        $this->load->model('role_model');
        $this->load->model('location_model');
        $this->load->model('categories_model');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('authorizationcheck_helper');
        $this->load->library('form_validation');

        $authorized = authorization_check($this, 3);
        if (!$authorized) show_error('You are not authorized to visit this page');

        $this->output->enable_profiler(TRUE);
    }

    public function index() {
        //set title
        $data['title'] = 'Admin panel';

        //set styles
        $data['styles'][] = 'https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css';

        //set scripts
        $data['scripts'][] = 'https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js';
        $data['scripts'][] = base_url('js/Admin-Users.js');
        $data['scripts'][] = base_url('js/AdminUsers.js');
        $data['scripts'][] = base_url('js/AdminDeletedItems.js');
        //set styles
        $data['styles'][] = base_url('css/admin-panel.css');

        //Data from DB.
        //give roles
        $data['roles'] = $this->role_model->get_role()['data'];
        //give locations
        $data['locations'] = $this->location_model->get_location();
        //give categories
        $data['categories'] = $this->categories_model->get_category();

        /** Deleted Items **/
        $data['deleted_items']['title'] = null;
        $data['deleted_items']['head'][0]['name'] = 'Item ID';
        $data['deleted_items']['head'][0]['db'] = 'id';
        $data['deleted_items']['head'][1]['name'] = 'Location';
        $data['deleted_items']['head'][1]['db'] = 'location';
        $data['deleted_items']['head'][2]['name'] = 'Category';
        $data['deleted_items']['head'][2]['db'] = 'category';
        $data['deleted_items']['head'][3]['name'] = 'Created On';
        $data['deleted_items']['head'][3]['db'] = 'created_on';


        //validation rules
        $this->form_validation->set_rules('userid', 'User ID', 'required|trim|htmlspecialchars|encode_php_tags');
        $this->form_validation->set_rules('firstname', 'First name', 'required|trim|htmlspecialchars|encode_php_tags|max_length[45]');
        $this->form_validation->set_rules('lastname', 'Last name', 'required|trim|htmlspecialchars|encode_php_tags|max_length[45]');
        $this->form_validation->set_rules('role', 'Role', 'required|trim|htmlspecialchars|encode_php_tags');



        if ($this->form_validation->run() === FALSE) {
            //load views
            $this->load->view('templates/header', $data);
            $this->load->view('admin/index', $data);
            $this->load->view('admin/users', $data);
            $this->load->view('admin/deleted_items', $data);
            $this->load->view('admin/locations', $data);
            $this->load->view('admin/categories', $data);
            $this->load->view('admin/end', $data);
            $this->load->view('templates/footer');
        } else {
            $user = array(
                'id' => $this->input->post('userid'),
                'firstname' => $this->input->post('firstname'),
                'lastname' => $this->input->post('lastname'),
                'role_id' => $this->input->post('role')
            );

            $this->user_model->update_user($user);

            redirect('admin');
        }
    }
}