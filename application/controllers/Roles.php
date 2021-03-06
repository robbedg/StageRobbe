<?php

/**
 * Controller for Roles.
 * @package application\controllers
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @Date 7/03/2017
 * @Time 14:54
 * @filesource
 */
class Roles extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('role_model');
        $this->load->helper('url_helper');
        $this->load->helper('authorizationcheck_helper');

        authorization_check($this);

        //$this->output->enable_profiler(TRUE);
    }

    //get role(s)
    public function get() {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $input = json_decode($stream_clean, true);

        $data = $this->role_model->get_role($input);

        //output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}