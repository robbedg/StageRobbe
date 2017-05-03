<?php

/**
 * Controller for users.
 * @package application\controllers\Users
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @date 7/03/2017
 * @time 13:07
 * @filesource
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

        //set scripts
        $data['scripts'][] = base_url('js/moment/moment-with-locales.min.js');
        $data['scripts'][] = base_url('js/UserProfile.js');

        //set data
        $data['title'] = 'Profile';
        $data['user_id'] = $id;

        $this->load->view('/templates/header', $data);
        $this->load->view('/users/index', $data);
        $this->load->view('/templates/footer');
    }

    //handle requests for users
    public function get()
    {
        $return = [];

        //no errors (PHP)
        set_error_handler(function() {});
        try {
            $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
            $input = json_decode($stream_clean, true);

            $results = $this->user_model->get_user($input);

            //break if query= false
            if ($results === FALSE) throw new Exception();

            $return = $results;
            $return['success'] = TRUE;
        } catch (Exception $e) {
            $return['success'] = FALSE;
        }
        restore_error_handler();

        //output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($return));
    }
}