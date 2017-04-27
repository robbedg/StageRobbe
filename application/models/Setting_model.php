<?php

/**
 * Created by PhpStorm.
 * User: Robbe De Geyndt
 * Date: 27/04/2017
 * Time: 10:58
 */
class Setting_model extends CI_Model
{
    public function __construct()
    {
        $this->load->helper('file');
    }

    //get setting
    public function get_setting($key = NULL) {
        $settings = json_decode(file_get_contents('./settings.json'), true);

        if (!empty($key) && is_string($key) && array_key_exists($key, $settings)) {
            return $settings[$key];
        } else {
            return NULL;
        }
    }

    //set setting
    public function  set_setting($key = NULL, $value = NULL) {
        $settings = json_decode(file_get_contents('./settings.json'), true);

        if (!empty($key) && !empty($value) && is_string($key)) {
            $settings['$key'] = $value;
            return write_file('./settings.json', json_encode($settings, JSON_PRETTY_PRINT));
        }
    }
}