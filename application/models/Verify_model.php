<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

/**
 * Input verify
 *
 * @author Jack Wu
 *        
 */
class Verify_model extends CI_Model {
	private $redirctKey = 'AdR2015';
	
	/**
	 * Remove all UTF8 characters
	 *
	 * @param string $str        	
	 * @return string without UTF8
	 */
	private function utf8remove($str) {
		$b2 = '#([\xC0-\xDF][\x80-\xBF])*#';
		$b3 = '#([\xE0-\xEF][\x80-\xBF]{2})*#';
		$b4 = '#([\xF0-\xF7][\x80-\xBF]{3})*#';
		$str = preg_replace ( $b2, '', $str );
		$str = preg_replace ( $b3, '', $str );
		$str = preg_replace ( $b4, '', $str );
		return ($str);
	}
	
	/**
	 * string verify
	 * 
	 * @param string $str
	 * @return boolean
	 */
	public function isNormalStr($str) {
		$pt = '#[^A-Za-z0-9 \-_:/\.\?\=\|]#';
		$str = $this->utf8remove ( $str );
		if (preg_match ( $pt, $str ) === TRUE) {
			//die("111");
			return FALSE;
		}
		//die("222");
		return TRUE;
	}
	
	/**
	 * time string verify
	 * 
	 * @param string $str
	 * @return boolean
	 */
	public function isTimeStr($str) {
		$pt = '#[^0-9 \-_:/\.]#';
		if (preg_match ( $pt, $str ) === TRUE) {
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * phone number verify
	 * 
	 * @param string $str
	 * @return boolean
	 */
	public function isPhoneStr($str) {
		$pt = '#[^0-9\-\(\)]#';
		$str = $this->utf8remove ( $str );
		if (preg_match ( $pt, $str ) !== FALSE) {
			return FALSE;
		}
		$len = strlen ( $str );
		if (($len < 10) || ($len > 15)) {
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * Remove script tag and php tag from uploaded html content
	 * 
	 * @param string $str
	 * @return boolean
	 */
	public function removeTags($str) {
		$str = str_replace('<'.'?', '', $str );		// remove < ?
		$str = str_ireplace('<SCRIPT', '', $str );		// remove <script
		return $str;
	}
	
	/**
	 * email verify
	 * 
	 * @param string $str
	 * @return boolean
	 */
	public function isEmail($str) {
		if (! filter_var ( $str, FILTER_VALIDATE_EMAIL )) {
			return FALSE;
		}
		
		// Email server confirm ?
		return TRUE;
	}
}
