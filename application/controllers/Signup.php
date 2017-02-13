<?php
/**
 *COPYRIGHT (C) 2016 Campfire. All Rights Reserved.
 * Signup.php is the controller for signup_view.php
 * Solves SE165 Semester Project Fall 2016
 * @author Peter Curtis, Tyler Jones, Troy Nguyen, Marshall Cargle, 
 *     Luis Otero, Jorge Aguiniga, Stephen Piazza, Jatinder Verma
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
		// if the account was successfully created, redirect after delay to show success
		if ($this->session->flashdata('editSuccess')) {
			header("refresh:5; url=".base_url()."/index.php/login");
		}
		// set form validation rules
		$this->form_validation->set_rules('fname', 'First Name', 'trim|required|regex_match[#^[a-zA-Z\'-]+$#]|min_length[1]|max_length[30]|xss_clean');
		$this->form_validation->set_rules('lname', 'Last Name', 'trim|required|regex_match[#^[a-zA-Z\'-]+$#]|min_length[1]|max_length[30]|xss_clean');
		$this->form_validation->set_rules('email', 'Email ID', 'trim|required|valid_email|is_unique[user.user_email]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
		
		// submit the form and validate
		if ($this->form_validation->run() == FALSE) {
			// if it fails just load the view again
			$this->load->view('signup_view');
		} else {
			//prepare to insert user details into user table
			$user_data = array(
				'user_fname' => $this->input->post('fname'),
				'user_lname' => $this->input->post('lname'),
				'user_email' => $this->input->post('email'),
				'User_password' => md5($this->input->post('password', TRUE))
			);
			
			if ($this->user_model->insert_user($user_data)) {
				// success!!!
				$this->session->set_flashdata('msg','<div class="alert alert-success text-center">You are Successfully Registered! You are being redirected... Please login to access your Profile!</div>');
				$this->session->set_flashdata('editSuccess', true);
				redirect('signup/index');
			} else {
				// error
				$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Oops! Error.  Please try again later!!!</div>');
				redirect('signup/index');
			}
		}
	}
}
