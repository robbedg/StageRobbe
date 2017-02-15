<?php

/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 15/02/2017
 * Time: 9:47
 */
class Upload extends CI_Controller
{
    public function  __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
    }

    public function index()
    {
        $data['title'] = 'upload';
        $data['scripts'][] = site_url('../dropzone/dropzone.min.js');
        $data['scripts'][] = site_url('../js/Upload.js');
        $data['styles'][] = site_url('../dropzone/basic.min.css');
        $data['styles'][] = site_url('../dropzone/dropzone.min.css');

        $this->load->view('templates/header', $data);
        $this->load->view('upload/upload_form', array('error' => ' '));
        $this->load->view('templates/footer', $data);
    }

    public function do_upload() {

        $data['title'] = 'upload';
        $data['scripts'][] = site_url('../dropzone/dropzone.min.js');
        $data['scripts'][] = site_url('../js/Upload.js');
        $data['styles'][] = site_url('../dropzone/basic.min.css');
        $data['styles'][] = site_url('../dropzone/dropzone.min.css');

        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $config['encrypt_name']         = TRUE;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('file'))
        {
            $error = array('error' => $this->upload->display_errors());
            var_dump($error);

            $this->load->view('templates/header', $data);
            $this->load->view('upload/upload_form', $error);
            $this->load->view('templates/footer', $data);
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $data['title'] = 'upload';

            $this->load->view('templates/header', $data);
            $this->load->view('upload/success', $data);
            $this->load->view('templates/footer', $data);
        }
    }


}