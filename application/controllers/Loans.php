<?php

/**
 * Controller for loans.
 * @package application\controllers
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @date 9/03/2017
 * @time 11:12
 * @filesource
 */
class Loans extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('item_model');
        $this->load->model('loan_model');
        $this->load->helper('url_helper');
        $this->load->helper('date');
        $this->load->helper('authorizationcheck_helper');
        authorization_check($this);

        //timezone = CET
        date_default_timezone_set('CET');

        //$this->output->enable_profiler(TRUE);
    }

    //show detail page of loans
    public function view($identifier = NULL, $id = NULL)
    {

        //check if correct data is given
        if(empty($identifier) || empty($id)) {
            show_404();
        }

        //set data
        $data = [];
        $data['title'] = 'Reservaties';
        //breadcrumb
        $data['breadcrum']['items'][] = array('name' => '<span class="fa fa-home"></span>', 'href' => site_url('home'));
        //header
        $data['head'][] = array('name' => 'ID', 'db' =>'id');
        //scripts
        $data['scripts'][] = base_url('js/tables/MainTable.js');

        //correct data
        switch ($identifier) {
            //when user is given
            case 'user':

                //Only administrator can view other peoples profiles.
                if (!(($id === $_SESSION['id']) || (authorization_check($this, 3)))) {
                    show_error("U kan deze actie niet uitvoeren.");
                }

                //breadcrumb
                $data['breadcrum']['items'][] = array('name' => 'Profiel', 'href' => site_url('users/'.$id));
                $data['breadcrum']['active'] = 'Reservaties';

                //header
                $data['head'][] = array('name' => 'Item ID', 'db' => 'item_id');
                $data['head'][] = array('name' => 'Locatie', 'db' => 'location');
                $data['head'][] = array('name' => 'Categorie', 'db' => 'category');

                //set hiddenfield
                $data['hiddenfields'][] = array('id' => 'user_id', 'value' => $id);

                //script
                $data['scripts'][] = base_url('js/tables/UserLoansTable.js');

                break;
            //when item is given
            case 'item':

                //get item
                $item = $this->item_model->get_item(array('id' => $id, 'location' => true, 'category' => true));

                //if id false show 404
                if (empty($item) || ($item['count'] === 0)) {
                    show_404();
                }

                $item = $item['data'];

                //breadcrumb
                $data['breadcrum']['items'][] = array('name' => 'Locatie: '.$item['location'], 'href' => site_url('categories/'.$item['location_id']));
                $data['breadcrum']['items'][] = array('name' => 'Categorie: '.$item['category'], 'href' => site_url('items/'.$item['location_id'].'/'.$item['category_id']));
                $data['breadcrum']['items'][] = array('name' => $item['name'].': '.$item['id'], 'href' => site_url('items/view/'.$item['id']));
                $data['breadcrum']['active'] = 'Reservaties';

                //header
                $data['head'][] = array('name' => 'UID', 'db' => 'uid');
                $data['head'][] = array('name' => 'Naam', 'db' => 'lastname, firstname');

                //set hiddenfield
                $data['hiddenfields'][] = array('id' => 'item_id', 'value' => $id);
                $data['hiddenfields'][] = array('id' => 'user_id', 'value' => $_SESSION['id']);

                //script
                $data['scripts'][] = base_url('js/tables/ItemLoansTable.js');
                break;
            //when input invalid
            default:
                show_404();
        }

        //header
        $data['head'][] = array('name' => 'Van', 'db' => 'from');
        $data['head'][] = array('name' => 'Tot', 'db' => 'until');

        //show
        $this->load->view('templates/header', $data);
        $this->load->view('pages/index', $data);
        $this->load->view('templates/footer');

    }

    //get loans
    public function get()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $input = json_decode($stream_clean, true);

        $items = $this->loan_model->get_loan($input);

        //output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($items));
    }

    //new loan
    public function set()
    {
        //get info
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $input = json_decode($stream_clean, true);

        $output = [];

        set_error_handler(function() {});
        try {
            if (intval($_SESSION['id']) === intval($input['user_id'])) {

                //check if everything is valid
                $result = $this->loan_model->check_availability($input);

                //when fail
                if (!$result['success']) {
                    $output['success'] = FALSE;
                    $output['errors'][] = 'The selected values are not valid.';
                } else {
                    $this->loan_model->set_loan($input);
                    $output['success'] = TRUE;
                }
            } else {
                throw new Exception();
            }
        } catch (Exception $error) {
            $output['success'] = FALSE;
            $output['errors'][] = "The request is not valid.";
        }
        restore_error_handler();

        //output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

    //return loan
    public function close($id = null)
    {
        //if id is not given return 404.
        if ($id === null) show_404();

        $result = $this->loan_model->close_loan($id);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    //delete loan
    public function delete($id = NULL)
    {
        //if id is not given return 404.
        if ($id === null) show_404();

        $result = $this->loan_model->delete_loan($id);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }
}