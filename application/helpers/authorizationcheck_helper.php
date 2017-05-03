<?php

/**
 * Helper to check if user is authorised to perform a certain action.
 * @package application\helpers\authorizationcheck_helper
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @date 22/02/2017
 * @time 11:36
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Check if user has a certain authorization level.
 * @param object $object Page that needs the check.
 * @param bool $role Minimum role of user.
 * @return bool Yes or No
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