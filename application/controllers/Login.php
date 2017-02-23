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
}