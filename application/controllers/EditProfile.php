<?php
/**
 *COPYRIGHT (C) 2016 Campfire. All Rights Reserved.
 * EditProfile.php is the controller for editProfile_view.php
 * Solves SE165 Semester Project Fall 2016
 * @author Peter Curtis, Tyler Jones, Troy Nguyen, Marshall Cargle, 
 *     Luis Otero, Jorge Aguiniga, Stephen Piazza, Jatinder Verma
*/
class EditProfile extends CI_Controller {

	// constructor used for needed initialization
	public function __construct() {
		parent::__construct();

		$this->load->helper(array('form','url'));
		$this->load->helper('security');
		$this->load->library(array('session', 'form_validation'));
		$this->load->database();
		$this->load->model('user_model');
		if (!$this->session->userdata('login')) {
			redirect('home/index');
		}
		

	}

	function index() {
		$user_id = $this->session->userdata('uid');
		$tempUserData = $this->user_model->get_user_by_id($user_id);
		$data['user_data'] = array('user_fname' => $tempUserData[0]->user_fname,'user_lname' => $tempUserData[0]->user_lname,
				'user_email' => $tempUserData[0]->user_email);
		
		// set form validation rules
		$this->form_validation->set_rules('fname', 'First Name', 'trim|required|regex_match[#^[a-zA-Z\'-]+$#]|min_length[2]|max_length[30]|xss_clean');
		$this->form_validation->set_rules('lname', 'Last Name', 'trim|required|regex_match[#^[a-zA-Z\'-]+$#]|min_length[2]|max_length[30]|xss_clean');
		$this->form_validation->set_rules('email', 'Email ID', 'trim|required|valid_email|callback_email_check');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
		
		
		if ($this->form_validation->run() == FALSE) {
			// if it fails just load the view again
			$this->load->view('editProfile_view', $data);
 			
 		} else {
			//prepare to update user details into user table
			$user_data = array(
				'user_fname' => $this->input->post('fname'),
				'user_lname' => $this->input->post('lname'),
				'user_email' => $this->input->post('email'),
				'User_password' => md5($this->input->post('password', TRUE))
			);
			
			if ($this->user_model->update_user($user_id, $user_data)) {
				// success!!!
				$this->session->set_userdata('fname', $user_data['user_fname']);
				$this->session->set_userdata('lname', $user_data['user_lname']);
				$this->session->set_flashdata('msg','<div class="alert alert-success text-center">You have Successfully Updated your account! </div>');
				redirect('editProfile');
			} else {
				// error
				$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Oops! Error.  Please try again later!!!</div>');
				redirect('editProfile');
			}
		}
	}
	
	function email_check($str) {
		$user_id = $this->session->userdata('uid');
		$query = $this->db->get_where('user', array('user_email' => $str));
		$validEmail = true;
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				 if ($row->user_id != $user_id) {
				 	$this->form_validation->set_message('email_check', 'This Email ID is already in use.');
				 	$validEmail = false;
				 }
			}
		} else {
			$validEmail = true;
		}
		return $validEmail;
	}
	
	function deleteUser() {
		$user_id = $this->session->userdata('uid');
		$userData = $this->user_model->get_user_by_id($user_id);
		//Delete from the database using the User ID
		$this->user_model->delete_user($user_id);
		//Delete the Session information
		$data = array('login' => '', 'fname' => '','lname' => '', 'uid' => '');
		$this->session->unset_userdata($data);
		$this->session->sess_destroy();
	}
}
