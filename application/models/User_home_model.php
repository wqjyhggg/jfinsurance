<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class User_home_model extends CI_Model
{
  // CREATE TABLE `user_home` (
  //   `user_id` INT NOT NULL ,
  //   `paras` TEXT, 
  //   PRIMARY KEY (`user_id`)) ENGINE = MyISAM;	
  /**
   *
   * Get customer by id
   *
   * @param integer	$customer_id
   * @return array
   */
  public function get_by_id($user_id)
  {
    $this->db->where("user_id", $user_id);
    return $this->db->get('user_home')->row_array();
  }

  public function save($user_id, $paras)
  {
    if ($rc = $this->get_by_id($user_id)) {
      $this->db->set('paras', $paras);
      $this->db->where("user_id", $user_id);
      $this->db->update('user_home');
    } else {
      $this->db->set('paras', $paras);
      $this->db->set('user_id', $user_id);
      $this->db->insert('user_home');
    }
    return 0;
  }
}
