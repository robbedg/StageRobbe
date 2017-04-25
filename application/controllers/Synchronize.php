<?php

/**
 * Created by PhpStorm.
 * User: Robbe De Geyndt
 * Date: 19/04/2017
 * Time: 9:13
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

            do_upload($this, $input);
            $result['success'] = TRUE;
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