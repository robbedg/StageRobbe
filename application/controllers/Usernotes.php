<?php

/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 21/02/2017
 * Time: 15:50
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

    //deleting usernote
    public function remove($item_id = NULL, $note_id = NULL) {

        //when no id provided give 404
        if (empty($item_id) || empty($note_id)) {
            show_404();
        }

        $this->usernote_model->remove_usernote($note_id);
        redirect(site_url('items/view/'.$item_id));
    }
}