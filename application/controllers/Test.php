<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
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

        $this->load->library('mail');
    }

    /* -----------------------------------------------------
    * MAIL PLUGIN SAMPLES
    * -----------------------------------------------------*/
    public function index($options = null)
    {
        $html = '
         <h1>Mail Plugin Samples</h1>
         <hr>
            <ul>
               <li><a href="test/sample_basic">Sample Basic</a></li>
               <li><a href="test/sample_array">Sample Array</a></li>
               <li><a href="test/sample1">Sample 1 template html</a></li>
               <li><a href="test/sample2">Sample 2 template html & text</a></li>
               <li><a href="test/sample3">Sample 3 template plain-text</a></li>
            </ul>
      ';
        exit($html);
    }

    /* -----------------------------------------------------
     * Message without HTML template as inline HTML format
     * -----------------------------------------------------*/
    public function sample_basic()
    {
        $data = '<h2>Sample Basic</h2>
               <hr>
               <p>This is a simple basic mail message in <strong>HTML</strong> string format</p>
               <p>Lorem ipsum dolor sit amharum<br /> quod deserunt id dolores.</p>';

        $mail = new Mail();
        $mail->setMailBody($data);
        $mail->sendMail('Test Sample Basic');
        exit('Message Sent!');
    }

    /* -----------------------------------------------------
     * Message as an Array with no HTML template
     * -----------------------------------------------------*/
    public function sample_array()
    {
        $data = array(
            'Juliet & Romeo',
            'mahedi2014@gmail.com',
            'This is an example using an Array as a message, without an external template HTML',
            date('Y-m-d H:i:s'),
        );

        $mail = new Mail();
        $mail->setMailBody($data);
        $mail->sendMail('Test Sample Array');
        exit('Message Sent!');
    }

    /* -----------------------------------------------------
     * Message using a external HTML template
     * -----------------------------------------------------*/
    public function sample1()
    {
        $data = null;
        $template_html = 'sample-1.html';

        $mail = new Mail();
        $mail->setMailBody($data, $template_html);
        $mail->sendMail('Test Sample 1 - external HTML template');
        exit('Message Sent!');
    }

    /* -----------------------------------------------------
     * Message as an associative array with external HTML template
     * -----------------------------------------------------*/
    public function sample2()
    {
        $data = array(
            "NAME"       => 'Juliet & Romeo',
            "EMAIL"      => 'some_email@example.com',
            "MESSAGE"    => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt, ad labore iusto quibusdam totam. Repellendus, architecto quos temporibus dolores assumenda amet veritatis quisquam!',
            "DATE"       => date('Y-m-d H:i:s'),
            "SMALL_TEXT" => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                    Sapiente, fugiat consequatur illo eaque ipsam expedita sint itaque quibusdam porro fugit quas quae placeat
                                    qui fuga sequi aspernatur nam quo quis.',
        );

        $template_html = 'sample-2.html';

        $mail = new Mail();
        $mail->setMailBody($data, $template_html);
        $mail->sendMail('Test Sample 2 Assoc Array with an external template and plain-text file');
        exit('Message Sent!');
    }
    /* -----------------------------------------------------
     * Message as a plain-text format using external template
     * -----------------------------------------------------*/
    public function sample3()
    {
        $data = array(
            'John',
            'john@example.com',
            'Sydney, NSW',
            'Australia',
            12,06,1980,
            '02 123 45678',
            'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'
        );
        $template_html = 'sample-3.txt';

        $mail = new Mail();
        $mail->setMailBody($data, $template_html, 'TEXT');
        $mail->sendMail('Test Sample 3 Text Plain Template Format');
        exit('Email Sample 3');
    }

}

/* End of file Samples_controller.php */
/* Location: ./application/controllers/Samples_controller.php */