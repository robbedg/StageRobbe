<?php

/**
 * Helper to sort and sort data for synchronisation.
 * @package application\helpers\synchronization_helper
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @date 19/04/2017
 * @time 9:39
 * @filesource
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Sort incoming data and store in main database.
 * @param object $object Page that uses this function
 * @param mixed $data Data that needs to be sorted & stored
 * @return mixed FALSE or TRUE
 */
function do_upload($object, $data = FALSE) {
    $object->load->database();
    $object->load->model('location_model');
    $object->load->model('categories_model');
    $object->load->model('item_model');
    $object->load->model('usernote_model');
    $object->load->model('user_model');

    //check data
    if ($data === FALSE) {
        show_error("No data");
    }

    //different operations
    $locations_create = [];
    $categories_create = [];
    $items_create = [];
    $items_update = [];
    $items_delete = [];
    $usernotes_create = [];


    //sorting
    foreach ($data as $key => $value) {
        switch ($key) {

            //sort locations
            case 'locations':
                foreach ($value as $location) {
                    switch ($location['action']) {
                        case 'Create':
                            $locations_create[] = $location;
                            break;
                        default:
                            break;
                    }
                }
                break;

            //sort categories
            case 'categories':
                foreach ($value as $category) {
                    switch ($category['action']) {
                        case 'Create':
                            $categories_create[] = $category;
                            break;
                        default:
                            break;
                    }
                }
                break;

            //sort items
            case 'items':
                foreach ($value as $item) {
                    switch ($item['action']) {
                        case 'Create':
                            $items_create[] = $item;
                            break;
                        case 'Update':
                            $items_update[] = $item;
                            break;
                        case 'Delete':
                            $items_delete[] = $item;
                            break;
                        default:
                            break;
                    }
                }
                break;

            //sort usernotes
            case 'usernotes':
                foreach ($value as $usernote) {
                    switch ($usernote['action']) {
                        case 'Create':
                            $usernotes_create[] = $usernote;
                            break;
                        default:
                            break;
                    }
                }

            //default
            default:
                break;
        }
    }

    //to database

    //start transaction
    $object->db->trans_start();

    //create locations
    foreach ($locations_create as $location) {
        $object->location_model->set_location($location);
    }

    //create categories
    foreach ($categories_create as $category) {
        $object->categories_model->set_category($category);
    }

    //create items
    foreach ($items_create as $item) {
        $location = $object->location_model->get_location(array('name' => $item['location']));
        if ($location['count'] === 1) {
            $location = $location['data'][0]['id'];
        } else {
            $location = null;
        }

        $category = $object->categories_model->get_category(array('name' => $item['category']));
        if ($category['count'] === 1) {
            $category = $category['data'][0]['id'];
        } else {
            $category = null;
        }

        $item['location_id'] = $location;
        $item['category_id'] = $category;

        $object->item_model->set_item($item);
    }

    //update items
    foreach ($items_update as $item) {
        $location = $object->location_model->get_location(array('name' => $item['location']));
        if ($location['count'] === 1) {
            $location = $location['data'][0]['id'];
        } else {
            $location = null;
        }

        $category = $object->categories_model->get_category(array('name' => $item['category']));
        if ($category['count'] === 1) {
            $category = $category['data'][0]['id'];
        } else {
            $category = null;
        }

        $item['location_id'] = $location;
        $item['category_id'] = $category;

        $object->item_model->set_item($item);
    }

    //delete item
    foreach ($items_delete as $item) {
        $object->item_model->remove_item($item['id']);
    }

    //create usernotes
    foreach ($usernotes_create as $usernote) {
        $user_id = $object->user_model->get_user(array('uid' => $usernote['user_uid']));
        if ($user_id['count'] === 1) {
            $user_id = $user_id['data'][0]['id'];
        } else {
            $user_id = null;
        }

        $usernote['user_id'] = $user_id;

        $object->usernote_model->set_usernote($usernote);
    }

    //end transaction
    $valid = $object->db->trans_complete();

    return $valid;
 }

/**
 * Send out data for mobile application.
 * @param object $object Page that uses this function
 * @return array Data for mobile application
 */
 function do_download($object) {
     $object->load->database();
     $object->load->model('location_model');
     $object->load->model('categories_model');
     $object->load->model('item_model');
     $object->load->model('usernote_model');
     $object->load->model('loan_model');

     //create new Array
     $result = [];

     $result['locations'] = $object->location_model->get_location();
     $result['categories'] = $object->categories_model->get_category();
     $result['items'] = $object->item_model->get_item(array('location' => true, 'category' => true, 'attributes' => true, 'last_loans' => true));

     //usernotes
     $usernotes = $object->usernote_model->get_usernote(array('user' => true))['data'];

     foreach ($result['items']['data'] as $key => $item) {
         foreach ($usernotes as $usernote) {
             if ($usernote['item_id'] === $item['id']) {
                 $result['items']['data'][$key]['usernotes'][] = $usernote;
             }
         }
     }

     return $result;
 }