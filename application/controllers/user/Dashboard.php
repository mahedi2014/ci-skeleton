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


            $this->load->library('email');

            $emailConfig = array(
                'protocol' => 'tls',
                'smtp_host' => 'smtp.gmail.com',
                'smtp_user' => 'paytechbd@gmail.com',
                'smtp_pass' => 'mahediazad',
                'smtp_port' => 587,
                'crlf' => "\r\n"
            );

            $this->email->initialize($emailConfig);
//            $this->load->library('email',$emailConfig);
            $this->email->set_newline("\r\n");

            $this->email->from('paytechbd@gmail.com', 'Your Name');
            $this->email->to('mahedi2014@gmail.com');
            $this->email->cc('mahedi2014@gmail.com');
            $this->email->bcc('mahedi2014@gmail.com');
            $this->email->subject('Email Test');
            $this->email->message('Testing the email class.');
            $this->email->send();

            $data->users = $this->email->print_debugger();
            $this->load->render_content('user/email_send', $data);
        }
    }
}
