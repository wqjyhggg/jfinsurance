<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

/**
 * @author Jack Wu
 * htmlspecialchars 
 */
class Html_model extends CI_Model {
	/**
	 * Remove all double quotes
	 *
	 * @param string $str        	
	 * @return string without UTF8
	 */
	public function escapeQuote2($str) {
		$str = preg_replace ('/"/', '&quot;', $str);
		return ($str);
	}

	/**
	 * Remove all double quotes
	 *
	 * @param string $str        	
	 * @return string without UTF8
	 */
	public function escapeQuote($str) {
		$str = preg_replace ("/'/", '&#039;', $str);
		return ($str);
	}
}