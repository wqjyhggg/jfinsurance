<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  CREATE TABLE `announcement_user` ( 
    `user_id` INT NOT NULL,
    `announcement_id` INT NOT NULL,
    `status` tinyint NOT NULL DEFAULT 1,
    `update_tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY  (`user_id`,`announcement_id`)) ENGINE = MyISAM;
*/
class Announcement_user_model extends CI_Model {
  public function set_read($announcement_id, $user_id) {
    return $this->db->query("insert into announcement_user (announcement_id, user_id) values ('".$announcement_id."','".$user_id."')";
  }

  public function get_user_announcement_ids($user_id) {
    return $this->db->where("user_id", $user_id)->get("announcement_user")->result_array();
  }

  public function get_announcement_user_ids($announcement_id) {
    return $this->db->where("announcement_id", $announcement_id)->get("announcement_user")->result_array();
  }
}
