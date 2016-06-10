<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Log_model extends CI_Model {
	/**
	 *
	 * User activity log
	 *
	 * @param string $type
	 *        	activity type
	 * @param array $para
	 *        	activity information
	 * @return none
	 */
	public function activity($type, $para) {
		$user = $this->session->userdata ( 'user' );
		if (! $user) {
			return;
		}
		
		$log = array (
				'atype' => $type,
				'tm' => date('c'),
				'user_id' => $user['user_id']
		);
		
		if (isset($para['plan_id'])) $log['plan_id'] = $para['plan_id'];
		if (isset($para['customer_id'])) $log['customer_id'] = $para['customer_id'];
		if (isset($para['payment_id'])) $log['payment_id'] = $para['payment_id'];
		if (isset($para['message'])) $log['message'] = $para['message'];
		if (isset($para['systemlog'])) $log['systemlog'] = $para['systemlog'];
		
		$this->db->insert ( "activity", $log );
		return $this->db->insert_id ();
	}
}