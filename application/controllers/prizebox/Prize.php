<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prize extends CI_Controller {
	public function __construct() {

		session_start();

		parent::__construct();
		$this->load->database();
		$this->load->helper('html');
		$this->load->helper('url');

        $this->load->model('prizebox/Prize_Sms');
	}
	public function index()
	{
//		$this->load->render_default('api/home');
	}


    /**
     * Add sms to database
     * browse: http://localhost/smsproject/push-pull-prize/prizebox/prize/set_sms/?msg=mojo%2056&msisdn=01752720020
     *
     */
	public function set_sms()
	{
      $this->Prize_Sms->set_prize_sms();
	}

}
