<?php
/**
 *COPYRIGHT (C) 2016 Campfire. All Rights Reserved.
 * Home.php is the controller for home_view.php
 * Solves SE165 Semester Project Fall 2016
 * @author Peter Curtis, Tyler Jones, Troy Nguyen, Marshall Cargle, 
 *     Luis Otero, Jorge Aguiniga, Stephen Piazza, Jatinder Verma
*/
class Home extends CI_Controller {

	// constructor used for needed initialization
	public function __construct() {
		parent::__construct();
		$this->load->helper(array('url', 'html'));
		$this->load->library('session');
	}

	function index() {
		$this->load->view('home_view');
	}

	function logout() {
		// destroy session upon logout
		$data = array('login' => '', 'uname' => '', 'uid' => '');
		$this->session->unset_userdata($data);
		$this->session->sess_destroy();
		// return to home page, but logged out
		redirect('home/index');
	}
    
    //Feedback Form 
    //Email is sent to the teams email.
    function send_email() {
        
        $this->load->library("form_validation");
        
        $this->form_validation->set_rules('name', 'Name', 'required|alpha');
        $this->form_validation->set_rules("email", "Email", "required|valid_email");
        $this->form_validation->set_rules("message", "Message", "required");
        
        if ($this->form_validation->run() == FALSE) {
            //If it fails, load the home page again.
            $this->load->view('home_view');
        } else {
            $data["message"] = "The email has been successfuly sent";
            
            //Email Library
            $this->load->library('email');
            
            //From, To, Subject, Message
            $this->email->from(set_value("email"), set_value("name"));
            $this->email->to("teamcampfirellc@gmail.com");
            $this->email->subject("Campfire");
            $this->email->message(set_value("message"));
            
            //Send email
            $this->email->send();
            
            $this->load->view('home_view', $data);     
        }   
    }   
}