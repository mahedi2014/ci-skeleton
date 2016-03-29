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

    public function index()
    {
	    $mail = new Mail();
	    $this->email_sending();
    }

	public function email_me()
	{
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

		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		$this->email->from('noreply@mrcmanager.com', 'test'); // change it to yours
		$this->email->to('mahedi2014@gmail.com');// change it to yours
		$this->email->subject('Email Verification!');
		$this->email->message('Test');
		if($this->email->send())
		{
			echo "success";
		}
		else
		{
			echo "failed";
		}
	}

	public function email_smtp()
	{
		$to = 'mahedi2014@gmail.com';
		$from = 'noreply@mrcmanager.com';
		$from_name = 'test';
		$subject = 'test';
		$body = 'test';

		global $error;
		$mail = new PHPMailer();

		$mail->SMTPDebug  = 3;

		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = 'ssl://smtp.zoho.com';
		$mail->Port = 465;
		$mail->Username = 'noreply@mrcmanager.com';
		$mail->Password = 'Energytec2013';

		$mail->SetFrom($from, $from_name);
		$mail->Subject = $subject;
		$mail->Body = $body;
		$mail->AddAddress($to);
		if(!$mail->Send()) {
			echo $error = 'Mail error: '.$mail->ErrorInfo;
			return false;
		} else {
			echo $error = 'Message sent!';
			return true;
		}


	}
}