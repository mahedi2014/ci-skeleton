<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Mahedi Azad
 * Date: 24-Mar-16
 * Time: 12:14 PM
 */
class Email_Config
{
    function __construct(){
        parent::__construct();
        $this->load->library('phpmailer');
    }
    function send_email() {
        $subject = 'Testing Email';
        $name = 'iCoreThink Technologies';
        $email = 'mahedi2014@gmail.com';
        $body = "This is body text for test email to combine CodeIgniter and PHPmailer";
        $this->phpmailer->AddAddress($email);
        $this->phpmailer->IsMail();
        $this->phpmailer->From = 'info@icorethink.com';
        $this->phpmailer->FromName = 'iCoreThink Technologies';
        $this->phpmailer->IsHTML(true);
        $this->phpmailer->Subject = $subject;
        $this->phpmailer->Body = $body;
        $this->phpmailer->Send();

    }

}