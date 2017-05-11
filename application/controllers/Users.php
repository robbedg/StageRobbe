<?php

/**
 * Controller for users.
 * @package application\controllers
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @date 7/03/2017
 * @time 13:07
 * @filesource
 */
class Users extends CI_Controller
{
    /**
     * Users constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->helper('url_helper');
        $this->load->helper('authorizationcheck_helper');
        
        authorization_check($this);
    }

    /**
     * Shows profile page of user.
     * @param mixed $id ID of user
     */
    public function index($id = NULL)
    {
        //no page when empty
        if (empty($id)) {
            show_404();
        }

        //Only administrator can view other peoples profiles.
        if (!(($id === $_SESSION['id']) || (authorization_check($this, 4)))) {
            show_error("U kan deze actie niet uitvoeren.");
        }

        //set scripts
        $data['scripts'][] = base_url('js/moment/moment-with-locales.min.js');
        $data['scripts'][] = base_url('js/UserProfile.js');

        //set data
        $data['title'] = 'Profiel';
        $data['user_id'] = $id;

        $this->load->view('/templates/header', $data);
        $this->load->view('/users/index', $data);
        $this->load->view('/templates/footer');
    }

    /**
     * Get list of users
     */
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

    /**
     * Update a user
     */
    public function update()
    {
        //Get request data
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $input = json_decode($stream_clean, true);

        //check necessary id
        if (empty($input['id'])) {
            show_error('No id provided');
            die();
        }

        //check authorization
        if (!authorization_check($this, 3) && !($_SESSION['id'].'' === $input['id'].'')) {
            show_error('Unauthorized');
            die();
        }

        //update
        $result = $this->user_model->update_user($input);

        //output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => $result)));

    }

    /**
     * Delete user.
     */
    public function delete() {

        //Get request data
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $input = json_decode($stream_clean, true);

        //check authorisation
        if (!authorization_check($this, 3) && empty($input['id'])) {
            show_error('Unauthorized.');
        }

        //set success
        $success = FALSE;

        //check users
        $users = $this->user_model->get_user(array('id' => $input['id']));

        if ($users['count'] === 1) {
            $user = $users['data'][0];

            if($user['role_id'] < 3 || $_SESSION['role_id'] === 4) {
                $this->user_model->delete_user(array('id' => $user['id']));
                $success = TRUE;
            }
        }

        //output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => $success)));
    }
}