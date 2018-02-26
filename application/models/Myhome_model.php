<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Myhome_model extends CI_Model {
	public $logstr;
	public $sqlstr;
	public $error;
	public $logo_width = 390;
	public $image_width = 1500;
	public $pdf_logo_width = 80;
	public $pdf_qr_width = 60;
	public $pdf_qr2_width = 60;
	
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
	 * Return qr name
	 *
	 * @param integer $plan_id
	 * @return array
	 */
	public function get_qr_filename() {
		$beuser = $this->session->userdata('beuser');
		return md5($beuser['user_id']) . 'qr';
	}

	/**
	 * Return log name
	 *
	 * @return string
	 */
	public function get_pdf_logo_filename() {
		$beuser = $this->session->userdata('beuser');
		return md5($beuser['user_id']) . 'pdflogo';
	}

	/**
	 * Return image name
	 *
	 * @param string $name
	 * @return array
	 */
	public function get_pdf_filename($name) {
		$beuser = $this->session->userdata('beuser');
		return md5($beuser['user_id']) . 'pdf' . $name;
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
	 * Return log width
	 *
	 * @return integer
	 */
	public function get_pdf_logo_width() {
		return $this->pdf_logo_width;
	}

	/**
	 * Return image width
	 *
	 * @param string $name
	 * @return integer
	 */
	public function get_pdf_width($name) {
		$name = "pdf_". $name . "_width";
		return $this->$name;
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
	 * Get user by name
	 *
	 * @param string $name
	 * @return array
	 */
	public function get_user_by_name($name) {
		$sql = "SELECT * FROM user WHERE CONCAT(LOWER(firstname), '_', LOWER(lastname))=". $this->db->escape($name);
		$query = $this->db->query($sql);
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
		
		if (isset($para['myname']) && (empty($my) || ($my['myname'] != $para['myname']))) {
			$sql .= ", myname=" . $this->db->escape($para['myname']);
			$this->logstr .= 'agentname=>'.$para['myname']."; ";
		}
		if (isset($para['logo']) && (empty($my) || ($my['logo'] != $para['logo']))) {
			$sql .= ", logo=" . $this->db->escape($para['logo']);
			$this->logstr .= 'logo=>'.$para['logo']."; ";
		}
		if (isset($para['image']) && (empty($my) || ($my['image'] != $para['image']))) {
			$sql .= ", image=" . $this->db->escape($para['image']);
			$this->logstr .= 'image=>'.$para['image']."; ";
		}
		if (isset($para['qr']) && (empty($my) || ($my['qr'] != $para['qr']))) {
			$sql .= ", qr=" . $this->db->escape($para['qr']);
			$this->logstr .= 'qr=>'.$para['qr']."; ";
		}
		if (isset($para['qr_desc']) && (empty($my) || ($my['qr_desc'] != $para['qr_desc']))) {
			$sql .= ", qr_desc=" . $this->db->escape($para['qr_desc']);
			$this->logstr .= 'qr_desc=>'.$para['qr_desc']."; ";
		}
		if (isset($para['top_title']) && (empty($my) || ($my['top_title'] != $para['top_title']))) {
			$sql .= ", top_title=" . $this->db->escape($para['top_title']);
			$this->logstr .= 'top_title=>'.$para['top_title']."; ";
		}
		if (isset($para['top_desc']) && (empty($my) || ($my['top_desc'] != $para['top_desc']))) {
			$sql .= ", top_desc=" . $this->db->escape($para['top_desc']);
			$this->logstr .= 'top_desc=>'.$para['top_desc']."; ";
		}
		if (isset($para['about_title']) && (empty($my) || ($my['about_title'] != $para['about_title']))) {
			$sql .= ", about_title=" . $this->db->escape($para['about_title']);
			$this->logstr .= 'about_title=>'.$para['about_title']."; ";
		}
		if (isset($para['about_short']) && (empty($my) || ($my['about_short'] != $para['about_short']))) {
			$sql .= ", about_short=" . $this->db->escape($para['about_short']);
			$this->logstr .= 'about_short=>'.$para['about_short']."; ";
		}
		if (isset($para['about_desc']) && (empty($my) || ($my['about_desc'] != $para['about_desc']))) {
			$sql .= ", about_desc=" . $this->db->escape($para['about_desc']);
			$this->logstr .= 'about_desc=>'.$para['about_desc']."; ";
		}
		if (isset($para['foot_title']) && (empty($my) || ($my['foot_title'] != $para['foot_title']))) {
			$sql .= ", foot_title=" . $this->db->escape($para['foot_title']);
			$this->logstr .= 'foot_title=>'.$para['foot_title']."; ";
		}
		if (isset($para['address']) && (empty($my) || ($my['address'] != $para['address']))) {
			$sql .= ", address=" . $this->db->escape($para['address']);
			$this->logstr .= 'address=>'.$para['address']."; ";
		}
		if (isset($para['city_province']) && (empty($my) || ($my['city_province'] != $para['city_province']))) {
			$sql .= ", city_province=" . $this->db->escape($para['city_province']);
			$this->logstr .= 'city_province=>'.$para['city_province']."; ";
		}
		if (isset($para['post_code']) && (empty($my) || ($my['post_code'] != $para['post_code']))) {
			$sql .= ", post_code=" . $this->db->escape($para['post_code']);
			$this->logstr .= 'post_code=>'.$para['post_code']."; ";
		}
		if (isset($para['phone']) && (empty($my) || ($my['phone'] != $para['phone']))) {
			$sql .= ", phone=" . $this->db->escape($para['phone']);
			$this->logstr .= 'phone=>'.$para['phone']."; ";
		}
		if (isset($para['fax']) && (empty($my) || ($my['fax'] != $para['fax']))) {
			$sql .= ", fax=" . $this->db->escape($para['fax']);
			$this->logstr .= 'fax=>'.$para['fax']."; ";
		}
		if (isset($para['toll_free']) && (empty($my) || ($my['toll_free'] != $para['toll_free']))) {
			$sql .= ", toll_free=" . $this->db->escape($para['toll_free']);
			$this->logstr .= 'toll_free=>'.$para['toll_free']."; ";
		}
		if (isset($para['toll_free_fax']) && (empty($my) || ($my['toll_free_fax'] != $para['toll_free_fax']))) {
			$sql .= ", toll_free_fax=" . $this->db->escape($para['toll_free_fax']);
			$this->logstr .= 'toll_free_fax=>'.$para['toll_free_fax']."; ";
		}
		if (isset($para['email']) && (empty($my) || ($my['email'] != $para['email']))) {
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
