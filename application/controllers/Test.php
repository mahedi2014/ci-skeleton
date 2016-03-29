<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller
{
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
        $this->load->model('auth_model');

	    $this->load->library('mail');

    }

    /* -----------------------------------------------------
    * MAIL PLUGIN SAMPLES
    * -----------------------------------------------------*/
    public function index()
    {
	    $mail = new Mail();
    }

}