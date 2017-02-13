<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *COPYRIGHT (C) 2016 Campfire. All Rights Reserved.
 * Group_model.php is the model for a group
 * Solves SE165 Semester Project Fall 2016
 * @author Peter Curtis, Tyler Jones, Troy Nguyen, Marshall Cargle,
 *     Luis Otero, Jorge Aguiniga, Stephen Piazza, Jatinder Verma
*/
class Group_model extends CI_Model {
	// constructor for the user_model
	function __construct() {
		parent::__construct();
	}

	// Get all tags for the dropdown list
	function get_dropdown_list() {
		$this->db->from('tag');
		$this->db->order_by('tag_id');
		$result = $this->db->get();
		$return = array();
		if($result->num_rows() > 0) {
			foreach($result->result_array() as $row) {
				$return[$row['tag_id']] = $row['tag_title'];
			}
		}
		return $return;
	}

	// insert new group into DB
	function insert_group($group_data, $location_data, $tag_data, $owner_data) {
		// Get geocode for this zipcode
		$geocode = $this->getGeo($location_data['zipcode']);
		// Check that we have a valid geocode before updating db
		if (isset($geocode) && $geocode) {
			// insert values into organization
			$group_success = $this->db->insert('organization', $group_data);
			// Get the group ID and add it to the owner_data array
			$group_id = $this->db->insert_id();
			$owner_data['org_id'] = $group_id;
			//Check if location is in database
			$this->db->start_cache();
			$this->db->where('zipcode', $location_data['zipcode']);
			$this->db->where('address_one', '');
			$this->db->where('address_two', '');
			$loc_query = $this->db->get('location');
			$this->db->stop_cache();
			$this->db->flush_cache();
			//If location isnt in database yet
			if ($loc_query->num_rows() == 0){
				// insert values into location and get the location ID
				$location_success = $this->db->insert('location', $location_data);
				$location_id = $this->db->insert_id();
			} else {
				$locResult = $loc_query->result();
				$location_id = $locResult[0]->location_id;
				$location_success = true;
			}
			$geo_success = $this->db->query('UPDATE location
								SET city = "'.$geocode['city'].'", state = "'.$geocode['state'].'", geolat = '.$geocode['lat'].', geolng = '.$geocode['lng'].'
								WHERE location_id = '.$location_id.'');
			// Get the tag ID
			$this->db->like('tag_title', $tag_data['tag_title']);
			$query = $this->db->get('tag');
			$tag_id_array = $query->result();
			$tag_id = $tag_id_array[0]->tag_id;
			// insert this user into owner and member
			$owner_success = $this->db->insert('owner', $owner_data);
			$member_success = $this->db->insert('member', $owner_data);
			// Create arrays of id_data to insert in DB
			$location_id_data = array(
				'org_id' => $group_id,
				'location_id' => $location_id
			);
			$tag_id_data = array(
				'org_id' => $group_id,
				'tag_id' => $tag_id
			);
			// Call function to insert ids into organization_location and organization_tag
			$id_success = $this->insert_ids($location_id_data, $tag_id_data);
			// return true only if all inserts were successful
			return ($group_success && $location_success && $owner_success &&
					$member_success && $id_success && $geo_success);
		} else {
			return false;
		}
	}

	// insert ids into organization_location and organization_tag
	function insert_ids($location_id_data, $tag_id_data) {
		$location_success = $this->db->insert('organization_location', $location_id_data);
		$tag_success = $this->db->insert('organization_tag', $tag_id_data);
		return ($location_success && $tag_success);
	}

	// Get lattitude and longitude from zip
	function getGeo($zip) {
		if(!empty($zip)){
			//Send request and receive json data by address
			$geocodeFromAddr = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.$zip.'&sensor=false');
			$output = json_decode($geocodeFromAddr);
			//Check if it was a valid zipcode
			if(empty($output->results)) {
				return false;
			}
			//Get latitude and longitute from json data
			$geocode['lat']  = $output->results[0]->geometry->location->lat;
			$geocode['lng'] = $output->results[0]->geometry->location->lng;
			$address_data = $output->results[0]->address_components;
			for ($i = 0; $i < sizeof($address_data); $i++) {
				if ($address_data[$i]->types[0] == "locality") {
					$geocode['city'] = $address_data[$i]->long_name;
				}
				if ($address_data[$i]->types[0] == "administrative_area_level_1") {
					$geocode['state'] = $address_data[$i]->long_name;
				}
			}
			//Return latitude and longitude of the given address
			if(!empty($geocode)){
				return $geocode;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	// get groups joined or owned
	function get_groups($id, $user_type) {

		if ($user_type == 'member') {
			
			$query = 'SELECT org_title FROM organization c JOIN
											(SELECT org_id FROM owner ow
												WHERE ow.user_id =(SELECT user_id FROM user u where u.user_id = '.$id.')) AS f
											USING(org_id)';
			
			$data = $this->db->query('SELECT * FROM organization c JOIN
											(SELECT org_id FROM member m
												WHERE m.user_id =(SELECT user_id FROM user u where u.user_id = '.$id.')) AS f
											USING(org_id)
											WHERE org_title NOT IN ('.$query.')');


		}
		else if ($user_type == 'owner') {
			$data = $this->db->query('SELECT * FROM organization c JOIN
											(SELECT org_id FROM owner ow
												WHERE ow.user_id =(SELECT user_id FROM user u where u.user_id = '.$id.')) AS f
											USING(org_id)');
		}
		else {
			return null;
		}

		return $data->result();
	}

	// get group by group id and return information
	function get_group_by_id($group_id) {
		$group_result = $this->db->query('SELECT a.org_id, a.org_title, a.org_description, a.org_picture, d.user_id, d.user_fname, d.user_lname, d.user_email, e.zipcode
										FROM organization a, owner b, organization_location c, user d, location e
										WHERE a.org_id = '.$group_id.'
										AND a.org_id = b.org_id
										AND a.org_id = c.org_id
										AND b.user_id = d.user_id
										AND c.location_id = e.location_id');
		$query = $group_result->result_array();
		if ($query != null) {
			$group_data = $query[0];
		}
		if (isset($group_data)) {
			return $group_data;
		}
		return NULL;
	}
	
	//Get all events of a group
	function get_group_events($gID) {
		$query = $this->db->query('SELECT event_id, event_picture, event_title, event_description, event_begin_datetime, event_end_datetime
										FROM event
										WHERE event_id IN (SELECT event_id
										                        FROM organization_event
										                        WHERE org_id = '.$gID.')
										AND event_begin_datetime >= NOW()
										ORDER BY event_begin_datetime ASC');
		$group_events = array();
		foreach ($query->result_array() as $row) {
			$group_events[] = array('event_id' => $row['event_id'],'event_title' => $row['event_title'], 'event_begin_datetime' => $row['event_begin_datetime'],
					'event_end_datetime' =>$row['event_end_datetime'], 'event_picture' => $row['event_picture'], 'event_description' => $row['event_description']);
		}
		return $group_events;
	}

	// get first and last name of members in a group
	function get_group_members($group_id) {
		$query = $this->db->query('SELECT user.user_id, user.user_fname, user.user_lname
											FROM user, member
											WHERE user.user_id = member.user_id
											AND member.org_id = '.$group_id.';');
		$group_members = array();
		foreach ($query->result_array() as $row) {
			$group_members[] = array('user_fname' => $row['user_fname'], 'user_lname' => $row['user_lname'], 'user_id' =>$row['user_id']);
		}
		return $group_members;
	}

	//input: geolocation
	//output: array of matching groups information
	function search_groups_query($lat, $lng){

		$radius = 3958.761; //earth mean radius, in miles
		$distance = 10; //miles radius for search

		// latitude boundaries
		$maxLat = (float) $lat + rad2deg($distance / $radius);
		$minLat = (float) $lat - rad2deg($distance / $radius);

		// longitude boundaries (longitude gets smaller when latitude increases)
		$maxLng = (float) $lng + rad2deg($distance / $radius / cos(deg2rad((float) $lat)));
		$minLng = (float) $lng - rad2deg($distance / $radius / cos(deg2rad((float) $lat)));

		$query = $this->db->query("SELECT t2.*, t5.tag_title, (SELECT COUNT(member.member_id) FROM member WHERE t2.org_id = member.org_id) as members_count FROM location t1, organization t2, organization_location t3, organization_tag t4, tag t5
			WHERE t1.location_id = t3.location_id AND t2.org_id = t3.org_id
			AND t2.org_id = t4.org_id AND  t4.tag_id = t5.tag_id
			AND t1.geolat > $minLat AND t1.geolat < $maxLat AND t1.geolng > $minLng AND t1.geolng < $maxLng
			ORDER BY ABS(t1.geolat - $lat) + ABS(t1.geolng - $lng)
			ASC;");

		return $query->result_array();
	}

	//output: array of random groups information
	function get_random_groups(){
		$query	= $this->db->query("SELECT t1.*, t3.tag_title, (SELECT COUNT(member.member_id) FROM member WHERE t1.org_id = member.org_id) as members_count
		 														FROM organization t1, organization_tag t2, tag t3 WHERE t1.org_id = t2.org_id AND t2.tag_id = t3.tag_id ORDER BY RAND() LIMIT 0,12;");
		return $query->result_array();
	}

	// updates group and location in DB
	function update_group($group_data, $location_data, $tag_data) {
		try {
			// Get geocode for this zipcode
			$geocode = $this->getGeo($location_data['zipcode']);
			// Check that we have a valid geocode before updating db
			if (isset($geocode) && $geocode) {
				//update organization table with new values
				$group_success = $this->db->query('UPDATE organization
								SET org_title = "'.$group_data['org_title'].'", org_description = "'.$group_data['org_description'].'",
												org_picture = "'.$group_data['org_picture'].'"
								WHERE org_id = "'.$group_data['org_id'].'"');
				//Update organization_location table with new value
				//Check if location is in database
				$this->db->start_cache();
				$this->db->where('zipcode', $location_data['zipcode']);
				$this->db->where('address_one', '');
				$this->db->where('address_two', '');
				$loc_query = $this->db->get('location');
				$this->db->stop_cache();
				$this->db->flush_cache();
				//If location isnt in database yet
				if ($loc_query->num_rows() == 0){
					// insert values into location and get the location ID
					$location_success = $this->db->insert('location', $location_data);
					$location_id = $this->db->insert_id();
				} else {
					$locResult = $loc_query->result();
					$location_id = $locResult[0]->location_id;
					$location_success = true;
				}
				$location_success = $this->db->query('UPDATE organization_location
								SET location_id = '.$location_id.'
								WHERE org_id = "'.$group_data['org_id'].'"');

				// Get and update geocode for this zipcode
				$geocode = $this->getGeo($location_data['zipcode']);
				$geo_success = $this->db->query('UPDATE location
									SET city = "'.$geocode['city'].'", state = "'.$geocode['state'].'", geolat = '.$geocode['lat'].', geolng = '.$geocode['lng'].'
									WHERE location_id = '.$location_id.'');

				// Get the tag ID and update organization_tag table with new values
				$this->db->like('tag_title', $tag_data['tag_title']);
				$query = $this->db->get('tag');
				$tag_id_array = $query->result();
				$tag_id = $tag_id_array[0]->tag_id;
				$tag_success = $this->db->query('UPDATE organization_tag
								SET tag_id = '.$tag_id.'
								WHERE org_id = "'.$group_data['org_id'].'"');

				if ($group_success && $location_success && $geo_success && $tag_success) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} catch (Exception $e) {
			return false;
		}
	}
	
	
	function insert_group_bulletin($bulletin_data) {
		$bulletin_insert = $this->db->insert('bulletin', array('bulletin_message' => $bulletin_data['bulletin_message'], 'bulletin_datetime' => date('Y-m-d H:i:s'),
				'bulletin_user_id' => $bulletin_data['user_id']));
		$bulletin_id = $this->db->insert_id();
		$bulletin_group = $this->db->insert('organization_bulletin', array('org_id' => $bulletin_data['org_id'], 'bulletin_id' => $bulletin_id));
		return $bulletin_insert && $bulletin_group;
	}

	// gets bulletin message for group
	function get_bulletins($gid) {
		$query = $this->db->query('SELECT bulletin_message, bulletin_datetime, user_fname, user_lname
										FROM bulletin
										LEFT JOIN user ON bulletin_user_id  = user_id
										WHERE bulletin_id IN (SELECT bulletin_id
										                        FROM organization_bulletin
										                        WHERE org_id = '.$gid.')
										AND bulletin_datetime >= DATE_ADD(NOW(), INTERVAL -30 DAY)');
		$group_bulletins = array();
		foreach ($query->result_array() as $row) {
			$group_bulletins[] = array('user_fname' => $row['user_fname'], 'user_lname' => $row['user_lname'],
					'bulletin_message' =>$row['bulletin_message'], 'bulletin_datetime' => $row['bulletin_datetime']);
		}
		return $group_bulletins;
	}

	// join a group
	function join_group($uid, $gid) {
		$data = array('user_id' => $uid, 'org_id' => $gid);
		$this->db->insert('member', $data);
	}

	// leave a group
	function leave_group($uid, $gid) {
		$this->db->delete('member', array('user_id' =>$uid, 'org_id' =>$gid));
	}

	// delete group from database
	function delete_group($gID) {
		$this->db->where('org_id', $gID);
		$this->db->delete('organization');
		$this->db->where('org_id', $gID);
		$this->db->delete('organization_location');
		$this->db->where('org_id', $gID);
		$this->db->delete('organization_tag');
		$this->db->where('org_id', $gID);
		$this->db->delete('organization_event');
        $this->db->where('org_id', $gID);
        $this->db->delete('organization_bulletin');
        $this->db->where('org_id', $gID);
        $this->db->delete('member');
        $this->db->where('org_id', $gID);
        $this->db->delete('owner');
	}

	// gets all org_id's for organizations owned by this user
	function get_owned_by_uid($uid) {
		$data = $this->db->query('SELECT org_id FROM owner WHERE user_id='.$uid.'');
		return $data->result();
	}
	
		// get tag_title by group ID
	function get_tag_by_group($gID) {
		$data = $this->db->query('SELECT tag_title 
				FROM tag JOIN organization_tag ON(tag.tag_id = organization_tag.tag_id)
				WHERE org_id='.$gID.'');
		return $data->result();
	}
	

}
