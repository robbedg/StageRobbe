<?php

/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 22/02/2017
 * Time: 10:28
 */
class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('user_model');
        $this->load->helper('url_helper');
    }

    //login page
    public function index()
    {
        //If userdata is available
        if (!empty($this->input->server('USER_UID'))
            && !empty($this->input->server('USER_EMPLOYEENUMBER'))
            && !empty($this->input->server('USER_FIRSTNAME'))
            && !empty($this->input->server('USER_LASTNAME'))
            && !empty($this->input->server('USER_MAIL'))
        )
        {
            $userdata = array(
                'uid' => $this->input->server('USER_UID'),
                'firstname' => $this->input->server('USER_FIRSTNAME'),
                'lastname' => $this->input->server('USER_LASTNAME')
            );

            //get internal id
            $userinfo = $this->user_model->set_user($userdata);
            //add to userdata
            $userdata['id'] = $userinfo['id'];
            $userdata['role_id'] = $userinfo['role_id'];
            $userdata['logged_in'] = TRUE;

            $this->session->set_userdata($userdata);
            redirect('home');
        } else {
            show_error('You are not logged in.');
        }
    }

    //login page (password)
    public function login()
    {
        //This method will have the credentials validation
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if($this->form_validation->run() === FALSE)
        {
            //Field validation failed.  User redirected to login page
            $this->load->view('login/login');
        }
        else
        {
            $this->check_database();
        }

    }

    function check_database()
    {
        //Field validation succeeded.  Validate against database
        $userdata = $this->input->post();

        //query the database
        $result = $this->user_model->login($userdata['username'], $userdata['password']);


        if ($result) {
            $user = $this->user_model->get_user(array('uid' => $userdata['username']));

            if ($user['count'] !== 1) {
                show_error('Database failure.');
            }

            $user = $user['data'][0];

            $sessioninfo = [];
            $sessioninfo['id'] = $user['id'];
            $sessioninfo['uid'] = $user['uid'];
            $sessioninfo['firstname'] = $user['firstname'];
            $sessioninfo['lastname'] = $user['lastname'];
            $sessioninfo['role_id'] = $user['role_id'];
            $sessioninfo['logged_in'] = TRUE;

            $this->session->set_userdata($sessioninfo);
            redirect('home');
        }

        $data = [];
        $this->load->view('login/login', $data);
    }
}