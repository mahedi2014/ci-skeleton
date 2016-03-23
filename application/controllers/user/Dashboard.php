<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    public function __construct() {

        parent::__construct();
        $this->load->database();
        $this->load->helper('html');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('language');
        $this->load->library('form_validation');
        $this->load->library('session');

        $this->load->model('auth_model');
    }

    public function index()
    {

        if($this->session->userdata('username')) {
            $data = new stdClass();
            echo $this->session->userdata('username');
//            echo anchor(base_url().'auth/signout', 'Signout');

            $this->load->render_default('user/dashboard', $data);

        }else {
            redirect(base_url().'auth/signout','true');
        }
    }


}
