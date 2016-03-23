<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    public function __construct() {

        parent::__construct();
		$this->load->database();
        $this->load->helper('html');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('language');
        $this->load->library('form_validation');

        $this->load->library('session');

        $this->load->model('user_model');
    }

    public function index()
    {
        if($this->session->userdata('username')) {
            redirect(base_url().'dashboard/user','true');
        }else {
            $this->load->render_default('user/signin');
        }
    }

    public function signup()
    {
        // create the data object
        $data = new stdClass();

        // load form helper and validation library
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set validation rules
        $this->form_validation->set_rules('username', 'Username', 'trim|required|alpha_numeric|min_length[4]|is_unique[users.username]', array('is_unique' => 'This username already exists. Please choose another one.'));
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required|min_length[6]|matches[password]');

        if ($this->form_validation->run() === false) { // validation not ok, send validation errors to the view
            $this->load->render_default('user/signup', $data);

        } else { // set variables from the form
            $username = $this->input->post('username');
            $email    = $this->input->post('email');
            $password = $this->input->post('password');

            if ($this->user_model->create_user($username, $email, $password)) { // user creation ok
                $this->load->render_default('user/signup_success', $data);

            } else {
                // user creation failed, this should never happen
                $data->error = 'There was a problem creating your new account. Please try again.';

                // send error to the view
                $this->load->render_default('user/signup', $data);
            }
        }
    }


    public function signin()
    {
        // create the data object
        $data = new stdClass();

        // load form helper and validation library
        $this->load->helper('form');
        $this->load->library('form_validation');

        // set validation rules
        $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == false) {  // validation not ok, send validation errors to the view
            $this->load->render_default('user/signin');

        } else { // set variables from the form
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            if ($this->user_model->resolve_user_login($username, $password)) {

                $user_id = $this->user_model->get_user_id_from_username($username);
                $user    = $this->user_model->get_user($user_id);

                // set session user data
                $user_data = array(
                    'user_id'   => (int)$user->id,
                    'username'  => (string)$user->username,
                    'email'     => (string)$user->email,
                    'is_admin'  => (string)$user->is_admin,
                    'is_superadmin'  => (string)$user->is_superadmin
                );

                $this->session->set_userdata($user_data);

                //after session redirect
                redirect(base_url().'dashboard/user/index','true');

            } else {  // send error to the view
                $data->error = 'Wrong username or password.';
                $this->load->render_default('user/signin', $data);
            }
        }
    }

    /**
     * signout function.
     *
     * @access public
     * @return void
     */
    public function signout() {

        // create the data object
        $data = new stdClass();

        $session_array = $this->session->all_userdata();

        if ($this->session->unset_userdata($session_array)) { // remove session data
            // user logout ok
            $this->load->render_default('user/signin', $data);

        } else {
            // there user was not logged in, we cannot logged him out,
            redirect('/');

        }
    }
}
