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
	public function province_list($country2="") {
		$rtArr = array();
    if (empty($country2)) {
      $sql = "SELECT * FROM province ORDER BY orderby ASC";
    } else {
      $sql = "SELECT * FROM province WHERE country2=".$this->db->escape($country2)." ORDER BY orderby ASC";
    }
		return $this->db->query($sql)->result_array();
	}
}
