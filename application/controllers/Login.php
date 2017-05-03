<?php

/**
 * Controller for logging in/registering
 * @package application\controllers\Login
 * @author Robbe De Geyndt <robbe.degeyndt@student.odisee.be>
 * @date 22/02/2017
 * @time 10:28
 */
class Login extends CI_Controller
{
    /**
     * Login constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('setting_model');
        $this->load->model('user_model');
        $this->load->helper('url_helper');
    }

    /**
     * Try automatically logging in/registering and redirect.
     */
    public function index()
    {
        //If userdata is available
        if (!empty($this->input->server('USER_UID'))
            && !empty($this->input->server('USER_EMPLOYEENUMBER'))
            && !empty($this->input->server('USER_FIRSTNAME'))
            && !empty($this->input->server('USER_LASTNAME'))
            && !empty($this->input->server('USER_MAIL'))
        )
        {
            $userdata = array(
                'uid' => $this->input->server('USER_UID'),
                'firstname' => $this->input->server('USER_FIRSTNAME'),
                'lastname' => $this->input->server('USER_LASTNAME')
            );

            //get internal id
            $userinfo = $this->user_model->set_user($userdata);
            //add to userdata
            $userdata['id'] = $userinfo['id'];
            $userdata['role_id'] = $userinfo['role_id'];
            $userdata['logged_in'] = TRUE;

            $this->session->set_userdata($userdata);
            redirect('home');
        } else {
            redirect('man-login');
        }
    }

    /**
     * Handle log in request (json)
     */
    public function login()
    {

        //result
        $success = FALSE;

        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $input = json_decode($stream_clean, true);


        //query the database
        $result = $this->user_model->login($input['username'], $input['password']);


        if ($result) {
            $user = $this->user_model->get_user(array('uid' => $input['username']));

            if ($user['count'] === 1) {

                $user = $user['data'][0];

                $sessioninfo = [];
                $sessioninfo['id'] = $user['id'];
                $sessioninfo['uid'] = $user['uid'];
                $sessioninfo['firstname'] = $user['firstname'];
                $sessioninfo['lastname'] = $user['lastname'];
                $sessioninfo['role_id'] = $user['role_id'];
                $sessioninfo['logged_in'] = TRUE;

                $this->session->set_userdata($sessioninfo);

                $success = TRUE;
            }

        }

        $this->output
            ->set_header('Access-Control-Allow-Origin: *')
            ->set_header('Access-Control-Allow-Headers: Origin, Content-Type')
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => $success)));

    }

    /**
     * Page for logging in
     */
    public function login_page()
    {

        //Set rules
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|htmlspecialchars');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if($this->form_validation->run() === FALSE)
        {
            //Field validation failed.  User redirected to login page
            $data = [];
            $data['registration'] = $this->setting_model->get_setting('registration');
            $this->load->view('login/login', $data);
        }
        else
        {
            $this->check_database($this->input->post());
        }
    }

    /**
     * Get userdata from database and store in session.
     * @param $userdata data to identify user
     */
    public function check_database($userdata)
    {

        //query the database
        $result = $this->user_model->login($userdata['username'], $userdata['password']);


        if ($result) {
            $user = $this->user_model->get_user(array('uid' => $userdata['username']));

            if ($user['count'] !== 1) {
                show_error('Database failure.');
            }

            $user = $user['data'][0];

            $sessioninfo = [];
            $sessioninfo['id'] = $user['id'];
            $sessioninfo['uid'] = $user['uid'];
            $sessioninfo['firstname'] = $user['firstname'];
            $sessioninfo['lastname'] = $user['lastname'];
            $sessioninfo['role_id'] = $user['role_id'];
            $sessioninfo['logged_in'] = TRUE;

            $this->session->set_userdata($sessioninfo);
            redirect('home');
        }

        $data = [];
        $this->load->view('login/login', $data);
    }

    /**
     * Register new user
     */
    public function register()
    {
        //check if allowed
        if (!$this->setting_model->get_setting('registration')) {
            show_error('Registration is not allowed.');
        }

        //set rules
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        $this->form_validation->set_rules('username', 'Username', 'callback_username_check');
        $this->form_validation->set_rules('firstname', 'First name', 'required', 'htmlspecialchars');
        $this->form_validation->set_rules('lastname', 'Last name', 'required', 'htmlspecialchars');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if($this->form_validation->run() === FALSE)
        {
            //load view
            $this->load->view('login/register');
        }
        else
        {
            //set data
            $uid = htmlspecialchars(trim($this->input->post('username')));
            $firstname = htmlspecialchars(trim($this->input->post('firstname')));
            $lastname = htmlspecialchars(trim($this->input->post('lastname')));
            $password = $this->input->post('password');
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $user = array('uid' => $uid, 'firstname' => $firstname, 'lastname' => $lastname, 'password' => $password_hash);
            $this->user_model->set_user($user);

            //log in
            $this->check_database(array('username' => $uid, 'password' => $password));
        }

    }

    /**
     * Checks if username is available
     * @param $username uid of user
     * @return bool TRUE if available, false if used
     */
    public function username_check($username) {
        //get users
        $users = $this->user_model->get_user(array('uid' => htmlspecialchars(trim($username))));

        switch ($users['count']) {
            case 1:
                $this->form_validation->set_message('username_check', 'The username is already in use.');
                return FALSE;
                break;
            case 0:
                return TRUE;
                break;
            default:
                $this->form_validation->set_message('username_check', 'The username is invalid.');
                return FALSE;
                break;
        }
    }
}