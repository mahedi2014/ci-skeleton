<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
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
            redirect(base_url().'user/dashboard','true');
        }else {
            $this->load->render_default('auth/signin');
        }
    }

    public function signup()
    {
        // create the data object
        $data = new stdClass();

        // set validation rules
        $this->form_validation->set_rules('username', 'Username', 'trim|required|alpha_numeric|min_length[4]|is_unique[users.username]', array('is_unique' => 'This username already exists. Please choose another one.'));
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required|min_length[6]|matches[password]');

        if ($this->form_validation->run() === false) { // validation not ok, send validation errors to the view
            $this->load->render_default('auth/signup', $data);

        } else { // set variables from the form
            $username = $this->input->post('username');
            $email    = $this->input->post('email');
            $password = $this->input->post('password');

            if ($this->auth_model->create_user($username, $email, $password)) { // user creation ok
                $this->load->render_default('auth/signup_success', $data);

            } else {
                // user creation failed, this should never happen
                $data->error = 'There was a problem creating your new account. Please try again.';

                // send error to the view
                $this->load->render_default('auth/signup', $data);
            }
        }
    }


    public function signin()
    {
        // create the data object
        $data = new stdClass();

       // set validation rules
        $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === false) {  // validation not ok, send validation errors to the view
            if($this->session->userdata('username')) {
                redirect(base_url().'user/dashboard','true');
            }else {
                $this->load->render_default('auth/signin');
            }

        } else { // set variables from the form
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            if ($this->auth_model->resolve_user_login($username, $password)) {

                $user_id = $this->auth_model->get_user_id_from_username($username);
                $user    = $this->auth_model->get_user($user_id);

                // set session user data
                $user_data = array(
                    'user_id'   => (int)$user_id,
                    'username'  => (string)$user->username,
                    'email'     => (string)$user->email,
                    'is_admin'  => (string)$user->is_admin,
                    'is_superadmin'  => (string)$user->is_superadmin
                );

                $this->session->set_userdata($user_data);

                //after session redirect
                redirect(base_url().'user/dashboard','true');

            } else {  // send error to the view
                $data->error = 'Wrong username or password.';
                $this->load->render_default('auth/signin', $data);
            }
        }
    }

    /**
     * signout function.
     *
     * @access public
     * @return void
     */
    public function signout()
    {

        // create the data object
        $data = new stdClass();

        if ($this->session->sess_destroy()) { // remove session data
            // user logout ok
            $this->load->render_default('auth/signin', $data);

        } else {
            // there user was not logged in, we cannot logged him out,
            redirect('/');

        }
    }

    public function test(){
        require_once BASEPATH.'../vendor/autoload.php';

        $mail = new PHPMailer();
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                       // Specify main and backup server
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'amit@gmail.com';                   // SMTP username
        $mail->Password = 'digitalinspiration';               // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
        $mail->Port = 587;                                    //Set the SMTP port number - 587 for authenticated TLS
        $mail->setFrom('amit@gmail.com', 'Amit Agarwal');     //Set who the message is to be sent from
        $mail->addReplyTo('mahedi2014@gmail.com', 'First Last');  //Set an alternative reply-to address
        $mail->addAddress('mahedi2014@gmail.com', 'Josh Adams');  // Add a recipient
        //$mail->addAddress('ellen@example.com');               // Name is optional
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');
        $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
        //$mail->addAttachment('/usr/labnol/file.doc');         // Add attachments
        //$mail->addAttachment('/images/image.jpg', 'new.jpg'); // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
        $mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));

        if(!$mail->send()) {
            //echo 'Message could not be sent.';
           // echo 'Mailer Error: ' . $mail->ErrorInfo;
            $result = array(
                'status' => 1,
                'statusMessage' => $mail->ErrorInfo
            );
            exit;
        }

        $result = array(
            'status' => 1,
            'statusMessage' => 'Message has been sent'
        );


        /*$result = array(
            'status' => 1,
            'statusMessage' => 'this is a test'
        );*/
        echo json_encode($result);
    }
}
