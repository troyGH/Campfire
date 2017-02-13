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
		$query = $this->db->query('SELECT user_fname, user_lname, user_email
									FROM user
									WHERE user_id = '.$id.'');
		return $query->result();
	}

	// insert new user into DB
	function insert_user($user_data) {
		// insert values into user and return true on success
		return $this->db->insert('user', $user_data);
	}

	// updates user and location in DB
	function update_user($user_id, $user_data) {
		//update user table with new values
		try {
			$this->db->start_cache();
			$this->db->where('user_id', $user_id);
			$user_succes = $this->db->update('user', $user_data);
			$this->db->stop_cache();
			$this->db->flush_cache();
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
		$this->db->delete('attendee', array('user_id' => $user_id));
		$this->db->delete('bulletin', array('bulletin_user_id' => $user_id));
		$this->db->delete('member', array('user_id' => $user_id));
		$this->db->delete('owner', array('user_id' => $user_id));
	}

}?>
