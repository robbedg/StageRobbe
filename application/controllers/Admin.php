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
        $this->load->model('user_model');
        $this->load->model('role_model');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('authorizationcheck_helper');
        $this->load->library('form_validation');
        $this->load->library('session');

        $authorized = authorization_check($this, 3);
        if (!$authorized) show_error('You are not authorized to visit this page');

        $this->output->enable_profiler(TRUE);
    }

    //Page for managing users
    public function index()
    {
        //set title
        $data['title'] = 'Admin panel';

        //set styles
        $data['styles'][] = base_url('css/admin-panel.css');

        //set scripts
        $data['scripts'][] = base_url('js/admin/AdminUsers.js');

        //Data from DB.
        //give roles
        $data['roles'] = $this->role_model->get_role()['data'];

        //set active
        $data['active'] = 'users';

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
            $this->load->view('admin/end', $data);
            $this->load->view('templates/footer');
        } else {
            $user = array(
                'id' => $this->input->post('userid'),
                'firstname' => $this->input->post('firstname'),
                'lastname' => $this->input->post('lastname'),
                'role_id' => $this->input->post('role')
            );

            if ((intval($_SESSION['role_id']) > intval($user['role_id'])) || (intval($_SESSION['role_id']) === 4)) {
                $this->user_model->update_user($user);
            } else {
                show_error("You are not authorized to do this action.");
            }

            redirect('admin');
        }
    }

    //page for managing items
    public function deleted_items()
    {
        //set title
        $data['title'] = 'Admin panel';

        //set styles
        $data['styles'][] = base_url('css/admin-panel.css');

        //set scripts
        $data['scripts'][] = base_url('js/tables/MainTable.js');
        $data['scripts'][] = base_url('js/admin/AdminDeletedItems.js');

        //active
        $data['active'] = 'deleted-items';

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

        //load views
        $this->load->view('templates/header', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('admin/deleted_items', $data);
        $this->load->view('admin/end', $data);
        $this->load->view('templates/footer');
    }

    //page for managing locations
    public function locations()
    {
        //set title
        $data['title'] = 'Admin panel';

        //set styles
        $data['styles'][] = base_url('css/admin-panel.css');

        //set scripts
        $data['scripts'][] = base_url('js/tables/MainTable.js');
        $data['scripts'][] = base_url('js/admin/AdminLocations.js');

        //active
        $data['active'] = 'locations';

        /** Locations **/
        $data['locations']['title'] = null;
        $data['locations']['head'][0]['name'] = 'Location ID';
        $data['locations']['head'][0]['db'] = 'id';
        $data['locations']['head'][1]['name'] = 'Name';
        $data['locations']['head'][1]['db'] = 'name';
        $data['locations']['head'][2]['name'] = 'Amount Of Items';
        $data['locations']['head'][2]['db'] = 'item_count';

        //load views
        $this->load->view('templates/header', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('admin/locations', $data);
        $this->load->view('admin/end', $data);
        $this->load->view('templates/footer');
    }

    //page for managing categories
    public function categories()
    {
        //set title
        $data['title'] = 'Admin panel';

        //set styles
        $data['styles'][] = base_url('css/admin-panel.css');

        //set scripts
        $data['scripts'][] = base_url('js/tables/MainTable.js');
        $data['scripts'][] = base_url('js/admin/AdminCategories.js');

        //active
        $data['active'] = 'categories';

        /** Categories **/
        $data['categories']['title'] = null;
        $data['categories']['head'][0]['name'] = 'Location ID';
        $data['categories']['head'][0]['db'] = 'id';
        $data['categories']['head'][1]['name'] = 'Name';
        $data['categories']['head'][1]['db'] = 'name';
        $data['categories']['head'][2]['name'] = 'Amount Of Items';
        $data['categories']['head'][2]['db'] = 'item_count';

        //load views
        $this->load->view('templates/header', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('admin/categories', $data);
        $this->load->view('admin/end', $data);
        $this->load->view('templates/footer');
    }

    //show for displaying statistics
    public function statistics()
    {
        //set title
        $data['title'] = 'Statistics';

        //active
        $data['active'] = 'statistics';

        //load views
        $this->load->view('templates/header', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('admin/statistics', $data);
        $this->load->view('admin/end', $data);
        $this->load->view('templates/footer');
    }
}