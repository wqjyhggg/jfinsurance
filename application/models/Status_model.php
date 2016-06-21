<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Status_model extends CI_Model {
	/**
	 * Get All status list
	 * 
	 * @return	array					user table search result
	 */
	public function status_array() {
		$sql = "SELECT * FROM status ORDER BY status_id ASC";
		return $this->db->query($sql)->result_array();
	}

	/**
	 * Get All status indexed array
	 * 
	 * @return	array					user table search result
	 */
	public function status_list() {
		$rt = $this->status_array();
		$rArr = array();
		foreach ($rt as $rc) {
			$rArr[$rc['status_id']] = $rc;
		}
		return $rArr;
	}
}
