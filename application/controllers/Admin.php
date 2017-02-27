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
        $this->load->helper('sessioncheck_helper');

        session_check($this);

        $this->output->enable_profiler(TRUE);
    }

    public function index() {
        //set title
        $data['title'] = 'Admin panel';
        //set scripts
        $data['scripts'][] = base_url('js/Admin-Users.js');
        //set styles
        $data['styles'][] = base_url('css/admin-panel.css');
        //give users
        $data['users'] = $this->user_model->get_user();
        //give roles
        $data['roles'] = $this->role_model->get_role();

        $this->load->view('templates/header', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('admin/users', $data);
        $this->load->view('templates/footer');
    }

    public function updateuser($id = NULL) {

    }
}