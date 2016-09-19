<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Paytype_model extends CI_Model {
	/**
	 * Get All paytype list
	 * 
	 * @return	array					user table search result
	 */
	public function paytype_list() {
		return array('Credit Card', 'Cash', 'Cheque');
	}

	/**
	 * Get Default pay type only
	 * 
	 * @return	array					user table search result
	 */
	public function paytype_default() {
		return array('Credit Card');
	}
}
