<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Paytype_model extends CI_Model {
	/**
	 * Get All product list
	 * 
	 * @return	array					user table search result
	 */
	public function paytype_list() {
		return array('Credit Cart', 'Cash', 'Cheque');
	}
}
