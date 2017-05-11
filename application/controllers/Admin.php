<?php

/**
 * Controller for Admin panel.
 * @package application\controllers
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @date 22/02/2017
 * @time 15:18
 * @filesource
 */
class Admin extends CI_Controller
{
    /**
     * Admin constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('role_model');
        $this->load->model('setting_model');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('authorizationcheck_helper');
        $this->load->library('form_validation');
        $this->load->library('session');

        //authorization
        $authorized = authorization_check($this, 3);
        if (!$authorized) show_error('You are not authorized to visit this page');


        $this->output->enable_profiler(TRUE);
    }

    /**
     * Managing users.
     * @link /index.php/admin
     */
    public function index()
    {
        //set title
        $data['title'] = 'Administratie';

        //set styles
        $data['styles'][] = base_url('css/admin-panel.css');

        //set scripts
        $data['scripts'][] = base_url('js/admin/AdminUsers.js');

        //Data from DB.
        //give roles
        $data['roles'] = $this->role_model->get_role()['data'];

        //set active
        $data['active'] = 'users';

        //database lock
        $data['database_lock'] = $this->setting_model->get_setting('database_lock');

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

            //check original user
            $original_user = $this->user_model->get_user(array('id' => $user['id'], 'limit' => 1));

            if ($original_user['count'] !== 1) {
                show_error('Onbekende gebruiker.');
                die();
            }

            $original_user = $original_user['data'][0];

            if (
                ((intval($_SESSION['role_id']) > intval($user['role_id'])) || (intval($_SESSION['role_id']) === 4)) &&
                (intval($original_user['role_id']) < intval($_SESSION['role_id']) || intval($_SESSION['role_id']) === 4)
            ) {
                $this->user_model->update_user($user);
            } else {
                show_error("U kan deze actie niet uitvoeren.");
            }

            redirect('admin');
        }
    }

    /**
     * Managing reported item.
     */
    public function reported_items()
    {
        //set title
        $data['title'] = 'Administratie';

        //set styles
        $data['styles'][] = base_url('css/admin-panel.css');

        //scripts
        $data['scripts'][] = base_url('js/tables/MainTable.js');
        $data['scripts'][] = base_url('js/admin/AdminReportedItems.js');

        //active
        $data['active'] = 'reported-items';

        //database lock
        $data['database_lock'] = $this->setting_model->get_setting('database_lock');

        /** Reported Items */
        $data['reported_items']['title'] = null;
        $data['reported_items']['head'][] = array('name' => 'Item ID', 'db' => 'id');
        $data['reported_items']['head'][] = array('name' => 'Naam', 'db' => 'name');
        $data['reported_items']['head'][] = array('name' => 'Locatie', 'db' => 'location');
        $data['reported_items']['head'][] = array('name' => 'Categorie', 'db' => 'category');
        $data['reported_items']['head'][] = array('name' => 'Aangemaakt Op', 'db' => 'created_on');

        //load views
        $this->load->view('templates/header', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('admin/reported_items', $data);
        $this->load->view('admin/end', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Managing deleted items.
     */
    public function deleted_items()
    {
        //set title
        $data['title'] = 'Administratie';

        //set styles
        $data['styles'][] = base_url('css/admin-panel.css');

        //set scripts
        $data['scripts'][] = base_url('js/tables/MainTable.js');
        $data['scripts'][] = base_url('js/admin/AdminDeletedItems.js');

        //active
        $data['active'] = 'deleted-items';

        //database lock
        $data['database_lock'] = $this->setting_model->get_setting('database_lock');

        /** Deleted Items **/
        $data['deleted_items']['title'] = null;
        $data['deleted_items']['head'][] = array('name' => 'Item ID', 'db' => 'id');
        $data['deleted_items']['head'][] = array('name' => 'Naam', 'db' => 'name');
        $data['deleted_items']['head'][] = array('name' => 'Locatie', 'db' => 'location');
        $data['deleted_items']['head'][] = array('name' => 'Categorie', 'db' => 'category');
        $data['deleted_items']['head'][] = array('name' => 'Aangemaakt Op', 'db' => 'created_on');

        //load views
        $this->load->view('templates/header', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('admin/deleted_items', $data);
        $this->load->view('admin/end', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Managing locations.
     */
    public function locations()
    {
        //set title
        $data['title'] = 'Administratie';

        //set styles
        $data['styles'][] = base_url('css/admin-panel.css');

        //set scripts
        $data['scripts'][] = base_url('js/tables/MainTable.js');
        $data['scripts'][] = base_url('js/admin/AdminLocations.js');

        //active
        $data['active'] = 'locations';

        //database lock
        $data['database_lock'] = $this->setting_model->get_setting('database_lock');

        /** Locations **/
        $data['locations']['title'] = null;
        $data['locations']['head'][] = array('name' => 'Locatie ID', 'db' => 'id');
        $data['locations']['head'][] = array('name' => 'Naam', 'db' => 'name');
        $data['locations']['head'][] = array('name' => 'Aantal Items', 'db' => 'item_count');

        //load views
        $this->load->view('templates/header', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('admin/locations', $data);
        $this->load->view('admin/end', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Managing categories.
     */
    public function categories()
    {
        //set title
        $data['title'] = 'Administratie';

        //set styles
        $data['styles'][] = base_url('css/admin-panel.css');

        //set scripts
        $data['scripts'][] = base_url('js/tables/MainTable.js');
        $data['scripts'][] = base_url('js/admin/AdminCategories.js');

        //active
        $data['active'] = 'categories';

        //database lock
        $data['database_lock'] = $this->setting_model->get_setting('database_lock');

        /** Categories **/
        $data['categories']['title'] = null;
        $data['categories']['head'][] = array('name' => 'Locatie ID', 'db' => 'id');
        $data['categories']['head'][] = array('name' => 'Naam', 'db' => 'name');
        $data['categories']['head'][] = array('name' => 'Aantal Items', 'db' => 'item_count');

        //load views
        $this->load->view('templates/header', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('admin/categories', $data);
        $this->load->view('admin/end', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Displaying statistics.
     */
    public function statistics()
    {
        //set title
        $data['title'] = 'Admin Panel';

        //set styles
        $data['styles'][] = base_url('css/admin-panel.css');

        //set scripts
        $data['scripts'][] = 'https://www.gstatic.com/charts/loader.js';
        $data['scripts'][] = base_url('js/admin/AdminStatistics.js');

        //active
        $data['active'] = 'statistics';

        //database lock
        $data['database_lock'] = $this->setting_model->get_setting('database_lock');

        //load views
        $this->load->view('templates/header', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('admin/statistics', $data);
        $this->load->view('admin/end', $data);
        $this->load->view('templates/footer');
    }

    /**
     * General application settings.
     */
    public function general()
    {
        //set title
        $data['title'] = 'Admin Panel';

        //set styles
        $data['styles'][] = base_url('css/admin-panel.css');

        //set scripts
        $data['scripts'][] = base_url('js/admin/AdminGeneral.js');

        //active
        $data['active'] = 'general';

        //database lock
        $data['database_lock'] = $this->setting_model->get_setting('database_lock');

        //load views
        $this->load->view('templates/header', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('admin/general', $data);
        $this->load->view('admin/end', $data);
        $this->load->view('templates/footer');
    }
}