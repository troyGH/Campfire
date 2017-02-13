<?php
/**
 *COPYRIGHT (C) 2016 Campfire. All Rights Reserved.
 * Login.php is the controller for login_view.php
 * Solves SE165 Semester Project Fall 2016
 * @author Peter Curtis, Tyler Jones, Troy Nguyen, Marshall Cargle,
 *     Luis Otero, Jorge Aguiniga, Stephen Piazza, Jatinder Verma
*/
class Login extends CI_Controller {

	// constructor used for needed initialization
	public function __construct() {
		parent::__construct();
		$this->load->helper(array('form','url','html'));
		$this->load->library(array('session', 'form_validation'));
		$this->load->database();
		$this->load->model('user_model');

	}

	public function index() {
		// get form input from the view
		$email = $this->input->post("email");
		$password = $this->input->post("password");

		// form validation
		$this->form_validation->set_rules("email", "Email", "trim|required");
		$this->form_validation->set_rules("password", "Password", "trim|required");

		if ($this->form_validation->run() == FALSE) {
			// validation failed so reload login page
			$this->load->view('login_view');
		} else {
			// check for user credentials
			$uresult = $this->user_model->get_user($email, $password);
			if (count($uresult) > 0) {
				// set session data if user exists
				$sess_data = array('login' => TRUE, 'fname' => $uresult[0]->user_fname, 'lname' => $uresult[0]->user_lname,'uid' => $uresult[0]->user_id);
				$this->session->set_userdata($sess_data);
				redirect("home/index");
			} else {
				$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Wrong Email-ID or Password!</div>');
				redirect('login/index');
			}
		}
    }
}
