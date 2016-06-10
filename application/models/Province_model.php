<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Province_model extends CI_Model {
	/**
	 * Get user group list
	 * 
	 * @param	integer	$user_group_id
	 * @return	array					user table search result
	 */
	public function province_list() {
		$rtArr = array();
		$sql = "SELECT * FROM province ORDER BY orderby ASC";
		return $this->db->query($sql)->result_array();
	}
}
