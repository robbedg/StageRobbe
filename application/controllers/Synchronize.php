<?php

/**
 * Controller for synchronization.
 * @package application\controllers\Synchronize
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @date 19/04/2017
 * @time 9:13
 */
class Synchronize extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->helper('synchronization_helper');
        $this->load->helper('authorizationcheck_helper');


    }

    public function upload() {
        $result = [];

        //check permissions
        if (!authorization_check($this, 3)) {
            $result['success'] = FALSE;
        } else {
            $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
            $input = json_decode($stream_clean, true);

            $result['success'] = do_upload($this, $input);
        }


        $this->output
            ->set_header('Access-Control-Allow-Origin: http://applive.local') //only for browser testing
            ->set_header('Access-Control-Allow-Headers: Origin, Content-Type')
            ->set_header('Access-Control-Allow-Credentials: true')
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    public function download() {
        $result = [];

        //check permissions
        if (!authorization_check($this, 3)) {
            $result['success'] = FALSE;
        } else {
            $result = do_download($this);
        }

        $this->output
            ->set_header('Access-Control-Allow-Origin: http://applive.local') //only for browser testing
            ->set_header('Access-Control-Allow-Headers: Origin, Content-Type')
            ->set_header('Access-Control-Allow-Credentials: true')
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

}