<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_group_model extends CI_Model {
	/**
	 * Get user group list
	 * 
	 * @param	integer	$user_group_id
	 * @return	array					user table search result
	 */
	public function get_user_group_list($user_group_id) {
		$rtArr = array();
		$sql = "SELECT * FROM user_group WHERE user_group_id >= '" . (int)$user_group_id . "'";
		$rt = $this->db->query($sql)->result_array();
		foreach($rt as $rc) {
			$rtArr[$rc['user_group_id']] = $rc['name'];
		}
		return $rtArr;
	}
}
