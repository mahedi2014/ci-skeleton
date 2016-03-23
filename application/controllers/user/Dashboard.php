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
        if($this->auth_model->chk_signin()) {
            $data = new stdClass();
            $this->load->render_content('user/dashboard', $data);
        }
    }

    public function user_list()
    {
        if($this->auth_model->chk_signin()) {
            $data = new stdClass();
            $this->load->render_content('user/dashboard', $data);
        }
    }


}
