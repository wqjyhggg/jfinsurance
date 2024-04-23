<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  CREATE TABLE `user_province` ( 
    `id` int NOT NULL AUTO_INCREMENT, 
    `user_id` int NOT NULL, 
    `province2` char(2) NOT NULL COMMENT 'ID from province table', 
    PRIMARY KEY (`id`), 
    UNIQUE KEY `user_prov` (`user_id`,`province2`) ) ENGINE=MyISAM
*/
class User_province_model extends CI_Model {
  public function get_by_id($id) {
    return $this->db->get_where("user_province", array("id" => $id))->row_array();
  }

  public function get_by_user_province($user_id, $province2) {
    return $this->db->get_where("user_province", array("user_id" => $user_id, "province2" => $province2))->row_array();
  }

  public function add($user_id, $province2) {
    return $this->db->insert("user_province", array("user_id" => $user_id, "province2" => $province2));
  }
	
  public function remove_by_user($user_id) {
    return $this->db->delete("user_province", array("user_id" => $user_id));
  }
	
  public function get_by_user($user_id) {
    return $this->db->get_where("user_province", array("user_id" => $user_id))->result_array();
  }
}
