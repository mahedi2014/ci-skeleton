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
        echo 'signin complete';
        redirect(base_url().'user/signout','true');
    }


}
