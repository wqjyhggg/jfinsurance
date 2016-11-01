<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Region_model extends CI_Model {
    public $logstr;
	public $sqlstr;
	
	/**
	 * Get region list
	 * 
	 * @return	array					user table search result
	 */
	public function get_regions() {
		$regions = $this->db->get('region')->result_array();
		$rArr = array();
		foreach ($regions as $r) {
			$rArr[$r['region_id']] = $r['name'];
		}
		ksort($rArr);
		return $rArr;
	}
	
	/**
	 * Get region name
	 * 
	 * @param integer $region_id
	 * @return	string
	 */
	public function get_name($region_id) {
		$this->db->where('region_id', $region_id);
		return $this->db->get('region')->row_array();
	}
}
