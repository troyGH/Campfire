<?php
/**
 *COPYRIGHT (C) 2016 Campfire. All Rights Reserved.
 * Group.php is the controller for group_view.php
 * Solves SE165 Semester Project Fall 2016
 * @author Peter Curtis, Tyler Jones, Troy Nguyen, Marshall Cargle,
 *     Luis Otero, Jorge Aguiniga, Stephen Piazza, Jatinder Verma
*/
class Group extends CI_Controller {

	// constructor used for needed initialization
	public function __construct() {
		parent::__construct();
		$this->load->helper(array('form', 'url', 'html', 'security'));
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->database();
		$this->load->model('group_model');
	}

	function index() {
		$this->load->view('group_view');
	}

	function search(){
		// get form input from the view
		$query = $this->input->get('groupQuery');

		if($query){

			$json = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($query)); //http request, output is a json object
			$match = json_decode($json);//decode json object into a php variable

			//if search is valid
			if(empty($match->results) == false){
				$group_search_info = $this->group_model->search_groups_query($match->results[0]->geometry->location->lat, $match->results[0]->geometry->location->lng); //input first geolocation
			}
		}
		else{
			$random_group_query = $this->group_model->get_random_groups();
		}


		if(isset($group_search_info)){//for displaying searched groups

			if(count($group_search_info) > 12){

				//Configuring for pagination to echo properly in searchGroups_view
				$config['total_rows'] = count($group_search_info)/12 ;
				$config['per_page'] = 1;
				$config['num_links'] = 3;
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
			$arr['groups'] = $group_search_info;
			$this->load->view('searchGroups_view', $arr);
		}
		else if(isset($random_group_query)){ //for displaying random groups

			$arr['random'] = $random_group_query;
			$this->load->view('searchGroups_view', $arr);
		}
		else{
			$this->load->view('searchGroups_view');
		}
	}

	function display($gID = NULL){
		if ($gID != NULL) {
			$uid = $this->session->userdata('uid');
			$arr['gID'] = $gID;
			$data['info'] = $this->group_model->get_group_by_id($gID);
			$data['members'] = $this->group_model->get_group_members($gID);
			$data['events'] = $this->group_model->get_group_events($gID);
			$data['bulletins'] = $this->group_model->get_bulletins($gID);

			$member_status = 'nonmember';
			if ($this->session->userdata('login') == FALSE) {
				$member_status = 'notlogged';
			}
			else if ( $uid == $data['info']['user_id']) {
				$member_status = 'owner';
			}
			else {
				foreach($data['members'] as $row) {
					if ($row['user_id'] == $uid) {
						$member_status="member";
					}
				}
			}

			$data['status'] = $member_status;
			if ($data != NULL) {
				$this->load->view('group_view', $data);
			} else {
				redirect('group/search');
			}
		} else {
			redirect('group/search');
		}

		// set form validation rules
		$this->form_validation->set_rules('bulletinDescription', 'Bulletin Description', 'trim|required|min_length[1]|max_length[255]|callback_badWord_check');
		
		// submit the form and validate
		if ($this->form_validation->run() == FALSE) {
			// if it fails just load the view again
		} else {

			$bulletin_data = array();
			$bulletin_data['org_id'] = $gID;
			$bulletin_data['user_id'] = $this->session->userdata('uid');
			$bulletin_data['bulletin_message'] = $this->input->post('bulletinDescription');
 
			if ($this->group_model->insert_group_bulletin($bulletin_data)) {
				// success!!!
				$this->session->set_flashdata('msg','<div class="alert alert-success text-center">Bulletin Message successfully added!</div>');
				
				redirect('group/display/'.$gID);
			} else {
				$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Oops! Error. Please try again later!!!</div>');
				
				redirect('group/display/'.$gID);
			}
		}


	}

	function join_group($gID = NULL) {
		if ($gID != NULL) {
			$arr['gID'] = $gID;
			$uid = $this->session->userdata('uid');
			$this->group_model->join_group($uid, $gID);
		}
		redirect('group/display/'.$gID);
	}

	function leave_group($gID = NULL) {
		if ($gID != NULL) {
			$arr['gID'] = $gID;
			$uid = $this->session->userdata('uid');
			$this->group_model->leave_group($uid, $gID);
		}
		redirect('group/display/'.$gID);
	}

	
	function badWord_check($input) {
		$fh = fopen(base_url().'assets/text_input/badWords.txt', 'r') or die($php_errormsg);
		$line = fgets($fh);
		fclose($fh);
		if (preg_match($line, strtolower($input))) {
			$this->form_validation->set_message('badWord_check', 'You have entered an inappropriate word! Lets keep it clean!!!.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
}
