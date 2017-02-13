<?php
/**
 *COPYRIGHT (C) 2016 Campfire. All Rights Reserved.
 * Event.php is the controller for event_view.php
 * Solves SE165 Semester Project Fall 2016
 * @author Peter Curtis, Tyler Jones, Troy Nguyen, Marshall Cargle,
 *     Luis Otero, Jorge Aguiniga, Stephen Piazza, Jatinder Verma
*/
class Event extends CI_Controller {

	// constructor used for needed initialization
	public function __construct() {
		parent::__construct();
		$this->load->helper(array('form', 'url', 'html', 'security'));
		$this->load->library(array('session', 'pagination', 'form_validation'));
		$this->load->database();
		$this->load->model('event_model');
	}

	function index() {
		$this->load->view('event_view');
	}
	function search(){
		$this->load->view('searchEvents_view');
	}
	function search_nearby(){
		$lat = $this->input->post('current_lat');
		$lng = $this->input->post('current_lng');
		$dist = $this->input->post('dist');
		$result = $this->event_model->get_nearby_events($lat, $lng, $dist);

		echo json_encode($result);

	}

	function display($eventID = NULL){
		if ($eventID != NULL) {
			$uid = $this->session->userdata('uid');
			$arr['eventID'] = $eventID;
			$data['info'] = $this->event_model->get_event_by_id($eventID);
			$data['members'] = $this->event_model->get_event_members($eventID);
			$data['bulletins'] = $this->event_model->get_bulletins($eventID);

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
				$this->load->view('event_view', $data);
			} else {
				redirect('event/search');
			}
		} else {
			redirect('event/search');
		}
		
		// set form validation rules
		$this->form_validation->set_rules('bulletinDescription', 'Bulletin Description', 'trim|required|min_length[1]|max_length[255]|callback_badWord_check');
		
		// submit the form and validate
		if ($this->form_validation->run() == FALSE) {
			// if it fails just load the view again
		} else {
		
			$bulletin_data = array();
			$bulletin_data['event_id'] = $eventID;
			$bulletin_data['user_id'] = $this->session->userdata('uid');
			$bulletin_data['bulletin_message'] = $this->input->post('bulletinDescription');
		
			if ($this->event_model->insert_event_bulletin($bulletin_data)) {
				// success!!!
				$this->session->set_flashdata('msg','<div class="alert alert-success text-center">Bulletin Message successfully added!</div>');
				redirect('event/display/'.$eventID);
			} else {
				$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Oops! Error. Please try again later!!!</div>');
		
				redirect('event/display/'.$eventID);
			}
		}

	}

	function join_event($eventID = NULL) {
		if ($eventID != NULL) {
			$arr['eventID'] = $eventID;
			$uid = $this->session->userdata('uid');
			$this->event_model->join_event($uid, $eventID);
		}
		redirect('event/display/'.$eventID);
	}

	function leave_event($eventID = NULL) {
		if ($eventID != NULL) {
			$arr['eventID'] = $eventID;
			$uid = $this->session->userdata('uid');
			$this->event_model->leave_event($uid, $eventID);
		}
		redirect('event/display/'.$eventID);
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
