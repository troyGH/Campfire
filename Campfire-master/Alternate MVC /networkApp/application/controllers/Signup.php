<?php
/**
 *COPYRIGHT (C) 2016 Tyler Jones. All Rights Reserved.
 * Signup.php is the controller for signup_view.php
 * Solves SE148 Homework1
 * @author Tyler Jones
*/
class Signup extends CI_Controller {

	// constructor used for needed initialization
	public function __construct() {
		parent::__construct();
		$this->load->helper(array('form','url'));
		$this->load->helper('security');
		$this->load->library(array('session', 'form_validation'));
		$this->load->database();
		$this->load->model('user_model');
	}
	
	function index() {
		// set form validation rules
		$this->form_validation->set_rules('fname', 'First Name', 'trim|required|alpha|min_length[3]|max_length[30]|xss_clean');
		$this->form_validation->set_rules('lname', 'Last Name', 'trim|required|alpha|min_length[3]|max_length[30]|xss_clean');
		$this->form_validation->set_rules('email', 'Email ID', 'trim|required|valid_email|is_unique[user.email]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
		
		// submit the form and validate
		if ($this->form_validation->run() == FALSE) {
			// if it fails just load the view again
			$this->load->view('signup_view');
		} else {
			//insert user details into db
			$data = array(
				'fname' => $this->input->post('fname'),
				'lname' => $this->input->post('lname'),
				'email' => $this->input->post('email'),
				'password' => md5($this->input->post('password', TRUE))
			);
			
			if ($this->user_model->insert_user($data)) {
				// success!!!
				$this->session->set_flashdata('msg','<div class="alert alert-success text-center">You are Successfully Registered! Please login to access your Profile!</div>');
				redirect('signup/index');
			} else {
				// error
				$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Oops! Error.  Please try again later!!!</div>');
				redirect('signup/index');
			}
		}
	}
}
