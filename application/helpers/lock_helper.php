<?php
/**
 * Created by PhpStorm.
 * User: Robbe De Geyndt
 * Date: 27/04/2017
 * Time: 11:41
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Checks if Database is locked
 * @param $object
 * @return mixed
 */
function check_lock($object)
{
    $object->load->model('setting_model');
    $object->load->driver('cache', array('adapter' => 'wincache', 'backup' => 'apc'));

    if ($object->cache->get('database_lock') === FALSE) {
        $database_lock = $object->setting_model->get_setting('database_lock');
        $object->cache->save('database_lock', json_encode($database_lock), 3600);
    }
    
    return json_decode($object->cache->get('database_lock'));
}

/**
 * @param $object
 * @param $value
 */
function update_lock($object, $value)
{
    $object->load->model('setting_model');
    $object->load->driver('cache', array('adapter' => 'wincache', 'backup' => 'apc'));

    if (is_bool($value)) {
        $object->setting_model->set_setting('database_lock', $value);
        $this->cache->save('database_lock', json_encode($value), 3600);
    }
}