<?php
/**
 *COPYRIGHT (C) 2016 Campfire. All Rights Reserved.
 * CreateEvent.php is the controller for createEvent_view.php
 * Solves SE165 Semester Project Fall 2016
 * @author Peter Curtis, Tyler Jones, Troy Nguyen, Marshall Cargle,
 *     Luis Otero, Jorge Aguiniga, Stephen Piazza, Jatinder Verma
*/
class CreateEvent extends CI_Controller {

	// constructor used for needed initialization
	public function __construct() {
		parent::__construct();
		$this->load->helper(array('form','url','html','security', 'date'));
		$this->load->library(array('session', 'form_validation'));
		$this->load->database();
		$this->load->model('user_model');
		$this->load->model('group_model');
		$this->load->model('event_model');
		if (!$this->session->userdata('login')) {
			redirect('home/index');
		}
	}

	function index($gID = NULL) {
		//new directory for images
		$targetDir = './uploads/';

		//dynamically populate the tag_list for the dropdown
		$data['tag_list'] = $this->group_model->get_dropdown_list();

		//dynamically populate a list of groups owned by this user in the dropdown list
		$groupsOwned = $this->group_model->get_groups($this->session->userdata('uid'), "owner");
		$group_list = array();
		foreach ($groupsOwned as $group) {
			$group_list[$group->org_id] = $group->org_title;
		}
		$data['group_list'] = $group_list;
		
		if ($gID != NULL) {
			//get the group that linked to this page
			$groupTitle = $this->group_model->get_group_by_id($gID);
			$data['linked_group'] = $groupTitle['org_title'];
		}

		//set last entered description to be displayed if error occurred
		$data['description'] = $this->input->post('description');

		// get user information from session data to create basic profile
		$details = $this->user_model->get_user_by_id($this->session->userdata('uid'));
		$data['uname'] = $details[0]->user_fname . " " . substr($details[0]->user_lname, 0,1);

		//set form validations
		$this->form_validation->set_rules('eventTitle', 'Event Title', 'trim|required|regex_match[#^[a-zA-Z0-9 \'-]+$#]|min_length[1]|max_length[100]|callback_badWord_check|xss_clean');
		$this->form_validation->set_rules('startTime', 'Event Start', 'trim|required|callback_date_check|callback_date_start_check|xss_clean');
		$this->form_validation->set_rules('endTime', 'Event End', 'trim|required|callback_date_check|callback_date_end_check|xss_clean');
		$this->form_validation->set_rules('description', 'Event Description', 'trim|required|max_length[1000]|callback_badWord_check|xss_clean');
		if (empty($_FILES['imageUpload']['tmp_name'])) {
			$this->form_validation->set_rules('imageUpload', 'Upload and Image', 'required');
		} else {
			$this->form_validation->set_rules('imageUpload', 'Upload and Image', 'callback_ext_check');
		}

		// submit the form and validate, if it fails just load the view again
		if ($this->form_validation->run() == FALSE) {
			$this->load->view('createEvent_view', $data);
		} else {
			//calculate new image height to preserve ratio
			list($orig_w, $orig_h) = getimagesize($_FILES['imageUpload']['tmp_name']);
			$thumbSize = 500;
			$w_ratio = ($thumbSize / $orig_w);
			$h_ratio = ($thumbSize / $orig_h);
			if ($orig_w > $orig_h ) {//landscape
				$crop_w = round($orig_w * $h_ratio);
				$crop_h = $thumbSize;
			} elseif ($orig_w < $orig_h ) {//portrait
				$crop_h = round($orig_h * $w_ratio);
				$crop_w = $thumbSize;
			} else {//square
				$crop_w = $thumbSize;
				$crop_h = $thumbSize;
			}

			//new filename for uploaded file
			$filename = $_FILES['imageUpload']['name'];
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			$filePrefix =  str_replace("'", '', $this->session->userdata('fname'));
			$newFileName = $targetDir . '/' . $filePrefix . time() . "." . $ext;
			$simpleNewFileName = $filePrefix . time() . "." . $ext;
			//read binary data from image file
			$imgString = file_get_contents($_FILES['imageUpload']['tmp_name']);
			//create image from string
			$image = imagecreatefromstring($imgString);
			//correct orientation based on exif data using GD function
			$exif = exif_read_data($_FILES['imageUpload']['tmp_name']);
			if(!empty($exif['Orientation'])) {
				switch($exif['Orientation']) {
					case 8:
						$image = imagerotate($image,90,0);
						break;
					case 3:
						$image = imagerotate($image,180,0);
						break;
					case 6:
						$image = imagerotate($image,-90,0);
						break;
				}
			}
			$tmp = imagecreatetruecolor($thumbSize, $thumbSize);
			imagecopyresampled($tmp, $image, 0, 0, 0, 0, $crop_w, $crop_h, $orig_w, $orig_h);
			//Save image
			$image_success = imagejpeg($tmp, $newFileName, 100);
			//cleanup
			imagedestroy($image);
			imagedestroy($tmp);

			//translate user dates to "yyyy-mm-dd hh:mm:ss format"
			$startInput = $this->input->post('startTime');
			$startDateTime = date('Y-m-d H:i:s', strtotime($startInput));
			$endInput = $this->input->post('endTime');
			$endDateTime = date('Y-m-d H:i:s', strtotime($endInput));

			//prepare to insert group details into event table
			$event_data = array(
				'event_title' => $this->input->post('eventTitle'),
				'event_description' => $this->input->post('description'),
				'event_begin_datetime' => $startDateTime,
				'event_end_datetime' => $endDateTime,
				'event_picture' => $simpleNewFileName
			);

			//prepare to insert user location details into location table
			$location_data = array(
				'address_one' => $this->input->post('address1'),
				'address_two' => '',
				'zipcode' => $this->input->post('zipcode')
			);

			//prepare to insert group tag details into tag table
			$tag_data = array(
				'tag_title' => $this->input->post('tag')
			);

			//prepare to insert group details into  table
			$group_data = array(
				'org_id' => array_search($this->input->post('eventGroup'), $group_list)
			);
			print_r($group_data);

			//prepare to insert owner id into owner table
			$eventowner_data = array(
				'owner_id' => $this->session->userdata('uid')
			);

			// Set error/success messages
			if ($this->event_model->insert_event($event_data, $location_data, $tag_data, $group_data, $eventowner_data)  && $image_success){ // success!!!
				$this->session->set_flashdata('msg','<div class="alert alert-success text-center">Your Event has been successfully created! Please click Cancel if you are finished creating events</div>');
				redirect('createEvent/index');
			} else { // error!!!
				$this->removeImage($simpleNewFileName); // Remove image upload if group was not created
				$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Oops! Error.  Please try again later!!!</div>');
				redirect('createEvent/index');
			}
		}
	}

	function ext_check() {
		$filename = $_FILES['imageUpload']['name'];
		$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		if (preg_match("/(jpg|jpeg|png)/",$ext)) {
			return TRUE;
		} else {
			$this->form_validation->set_message('ext_check', 'Must be a jpg, jpeg, or png file.');
			return FALSE;
		}
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

	function date_check($input) {
		$date_regex = "/^(0[1-9]|1[0-2])\/(0[1-9]|1\d|2\d|3[01])\/(19|20)\d{2}[ ]([0-9]|0[0-9]|1?[0-9]|2[0-3]):[0-5]?[0-9]+$/";
		if (preg_match($date_regex, $input)) {
			return TRUE;
		} else {
			$this->form_validation->set_message('date_check', 'Invalid date or time, please try again.');
			return FALSE;
		}
	}
	
	function date_start_check($input) {
		$currentDate = date('Y-m-d H:i');
		$currentDate = date('Y-m-d H:i', strtotime($currentDate));
		$startDate = date('Y-m-d H:i', strtotime($input));
		if ($currentDate <= $startDate) {
			return TRUE;
		} else {
			$this->form_validation->set_message('date_start_check', 'Selected date and time must be later than current date and time.');
			return FALSE;
		}
	}
	
	function date_end_check($input) {
		$startDate = date('Y-m-d H:i', strtotime($this->input->post('startTime')));
		$endDate = date('Y-m-d H:i', strtotime($input));
		if ($startDate < $endDate) {
			return TRUE;
		} else {
			$this->form_validation->set_message('date_end_check', 'Selected date and time must be later than Event Start date and time.');
			return FALSE;
		}
	}

	function removeImage($fileName) {
		$path = './uploads/'.$fileName;
		if(unlink($path)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
