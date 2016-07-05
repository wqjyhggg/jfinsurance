<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Coverage_model extends CI_Model {
	public $logstr;
	public $sqlstr;
	
	/**
	 *
	 * Get coverage code
	 *
	 * @return array
	 */
	public function get_coverage_codes() {
		return $this->db->get('coverage_code')->result_array();
	}
}