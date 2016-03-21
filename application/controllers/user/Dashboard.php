<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __construct() {

		session_start();

		parent::__construct();
//		$this->load->database();
		$this->load->helper('html');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('language');
		$this->load->library('form_validation');
	}

	public function index()
	{


		if($this->session->userdata('admin_logged_in')){
			redirect('admin','true');
		}else{
			$this->load->render_custom('auth/login', 'auth/header', 'auth/footer');
		}
	}
}
