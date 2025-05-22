<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class User_notify_model extends CI_Model {
  // CREATE TABLE `user_nofify` (
  //   `user_id` INT NOT NULL ,
  //   `notify_type` TINYINT NOT NULL DEFAULT '0' COMMENT '0: none; 1: monthly, 2: every half month', 
  //   `for_type` VARCHAR(8) NOT NULL DEFAULT 'Expire' COMMENT 'Expire, Effect', 
  //   PRIMARY KEY (`user_id`)) ENGINE = MyISAM;	
	/**
	 *
	 * Get customer by id
	 *
	 * @param integer	$customer_id
	 * @return array
	 */
	public function get_by_id($user_id, $for_type="Expire") {
		$this->db->where( "user_id", $user_id );
    $this->db->where( "for_type", $for_type);
		return $this->db->get('user_nofify')->row_array();
	}

	public function save($user_id, $notify_type, $for_type="Expire") {
    $this->db->set('notify_type', $notify_type);
    $this->db->set('for_type', $for_type);
		if ($rc = $this->get_by_id($user_id)) {
      $this->db->where( "user_id", $user_id );
      $this->db->update('user_nofify');
    } else {
      $this->db->set('user_id', $user_id);
      $this->db->insert('user_nofify');
    }
		return 0;
	}

	public function get_notify_list($notify_type=0, $for_type="Expire") {
    $this->db->where( "notify_type >", $notify_type);
    $this->db->where( "for_type", $for_type);
		return $this->db->get('user_nofify')->result_array();
	}
}
