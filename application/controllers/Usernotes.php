<?php

/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 21/02/2017
 * Time: 15:50
 */
class Usernotes extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('usernote_model');
        $this->load->helper('url_helper');
    }

    //deleting usernote
    public function remove($item_id = NULL, $note_id = NULL) {

        //when no id provided give 404
        if (empty($item_id) || empty($note_id)) {
            show_404();
        }

        $this->usernote_model->remove_usernote($note_id);
        redirect(site_url('items/view/'.$item_id));
    }
}