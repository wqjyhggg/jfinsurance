<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  CREATE TABLE `training_user` ( 
    `user_id` INT NOT NULL,
    `training_id` INT NOT NULL,
    `status` tinyint NOT NULL DEFAULT 1,
    `update_tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY  (`user_id`,`training_id`)) ENGINE = MyISAM;
*/
class Training_user_model extends CI_Model {
  public function set_read($training_id, $user_id) {
    return $this->db->query("insert into training_user (training_id, user_id) values ('".$training_id."','".$user_id."'");
  }

  public function get_user_training_ids($user_id) {
    return $this->db->where("user_id", $user_id)->get("training_user")->result_array();
  }

  public function get_training_user_ids($training_id) {
    return $this->db->where("training_id", $training_id)->get("training_user")->result_array();
  }
}
