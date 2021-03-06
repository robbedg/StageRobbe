<?php

/**
 * Controller for settings.
 * @package application\controllers
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @date 2/05/2017
 * @time 13:53
 * @filesource
 */
class Settings extends CI_Controller
{
    /**
     * Settings constructor.
     */
     public function __construct()
     {
         parent::__construct();
         $this->load->model('setting_model');
         $this->load->helper('authorizationcheck_helper');
         $this->load->library('session');

         $authorized = authorization_check($this, 3);
         if (!$authorized) show_error('You are not authorized to access this function');
     }

     public function get() {
         //outcome
         $result = $this->setting_model->get_setting();

         //output
         $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
     }

    /**
     * Set settings
     */
     public function set() {
         //outcome
         $result = [];

         try {
             $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
             $input = json_decode($stream_clean, true);

             $result['success'] = $this->setting_model->set_setting($input);

         } catch (Exception $e) {
             $result['success'] = FALSE;
         }

         //output
         $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($result));
     }

}