<?php

/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 22/02/2017
 * Time: 11:36
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function session_check($object)
{
    $object->load->library('session');
    if ($object->session->userdata('logged_in') === TRUE) {
        return true;
    } else {
        redirect('login');
        return false;
    }
}