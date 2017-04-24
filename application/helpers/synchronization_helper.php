<?php

/**
 * Created by PhpStorm.
 * User: Robbe De Geyndt
 * Date: 19/04/2017
 * Time: 9:39
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function do_upload($object, $data = FALSE) {
    $object->load->database();
    $object->load->model('location_model');
    $object->load->model('categories_model');
    $object->load->model('item_model');

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

            //default
            default:
                break;
        }
    }

    //to database

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
        $location = $location['data'][0]['id'];

        $category = $object->categories_model->get_category(array('name' => $item['category']));
        $category = $category['data'][0]['id'];

        $item['location_id'] = $location;
        $item['category_id'] = $category;

        $object->item_model->set_item($item);
    }

    //update items
    foreach ($items_update as $item) {
        $location = $object->location_model->get_location(array('name' => $item['location']));
        $location = $location['data'][0]['id'];

        $category = $object->categories_model->get_category(array('name' => $item['category']));
        $category = $category['data'][0]['id'];

        $item['location_id'] = $location;
        $item['category_id'] = $category;

        $object->item_model->set_item($item);
    }

    //delete item
    foreach ($items_delete as $item) {
        $object->item_model->remove_item($item['id']);
    }
 }

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
     $result['items'] = $object->item_model->get_item(array('location' => true, 'category' => true, 'attributes' => true));

     //usernotes
     foreach ($result['items']['data'] as $key => $item) {
         $result['items']['data'][$key]['usernotes'] = $object->usernote_model->get_usernote(array('item_id' => $item['id'], 'user' => true))['data'];
     }

     //last user

     foreach ($result['items']['data'] as $key => $item) {
         $result['items']['data'][$key]['last_user'] = $object->loan_model->get_loan(array('item_id' => $item['id'], 'past' => true, 'user' => true, 'sort_on' => array('column' => 'from', 'order' => 'DESC'), 'limit' => '1'))['data'][0];
     }

     return $result;
 }