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
            $data['page_title'] = 'Signin | Ci-Skeleton-Basic';
			$this->load->render_default('auth/signin', $data);
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
