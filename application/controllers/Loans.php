<?php

/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 9/03/2017
 * Time: 11:12
 */
class Loans extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('loan_model');
        $this->load->helper('url_helper');
        $this->load->helper('authorizationcheck_helper');
        authorization_check($this);

        //$this->output->enable_profiler(TRUE);
    }

    //get loans
    public function get() {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $input = json_decode($stream_clean, true);

        $items = $this->loan_model->get_loan($input);

        if (empty($input['id'])) {
            foreach ($items['data'] as $key => $item) {

                $items['data'][$key]['from'] = (new DateTime($item['from']))->format('d/m/Y h:i');
                $items['data'][$key]['until'] = (new DateTime($item['until']))->format('d/m/Y h:i');
                if (!empty($items['data'][$key]['item_created_on'])) {
                    $items['data'][$key]['item_created_on'] = (new DateTime($item['item_created_on']))->format('d/m/Y h:i');
                }
            }
        } else {
            $items['data']['from'] = (new DateTime($items['data']['from']))->format('d/m/Y h:i');
        }

        //output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($items));
    }

    //new loan
    public function set() {
        //get info
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $input = json_decode($stream_clean, true);

        $output = [];

        set_error_handler(function() {});
        try {
            if (intval($_SESSION['id']) === intval($input['user_id'])) {
                $this->loan_model->set_loan($input);
                $output['success'] = TRUE;
            } else {
                throw new Exception();
            }
        } catch (Exception $error) {
            $output['error'] = "The request is not valid.";
        }
        restore_error_handler();

        //output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }
}