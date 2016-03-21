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
		$this->load->render_default('draft/dashboard');
//		$this->load->render_custom('draft/dashboard', 'draft/header', 'draft/footer');
	}
}
