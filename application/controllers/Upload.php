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

    public function do_upload($id) {

        $data['title'] = 'upload';
        $data['scripts'][] = base_url('dropzone/dropzone.min.js');
        $data['scripts'][] = base_url('js/Upload.js');
        $data['styles'][] = base_url('dropzone/basic.min.css');
        $data['styles'][] = base_url('dropzone/dropzone.min.css');

        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 500; //KB
        //$config['max_width']            = 1024;
        //$config['max_height']           = 768;
        //$config['encrypt_name']         = TRUE;
        $config['file_name']            = $id;
        $config['overwrite']            = TRUE;

        //delete old image
        $files = glob('./uploads/'.$id.'*');
        foreach ($files as $file) {
            unlink($file);
        }

        //upload new image
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('file'))
        {
            $error = array('error' => $this->upload->display_errors());
            return $error;
        }
        else
        {
            return 'success';
        }
    }


}