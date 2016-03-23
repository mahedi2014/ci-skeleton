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

        $this->load->model('user_model');
    }

    public function index()
    {
        $this->load->render_default('user/signup');
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
}
