<?php


class Mail
{
    function email_sending()
    {
        $this->CI = get_instance();

        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.zoho.com',
            'smtp_port' => 465,
            'smtp_user' => 'noreply@mrcmanager.com', // change it to yours
            'smtp_pass' => 'Energytec2013', // change it to yours
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'user_name' => 'Manager',
            'welcome_to' => 'Welcome!',
            'wordwrap' => TRUE
        );


//        fsockopen('ssl://smtp.zoho.com', '465', $errno, $error, '10');

        $this->CI->load->library('email', $config);
        $this->CI->email->set_newline("\r\n");
        $this->CI->email->from('noreply@mrcmanager.com', 'test'); // change it to yours
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