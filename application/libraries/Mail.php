<?php


class Mail extends CI_Controller
{
    function __construct()
    {
        $this->CI = get_instance();
//        $this->CI->load->library('mylib2');


        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'tcp://smtp.live.com',
            'smtp_crypto' => 'tls',
            'smtp_port' => '587',
            'smtp_user' => 'mrcmanager911@hotmail.com', // change it to yours
            'smtp_pass' => 'Energytec', // change it to yours
            'mailtype' => 'html',
            'charset' => 'UTF-8',
            'user_name' => 'MRC Manager',
            'smtp_timeout' => 10,
            'wordwrap' => TRUE
        );


        fsockopen('ssl://smtp.live.com', '465', $errno, $error, '10');

        $this->CI->load->library('email', $config);
//        $this->load->library('email');
        $this->CI->email->set_newline("\r\n");
        $this->CI->email->from('mrcmanager911@hotmail.com', 'test'); // change it to yours
        $this->CI->email->to('mahedi2014@gmail.com');// change it to yours
        $this->CI->email->subject('Email Verification!');
        $this->CI->email->message('Test');
        if($this->CI->email->send())
        {
            echo "success";
        }
        else
        {
            echo "failed";
        }
    }
}