<?php

/**
 * Created by PhpStorm.
 * User: Robbe De Geyndt
 * Date: 27/04/2017
 * Time: 10:58
 */
class Setting_model extends CI_Model
{
    /**
     * Setting_model constructor.
     */
    public function __construct()
    {
        $this->load->helper('file');
        $this->load->helper('authorizationcheck_helper');
        $this->load->driver('cache', array('adapter' => 'wincache', 'backup' => 'apc'));
    }

    /**
     * Get settings, stored in cache, if expired, read from settings file.
     * @param null $key get setting with key
     * @return null value of setting
     */
    public function get_setting($key = NULL) {
        //settings
        $settings = [];

        //renew cache if expired
        if ($this->cache->get('settings') === FALSE) {
            $settings = json_decode(file_get_contents('./settings.json'), true);
            $this->cache->save('settings',$settings, 3600);
        }

        //get settings from cache
        $settings = $this->cache->get('settings');

        if (!empty($key) && is_string($key) && array_key_exists($key, $settings)) {
            return $settings[$key];
        } else {
            return $settings;
        }
    }

    /**
     * Set settings, store in cache and settings file.
     * @param array $data
     * @return bool success
     * @internal param null $key store with key
     * @internal param null $value store value
     */
    public function set_setting($data = []) {

        $settings = json_decode(file_get_contents('./settings.json'), true);

        //nothing if no data is given
        if (empty($data)) return false;

        //go over each key
        foreach ($data as $key => $value) {
            if (!empty($key) && !is_null($value) && is_string($key)) {
                //add or change
                $settings[$key] = $value;
                //write to cache
                $this->cache->save('settings', $settings, 3600);
                //write to file
                write_file('./settings.json', json_encode($settings, JSON_PRETTY_PRINT));
            }
        }

        return true;
    }
}