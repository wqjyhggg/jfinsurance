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

	public function get_coverage_desc_by_code($coverage_code_id) {
		$this->db->where('coverage_code_id', $coverage_code_id);
		$row = $this->db->get('coverage_code')->row_array();
		if ($row) {
			return $row['name'];
		}
		return 'Special Coverage';
	}
}