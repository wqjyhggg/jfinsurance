<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Geo_model extends CI_Model {
	/**
	 *
	 * Get active country list
	 *
	 * @return array
	 */
	public function get_country() {
		$this->db->where( "status", 1 );
		return $this->db->get('country')->result_array();
	}

	/**
	 *
	 * Get province list by country
	 *
	 * @return array
	 */
	public function get_province($country) {
		$this->db->where( "country2", $country );
		return $this->db->get('province')->result_array();
	}
}