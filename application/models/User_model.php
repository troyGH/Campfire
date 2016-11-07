<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *COPYRIGHT (C) 2016 Campfire. All Rights Reserved.
 * User_model.php is the model for the user
 * Solves SE165 Semester Project Fall 2016
 * @author Peter Curtis, Tyler Jones, Troy Nguyen, Marshall Cargle,
 *     Luis Otero, Jorge Aguiniga, Stephen Piazza, Jatinder Verma
*/
class User_model extends CI_Model {

	// constructor for the user_model
	function __construct() {
		parent::__construct();
		$this->load->model('event_model');
		$this->load->model('group_model');
	}

	// get user for for login validation
	function get_user($email, $pwd) {
		$this->db->where('user_email', $email);
		$this->db->where('user_password', md5($pwd));
		$query = $this->db->get('user');
		return $query->result();
	}

	// get user by ID
	function get_user_by_id($id) {
		$query = $this->db->query('SELECT t1.user_fname, t1.user_lname, t1.user_email, t3.zipcode
									FROM user t1, user_location t2, location t3
									WHERE t1.user_id = '.$id.'
									AND t1.user_id = t2.user_id
									AND t2.location_id = t3.location_id');
		return $query->result();
	}

	// insert new user into DB
	function insert_user($user_data, $location_data) {
		// insert values into user and get the user ID
		$user_success = $this->db->insert('user', $user_data);
		$user_id = $this->db->insert_id();
		//Check if location is in database
		$this->db->start_cache();
		$this->db->where('zipcode', $location_data['zipcode']);
		$this->db->where('address_one', '');
		$this->db->where('address_two', '');
		$query = $this->db->get('location');
		$this->db->stop_cache();
		$this->db->flush_cache();
		//If location isnt in database yet
		if ($query->num_rows() == 0){
			// insert values into location and get the location ID
			$location_success = $this->db->insert('location', $location_data);
			$location_id = $this->db->insert_id();
		} else {
			$locResult = $query->result();
			$location_id = $locResult[0]->location_id;
			$location_success = TRUE;
		}
		// Create array of id_data to insert in DB
		$id_data = array(
			'user_id' => $user_id,
			'location_id' => $location_id
		);
		// Call function to insert user_id and location_id into DB
		$id_success = $this->insert_ids($id_data);
		// return true only if all inserts were successful
		return ($user_success && $location_success && $id_success);
	}

	// updates user and location in DB
	function update_user($user_id, $user_data, $location_data) {
		//update user table with new values
		try {
		$this->db->start_cache();
		$this->db->where('user_id', $user_id);
		$user_succes = $this->db->update('user', $user_data);
		$this->db->stop_cache();
		$this->db->flush_cache();
		//Update user location with new value

		//Check if location is in database
		$this->db->start_cache();
		$this->db->where('zipcode', $location_data['zipcode']);
		$this->db->where('address_one', '');
		$this->db->where('address_two', '');
		$query = $this->db->get('location');
		$this->db->stop_cache();
		$this->db->flush_cache();

		//If location isnt in database yet
		if ($query->num_rows() == 0){
			$this->db->insert('location', $location_data);
		}
		//update the user location table
		$location_success = $this->db->query('UPDATE user_location
							INNER JOIN location
							ON user_location.user_id = '.$user_id.'
							AND location.zipcode = '.$location_data['zipcode'].'
							SET user_location.location_id = location.location_id');

		return true;
		} catch (Exception $e){
			return false;
		}

	}

	// delete user from database
	function delete_user($user_id) {
		$owned_groups = $this->group_model->get_groups($user_id, 'owner');
		$owned_events = $this->event_model->get_events_by_user_id($user_id, 'owned');
		
		for ($x = 0; $x < sizeof($owned_groups); $x++) {
			$this->group_model->delete_group($owned_groups[$x]->org_id);
		}
		
		for ($x = 0; $x < sizeof($owned_events); $x++) {
			$this->group_model->delete_event($owned_events[$x]->event_id);
		}
		
		$this->db->delete('user', array('user_id'=> $user_id));
		$this->db->delete('user_location', array('user_id' => $user_id));
		$this->db->delete('attendee', array('user_id' => $user_id));
		$this->db->delete('bulletin', array('bulletin_user_id' => $user_id));
		$this->db->delete('member', array('user_id' => $user_id));
		$this->db->delete('owner', array('user_id' => $user_id));
	}

	// insert user_id and location_id into DB
	function insert_ids($id_data) {
		return $this->db->insert('user_location', $id_data);
	}
}?>
