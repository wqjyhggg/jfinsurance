<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Myhome_model extends CI_Model {
	public $logstr;
	public $sqlstr;
	public $error;
	public $logo_width = 390;
	public $image_width = 1500;
	
	/**
	 * Get myhome record by user_id
	 *
	 * @param integer $user_id
	 * @return array
	 */
	public function get_myhome($user_id) {
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('myhome');
		return $query->row_array();
	}
	
	/**
	 * Get myhome record by user_id
	 *
	 * @param integer $user_id
	 * @return string
	 */
	public function get_myhome_name($user_id) {
		$my = $this->get_myhome($user_id);
		if ($my) {
			return $my['myname'];
		}
		return '';
	}
	
	/**
	 * Format myname
	 *
	 * @param string $firstname
	 * @param string $lastname
	 * @return string
	 */
	public function get_myname($firstname, $lastname) {
		$firstname = str_replace('/[^a-z0-9]/', '', strtolower($firstname));
		$lastname = str_replace('/[^a-z0-9]/', '', strtolower($lastname));
		return $firstname . '_' . $lastname;
	}

	/**
	 * Return log name
	 *
	 * @return string
	 */
	public function get_logo_filename() {
		$beuser = $this->session->userdata('beuser');
		return md5($beuser['user_id']) . 'logo';
	}

	/**
	 * Return image name
	 *
	 * @param integer $plan_id
	 * @return array
	 */
	public function get_image_filename() {
		$beuser = $this->session->userdata('beuser');
		return md5($beuser['user_id']) . 'back';
	}

	/**
	 * Return log width
	 *
	 * @return integer
	 */
	public function get_logo_width() {
		return $this->logo_width;
	}

	/**
	 * Return image width
	 *
	 * @return integer
	 */
	public function get_image_width() {
		return $this->image_width;
	}

	/**
	 * Get record by name
	 *
	 * @param string $name
	 * @return array
	 */
	public function get_myhome_by_name($name) {
		$this->db->where('myname', $name);
		$query = $this->db->get('myhome');
		return $query->row_array();
	}

	/**
	 * Insert / Upload Record
	 * 
	 * @param array input array
	 * @return
	 * 
	 */
	public function update($para) {
		$this->logstr = '';
		$where = '';
		if (empty($para['user_id']) || empty($para['myname'])) {
			return 0;
		}
		$my = $this->get_myhome($para['user_id']);
		if ($my) {
			$sql = "UPDATE myhome SET last_update=NOW()";
			$where = " WHERE user_id=" . (int)$para['user_id'];
		} else {
			$sql = "INSERT INTO myhome SET last_update=NOW(), user_id=" . (int)$para['user_id'];
		}
		
		if (!empty($para['myname']) && !($my && ($my['myname'] == $para['myname']))) {
			$sql .= ", myname=" . $this->db->escape($para['myname']);
			$this->logstr .= 'agentname=>'.$para['myname']."; ";
		}
		if (!empty($para['logo']) && !($my && ($my['logo'] == $para['logo']))) {
			$sql .= ", logo=" . $this->db->escape($para['logo']);
			$this->logstr .= 'logo=>'.$para['logo']."; ";
		}
		if (!empty($para['image']) && !($my && ($my['image'] == $para['image']))) {
			$sql .= ", image=" . $this->db->escape($para['image']);
			$this->logstr .= 'image=>'.$para['image']."; ";
		}
		if (!empty($para['top_title']) && !($my && ($my['top_title'] == $para['top_title']))) {
			$sql .= ", top_title=" . $this->db->escape($para['top_title']);
			$this->logstr .= 'top_title=>'.$para['top_title']."; ";
		}
		if (!empty($para['top_desc']) && !($my && ($my['top_desc'] == $para['top_desc']))) {
			$sql .= ", top_desc=" . $this->db->escape($para['top_desc']);
			$this->logstr .= 'top_desc=>'.$para['top_desc']."; ";
		}
		if (!empty($para['foot_title']) && !($my && ($my['foot_title'] == $para['foot_title']))) {
			$sql .= ", foot_title=" . $this->db->escape($para['foot_title']);
			$this->logstr .= 'foot_title=>'.$para['foot_title']."; ";
		}
		if (!empty($para['address']) && !($my && ($my['address'] == $para['address']))) {
			$sql .= ", address=" . $this->db->escape($para['address']);
			$this->logstr .= 'address=>'.$para['address']."; ";
		}
		if (!empty($para['city_province']) && !($my && ($my['city_province'] == $para['city_province']))) {
			$sql .= ", city_province=" . $this->db->escape($para['city_province']);
			$this->logstr .= 'city_province=>'.$para['city_province']."; ";
		}
		if (!empty($para['post_code']) && !($my && ($my['post_code'] == $para['post_code']))) {
			$sql .= ", post_code=" . $this->db->escape($para['post_code']);
			$this->logstr .= 'post_code=>'.$para['post_code']."; ";
		}
		if (!empty($para['phone']) && !($my && ($my['phone'] == $para['phone']))) {
			$sql .= ", phone=" . $this->db->escape($para['phone']);
			$this->logstr .= 'phone=>'.$para['phone']."; ";
		}
		if (!empty($para['fax']) && !($my && ($my['fax'] != $para['fax']))) {
			$sql .= ", fax=" . $this->db->escape($para['fax']);
			$this->logstr .= 'fax=>'.$para['fax']."; ";
		}
		if (!empty($para['toll_free']) && !($my && ($my['toll_free'] == $para['toll_free']))) {
			$sql .= ", toll_free=" . $this->db->escape($para['toll_free']);
			$this->logstr .= 'toll_free=>'.$para['toll_free']."; ";
		}
		if (!empty($para['email']) && !($my && ($my['email'] == $para['email']))) {
			$sql .= ", email=" . $this->db->escape($para['email']);
			$this->logstr .= 'email=>'.$para['email']."; ";
		}
		$this->db->query($sql . $where);
		$this->sqlstr = $this->db->last_query();
		$last_id = $this->db->insert_id();
		$this->sqlstr .= "; (" . $last_id . ")";
		$this->logstr .= "; (" . $last_id . ")";
		return $last_id;
	}
}
