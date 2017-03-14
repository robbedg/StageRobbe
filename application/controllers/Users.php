<?php

/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 7/03/2017
 * Time: 13:07
 */
class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->helper('url_helper');
        $this->load->helper('authorizationcheck_helper');
        
        authorization_check($this);
    }

    //user profile page
    public function index($id = NULL)
    {
        //no page when empty
        if (empty($id)) {
            show_404();
        }

        //Only administrator can view other peoples profiles.
        if (!(($id === $_SESSION['id']) || (authorization_check($this, 4)))) {
            show_error("You are not authorized to perform this action.");
        }

        $this->load->view('/templates/header');
        $this->load->view('/users/index');
        $this->load->view('/templates/footer');
    }

    //handle requests for users
    public function get()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $input = json_decode($stream_clean, true);

        $data = array(
            'role_id' => $input['role_id'],
            'search' => $input['search'],
            'sort_on' => array('column' => 'lastname', 'order' => 'ASC'),
            'limit' => 10,
            'offset' => 0
        );

        $data = $this->user_model->get_user($data);

        //output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}