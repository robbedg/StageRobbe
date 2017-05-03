<?php

/**
 * Controller for user notes.
 * @package controllers
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @date 21/02/2017
 * @time 15:50
 * @filesource
 */
class Usernotes extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('usernote_model');
        $this->load->helper('url_helper');
        $this->load->helper('authorizationcheck_helper');

        authorization_check($this);
    }

    //get usernote
    public function get() {
        //results
        $results = [];

        //no errors
        set_error_handler(function() {});
        try {
            //get data from JSON
            $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
            $input = json_decode($stream_clean, true);
            $results = $this->usernote_model->get_usernote($input);

            if (!$results) {
                throw new Exception();
            }

            $results['success'] = TRUE;
        } catch (Exception $error) {
            $results['success'] = FALSE;
        }
        restore_error_handler();

        //output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($results));
    }

    //Create new usernote
    public function set() {

        //no errors
        set_error_handler(function() {});
        try {
            //get data from JSON
            $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
            $input = json_decode($stream_clean, true);

            //User can only post under own name
            if ($input['user_id'] !== ($_SESSION['id'])) {
                throw new Exception();
            }

            $result['success'] = $this->usernote_model->set_usernote($input);

            if (!$result['success']) {
                throw new Exception();
            }

            $result['success'] = TRUE;
        } catch (Exception $error) {
            $result['success'] = FALSE;
        }
        //errors
        restore_error_handler();

        //output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    //deleting usernote
    public function remove($note_id = NULL) {

        //when no id provided give 404
        if (empty($note_id)) {
            show_404();
        }

        $response = $this->usernote_model->remove_usernote($note_id);

        $result = [];
         if ($response === TRUE) {
             $result['success'] = TRUE;
         } else {
             $result['success'] = FALSE;
         }

         //output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }
}