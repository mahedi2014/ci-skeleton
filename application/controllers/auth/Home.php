<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
		$this->load->database();
		$this->load->helper('html');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('language');
		$this->load->library('form_validation');
        $this->load->library('session');
	}

	public function index()
	{
		if($this->session->userdata('admin_logged_in')){
			redirect('admin','true');
		}else{
            $this->signin();
		}
	}

	public function signin()
	{
		// create the data object
//		$data = new stdClass();
        $data['page_title'] = 'Signin';

		// load form helper and validation library
		$this->load->helper('form');
		$this->load->library('form_validation');

		// set validation rules
		$this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == false) {

			// validation not ok, send validation errors to the view
            $this->load->render_default('auth/signin', $data);

		} else {

			// set variables from the form
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			if ($this->user_model->resolve_user_login($username, $password)) {

				$user_id = $this->user_model->get_user_id_from_username($username);
				$user    = $this->user_model->get_user($user_id);

				// set session user datas
				$_SESSION['user_id']      = (int)$user->id;
				$_SESSION['username']     = (string)$user->username;
				$_SESSION['logged_in']    = (bool)true;
				$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
				$_SESSION['is_admin']     = (bool)$user->is_admin;

				// user login ok
                $this->load->render_default('auth/signin_success', $data);

			} else {

				// login failed
				$data->error = 'Wrong username or password.';

				// send error to the view
                $this->load->render_default('auth/signin', $data);

			}

		}
	}

    public function signup()
    {
        if($this->session->userdata('admin_logged_in')){
            redirect('admin','true');
        }else{
            $this->load->render_custom('auth/signup', 'auth/header', 'auth/footer');
        }
    }

}
