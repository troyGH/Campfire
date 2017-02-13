<?php
/**
 *COPYRIGHT (C) 2016 Campfire. All Rights Reserved.
 * MyGroups.php is the controller for myGroups_view.php
 * Solves SE165 Semester Project Fall 2016
 * @author Peter Curtis, Tyler Jones, Troy Nguyen, Marshall Cargle, 
 *     Luis Otero, Jorge Aguiniga, Stephen Piazza, Jatinder Verma
*/
class MyGroups extends CI_Controller {

	// constructor used for needed initialization
	public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','html'));
		$this->load->library(array('session', 'pagination'));
		$this->load->database();
		$this->load->model('user_model');
		$this->load->model('group_model');
	}

	function index() {
		// get user information from session data to create basic profile
		$details = $this->user_model->get_user_by_id($this->session->userdata('uid'));
		$data['uname'] = $details[0]->user_fname . " " . $details[0]->user_lname;
		$data['uemail'] = $details[0]->user_email;
		
		// get all groups for user
		$group_member = $this->group_model->get_groups($this->session->userdata('uid'), 'member');
		$group_owner = $this->group_model->get_groups($this->session->userdata('uid'), 'owner');
		$data['memberedgroups'] = $group_member;
		$data['ownedgroups'] = $group_owner;

		// load and populate the profile_view
		$this->load->view('myGroups_view', $data);
	}
	
	function set_config ($variable)
	{
		if (count($variable) > 12) {
			//Configuring for pagination to echo properly in searchGroups_view
			$config['total_rows'] = count($variable)/12 ;
			$config['per_page'] = 1;
			$config['num_links'] = 3;
			$config['url_segment'] = 3;
			$config['page_query_string'] = TRUE;
			$config['reuse_query_string'] = TRUE;
			$config['full_tag_open'] = '<nav><ul class="pagination">';
			$config['full_tag_close'] = '</ul></nav>';
			$config['prev_link'] = 'Previous';
			$config['prev_tag_open'] = '<li class="page-item" id="prev"> <span aria-hidden="true">';
			$config['prev_tag_close'] = '</span></li>';
			$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] =  '<li class="page-item">';
			$config['num_tag_close'] =  '</li>';
			$config['next_link'] = 'Next';
			$config['next_tag_open'] = '<li class="page-item" id="next"> <span aria-hidden="true">';
			$config['next_tag_close'] = '</span></li>';
			$config['last_link'] = '&raquo;';
			$config['last_tag_open'] = '<li class="page-item"><span aria-hidden="true">';
			$config['last_tag_close'] = '</span></li>';
			$config['first_link'] = '&laquo;';
			$config['first_tag_open'] = '<li class="page-item"><span aria-hidden="true">';
			$config['first_tag_close'] = '</span></li>';
			$this->pagination->initialize($config);
		}
	}
}
