<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    public function __construct() {

        parent::__construct();
        $this->load->helper('html');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('language');
        $this->load->library('form_validation');
        $this->load->library('Email_Config');

        $this->load->library('session');

        $this->load->model('auth_model');
        $this->load->model('user/users_data');
    }

    public function index()
    {
        if($this->auth_model->chk_signin()) {
            $data = new stdClass();
            $data->username = $this->session->userdata('username');
            $this->load->render_content('user/dashboard', $data);
        }
    }

    public function user_list()
    {
        if($this->auth_model->chk_signin()) {
            $data = new stdClass();
            $data->users   = $this->users_data->get_users_data();
            $this->load->render_content('user/user_list', $data);
        }
    }

    public function email_sending()
    {
        if($this->auth_model->chk_signin()) {
            $data = new stdClass();
            $data->users = $this->email_config->index();
            $this->load->render_content('user/email_send', $data);
        }
    }
}
