<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *COPYRIGHT (C) 2016 Campfire. All Rights Reserved.
 * Event_model.php is the model for an event
 * Solves SE165 Semester Project Fall 2016
 * @author Peter Curtis, Tyler Jones, Troy Nguyen, Marshall Cargle,
 *     Luis Otero, Jorge Aguiniga, Stephen Piazza, Jatinder Verma
*/
class Event_model extends CI_Model {
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

	// insert new event into DB
	function insert_event($event_data, $location_data, $tag_data, $group_data, $event_owner_data) {
		//Check if location is in database
		$this->db->start_cache();
		$this->db->where('address_one', $location_data['address_one']);
		$this->db->where('address_two', $location_data['address_two']);
		$this->db->where('zipcode', $location_data['zipcode']);
		$loc_query = $this->db->get('location');
		$this->db->stop_cache();
		$this->db->flush_cache();

		//If location isnt in database yet
		if ($loc_query->num_rows() == 0){
			//get geocode from google if not in db
			$geocode = $this->getGeo(
				$location_data['address_one'] . " " .
				$location_data['address_two'] . " ".
				$location_data['zipcode']
				);
			if (isset($geocode) && $geocode) {
				//add geocodes to address before insert
				$location_data['geolat'] = $geocode['lat'];
				$location_data['geolng'] = $geocode['lng'];
				$location_data['city'] = $geocode['city'];
				$location_data['state'] = $geocode['state'];
				//new location in db
				$location_success = $this->db->insert('location', $location_data);
				$location_id = $this->db->insert_id();
			} 
		} else { //old location
			$locResult = $loc_query->result();
			$location_id = $locResult[0]->location_id;
			$location_success = true;
		}
		// Check that we have a valid location before updating db
		if ($location_success) {
			// insert values into organization
			$event_success = $this->db->insert('event', $event_data);
			// Get the event ID and add it to the owner_data array
			$event_id = $this->db->insert_id();

			//event_location
			$event_location['event_id'] = $event_id;
			$event_location['location_id'] = $location_id;
			$event_location_success = $this->db->insert('event_location',$event_location);

			//owner
			$event_owner_data['event_id'] = $event_id;
			$event_owner_success = $this->db->insert('event_owner', $event_owner_data);

			//attendee
			$attendee_data['user_id'] = $event_owner_data['owner_id'];
			$attendee_data['event_id'] = $event_id;
			$attendee_success = $this->db->insert('attendee', $attendee_data);

			// Get the tag ID
			$this->db->like('tag_title', $tag_data['tag_title']);
			$query = $this->db->get('tag');
			$tag_id_array = $query->result();
			$tag_id = $tag_id_array[0]->tag_id;
			$event_tag_data['event_id'] = $event_id;
			$event_tag_data['tag_id'] = $tag_id;
			$event_tag_success = $this->db->insert('event_tag', $event_tag_data);
			
			// organization_event
			if (is_numeric($group_data['org_id'])) {
				$group_data['event_id'] = $event_id;
				$group_success = $this->db->insert('organization_event', $group_data);
			} else {
				$group_success = true;
			}
			
			// return true only if all inserts were successful
			return ($event_success &&
				$event_location_success &&
				$event_owner_success &&
				$attendee_success &&
				$event_tag_success &&
				$group_success);
		} else {
			return false;
		}
	}
	
	// update events in the DB
	//TODO NOT COMPLETE YET, STILL WORKING ON THIS, MOSTLY COPY AND PASTED FOR NOW (TYLER)
	function update_event($event_data, $location_data, $tag_data, $group_data) {
		//Check if location is in database
		$this->db->start_cache();
		$this->db->where('address_one', $location_data['address_one']);
		$this->db->where('address_two', $location_data['address_two']);
		$this->db->where('zipcode', $location_data['zipcode']);
		$loc_query = $this->db->get('location');
		$this->db->stop_cache();
		$this->db->flush_cache();

		//If location isnt in database yet
		if ($loc_query->num_rows() == 0){
			//get geocode from google if not in db
			$geocode = $this->getGeo(
				$location_data['address_one'] . " " .
				$location_data['address_two'] . " ".
				$location_data['zipcode']
				);
			if (isset($geocode) && $geocode) {
				//add geocodes to address before insert
				$location_data['geolat'] = $geocode['lat'];
				$location_data['geolng'] = $geocode['lng'];
				$location_data['city'] = $geocode['city'];
				$location_data['state'] = $geocode['state'];
				//new location in db
				$location_success = $this->db->insert('location', $location_data);
				$location_id = $this->db->insert_id();
			} 
		} else { //old location
			$locResult = $loc_query->result();
			$location_id = $locResult[0]->location_id;
			$location_success = true;
		}
		// Check that we have a valid location before updating db
		if ($location_success) {
			// Get the event ID for this event
			$event_id = $event_data['event_id'];

			// update the event table with current data
			$this->db->where('event_id',$event_id);
			$event_success = $this->db->update('event',$event_data);

			//event_location
			$event_location_success = $this->db->query('UPDATE event_location
								SET location_id = '.$location_id.'
								WHERE event_id = "'.$event_id.'"');

			// Get the tag ID
			$this->db->like('tag_title', $tag_data['tag_title']);
			$query = $this->db->get('tag');
			$tag_id_array = $query->result();
			$tag_id = $tag_id_array[0]->tag_id;
			$event_tag_success = $this->db->query('UPDATE event_tag
								SET tag_id = '.$tag_id.'
								WHERE event_id = "'.$event_id.'"');
			
			// organization_event
			$this->db->where('event_id', $event_id);
			$org_event_query = $this->db->get('organization_event');
			if ($org_event_query->num_rows() == 0 && is_numeric($group_data['org_id'])) {
				$group_success = $this->db->insert('organization_event', $group_data);
			} else {
				if (is_numeric($group_data['org_id'])) {
					$group_success = $this->db->query('UPDATE organization_event
									SET org_id = '.$group_data['org_id'].'
									WHERE event_id = "'.$event_id.'"');
				} else {
					$this->db->where('event_id', $event_id);
					$this->db->delete('organization_event');
					$group_success = true;
				}
			}
			
			// return true only if all updates were successful
			return ($event_success &&
				$event_location_success &&
				$event_tag_success &&
				$group_success);
		} else {
			return false;
		}
	}

	// Get lattitude and longitude from address
	function getGeo($address) {
		if(!empty($address)){
			//build a string with each part of the address
	        $formattedAddress = str_replace(' ','+',$address);
			//Send request and receive json data by address
			$geocodeFromAddr = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($formattedAddress).'&sensor=false');
			$output = json_decode($geocodeFromAddr);
			//Check if it was a valid address
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
			//Return latitude, longitude, city, and state of the given address
			if(!empty($geocode) && !empty($geocode['city']) && !empty($geocode['state'])){
				return $geocode;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	// get event by event id and return information
	function get_event_by_id($event_id) {
		$event_result = $this->db->query('SELECT a.event_id, a.event_title, a.event_description, a.event_begin_datetime,
											a.event_end_datetime, a.event_picture, d.user_fname, d.user_id, d.user_lname, d.user_email,
											e.address_one, e.address_two, e.zipcode, e.city, e.state, e.geolat, e.geolng
											FROM event a, event_owner b, event_location c, user d, location e
											WHERE a.event_id = '.$event_id.'
											AND a.event_id = b.event_id
											AND a.event_id = c.event_id
											AND b.owner_id = d.user_id
											AND c.location_id = e.location_id');
		$query = $event_result->result_array();
		if ($query != null) {
			$event_data = $query[0];
		}
		if (isset($event_data)) {
			return $event_data;
		}
		return NULL;
	}

	// get first and last name of members in a event
	function get_event_members($event_id) {
		$query = $this->db->query('SELECT user.user_id, user.user_fname, user.user_lname
											FROM user, attendee
											WHERE user.user_id = attendee.user_id
											AND attendee.event_id = '.$event_id.';');
		$event_members = array();
		foreach ($query->result_array() as $row) {
			$event_members[] = array('user_fname' => $row['user_fname'], 'user_lname' => $row['user_lname'], 'user_id' =>$row['user_id']);
		}
		return $event_members;
	}

	// get events created by or RSVP'd for
	function get_events_by_user_id($id, $retrieval_type) {
										
		if ($retrieval_type == 'owned') 
		{
			$data = $this->db->query('SELECT * FROM event ev JOIN 
										(SELECT event_id FROM event_owner ow 
											WHERE ow.owner_id = '.$id.') AS f 
											USING(event_id)');
			
		}
		else if ($retrieval_type == 'rsvp') 
		{
			$query = 'SELECT event_title FROM event ev JOIN (SELECT event_id FROM event_owner ow WHERE ow.owner_id = '.$id.') AS f USING(event_id)';
			$data = $this->db->query('SELECT * FROM event ev JOIN 
										(SELECT event_id FROM attendee a 
											WHERE a.user_id = '.$id.') AS f 
										USING(event_id) WHERE event_title NOT IN ('.$query.')');
		}
		else {
			return null;
		}

		return $data->result();
	}

	function insert_event_bulletin($bulletin_data) {
		$bulletin_insert = $this->db->insert('bulletin', array('bulletin_message' => $bulletin_data['bulletin_message'], 'bulletin_datetime' => date('Y-m-d H:i:s'),
				'bulletin_user_id' => $bulletin_data['user_id']));
		$bulletin_id = $this->db->insert_id();
		$bulletin_event = $this->db->insert('event_bulletin', array('event_id' => $bulletin_data['event_id'], 'bulletin_id' => $bulletin_id));
		return $bulletin_insert && $bulletin_event;
	}
	
	// gets bulletin message for event
	function get_bulletins($eventId) {
		$query = $this->db->query('SELECT bulletin_message, bulletin_datetime, user_fname, user_lname
									FROM bulletin
									LEFT JOIN user ON bulletin_user_id  = user_id
									WHERE bulletin_id IN (SELECT bulletin_id
									                        FROM event_bulletin
									                        WHERE event_id = '.$eventId.')
									AND bulletin_datetime >= DATE_ADD(NOW(), INTERVAL -30 DAY)');
		$event_bulletins = array();
		foreach ($query->result_array() as $row) {
			$event_bulletins[] = array('user_fname' => $row['user_fname'], 'user_lname' => $row['user_lname'],
					'bulletin_message' =>$row['bulletin_message'], 'bulletin_datetime' => $row['bulletin_datetime']);
		}
		return $event_bulletins;
	}

	// join an event
	function join_event($uid, $eventId) {
		$data = array('user_id' => $uid, 'event_id' => $eventId);
		$this->db->insert('attendee', $data);
	}

	// leave an event
	function leave_event($uid, $eventId) {
		$this->db->delete('attendee', array('user_id' =>$uid, 'event_id' =>$eventId));
	}

	// delete event by event id
	function delete_event($event_id) {
		$data = $this->db->query('SELECT bulletin_id FROM event_bulletin WHERE event_id='.$event_id.'');
		$bulletinIDs = $data->result();
		// Delete all bulletins with this event_id
		foreach ($bulletinIDs as &$value) {
			$this->db->where('bulletin_id', $value->bulletin_id);
			$this->db->delete('bulletin');
		}
		$this->db->where('event_id', $event_id);
		$this->db->delete('event');
		$this->db->where('event_id', $event_id);
        $this->db->delete('event_bulletin');
		$this->db->where('event_id', $event_id);
		$this->db->delete('event_location');
		$this->db->where('event_id', $event_id);
        $this->db->delete('event_owner');
		$this->db->where('event_id', $event_id);
		$this->db->delete('event_tag');
		$this->db->where('event_id', $event_id);
		$this->db->delete('organization_event');
	}

	//generate list of nearby event
	function get_nearby_events($lat, $lng, $dist){
		$radius = 3958.761; //earth mean radius, in miles
		$distance = $dist; //miles radius for search

		// latitude boundaries
		$maxLat = (float) $lat + rad2deg($distance / $radius);
		$minLat = (float) $lat - rad2deg($distance / $radius);

		// longitude boundaries (longitude gets smaller when latitude increases)
		$maxLng = (float) $lng + rad2deg($distance / $radius / cos(deg2rad((float) $lat)));
		$minLng = (float) $lng - rad2deg($distance / $radius / cos(deg2rad((float) $lat)));

		$query = $this->db->query("SELECT t2.*, t5.tag_title, t1.geolat, t1.geolng, (SELECT COUNT(attendee.attendee_id) FROM attendee WHERE t2.event_id = attendee.event_id) as attendee_count FROM location t1, event t2, event_location t3, event_tag t4, tag t5
			WHERE t1.location_id = t3.location_id AND t2.event_id = t3.event_id
			AND t2.event_id = t4.event_id AND  t4.tag_id = t5.tag_id
			AND t1.geolat > $minLat AND t1.geolat < $maxLat AND t1.geolng > $minLng AND t1.geolng < $maxLng;");

		return $query->result_array();
	}
	
	// gets all event_id's for events owned by this user
	function get_owned_by_uid($uid) {
		$data = $this->db->query('SELECT event_id FROM event_owner WHERE owner_id='.$uid.'');
		return $data->result();
	}
	
	// get tag_title by event ID
	function get_tag_by_event($event_id) {
		$data = $this->db->query('SELECT tag_title 
				FROM tag JOIN event_tag ON(tag.tag_id = event_tag.tag_id)
				WHERE event_id='.$event_id.'');
		return $data->result();
	}
	
	// get org_title by event ID
	function get_group_by_event($event_id) {
		$data = $this->db->query('SELECT org_title 
				FROM organization JOIN organization_event ON(organization.org_id = organization_event.org_id)
				WHERE event_id='.$event_id.'');
		return $data->result();
	}
}

