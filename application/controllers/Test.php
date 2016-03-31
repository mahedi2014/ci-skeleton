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
	    $this->email_smtp();
    }

	public function email_smtp()
	{
		$to = 'mahedi2014@gmail.com';
		$from = 'paytechbd@gmail.com';
		$from_name = 'test';
		$subject = 'test';
		$body = 'test';

		global $error;
		$mail = new PHPMailer();

		$mail->SMTPDebug  = 3;

		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = 'ssl://smtp.gmail.com';
		$mail->Port = 465;
		$mail->Username = 'paytechbd@gmail.com';
		$mail->Password = 'mahediazad';

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


	public function send_email()
	{
		$this->mail->email_smtp();
	}



	public function test_code()
	{
		$crypt = new Crypt();
		$key = 'd0a7e7997b6d5fcd55f4b5c32611b87cd923e8883';
		$content = '1d23';


		  $c  = $crypt->mc_encrypt($content, $key);
		echo strlen($c);


		//$c = 'VMZoMwwllkRe4Z2Ay9xXl8zx3gcJX9oEl4yfCr9qHcJexDYVLN8m1L/65qqkUsNr3zSG/5Lzeq8Jabu1wsirne5InX3saOpzK/WTQ03x3l0TwEZZo8zm1r5pl4qLS8OD|9DU1DAKkxk64QuhzfcNdxzK/cWBmM5OahpQ1dyL3hc4=';
		  $crypt->mc_decrypt($c, $key);

	}
}