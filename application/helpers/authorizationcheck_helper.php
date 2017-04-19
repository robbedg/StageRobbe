<?php

/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 22/02/2017
 * Time: 11:36
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @param $object
 * @param bool $role
 * @return bool
 */
function authorization_check($object, $role = FALSE)
{
    $object->load->library('session');
    if ($object->session->userdata('logged_in') === TRUE) {
        //check role
        if (!empty($role)) {
            if ($role <= $object->session->userdata('role_id')) {
                return true;
            } else {
                return false;
            }
        }

        //if everything checks out return true
        return true;
    } else {
        redirect('login');
        return false;
    }
}