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