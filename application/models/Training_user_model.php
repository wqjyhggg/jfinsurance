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
class Training_model extends CI_Model {
  public function get_by_id($training_id) {
    return $this->db->get("training", array("training_id" => $training_id))->row_array();
  }
	
	public function add($para) {
		if (empty($para['title'])) {
			return 0;
		} else {
      $this->db->set("title", trim($para["title"]));
		}
		if (isset($para['desc'])) {
      $this->db->set("desc", trim($para["desc"]));
    }
		if (isset($para['start_tm'])) {
      $this->db->set("start_tm", trim($para["start_tm"]));
    } else {
      $this->db->set("start_tm", date("Y-m-d H:i:s"));
    }
		if (isset($para['links'])) {
      $this->db->set("links", trim($para["links"]));
    }
		if (isset($para['status'])) {
      $this->db->set("status", trim($para["status"]));
    }
    if ($this->db->insert('training')) {
      $id = $this->db->insert_id();
      return $id;
    }
    return 0;
	}
	
	public function update($id, $para) {
    if (isset($para['title'])) {
      $this->db->set("title", trim($para["title"]));
		}
		if (isset($para['desc'])) {
      $this->db->set("desc", trim($para["desc"]));
    }
		if (isset($para['start_tm'])) {
      $this->db->set("start_tm", trim($para["start_tm"]));
    }
		if (isset($para['links'])) {
      $this->db->set("links", trim($para["links"]));
    }
		if (isset($para['status'])) {
      $this->db->set("status", trim($para["status"]));
    }
    $this->db->where("training_id", $id);
    if ($this->db->update('training')) {
      return $id;
    }
    return 0;
	}
	
	public function search($para, $limit=0, $start=-1) {
    if (isset($para['training_id'])) {
      $this->db->where("training_id", trim($para["training_id"]));
    }
    if (isset($para['title'])) {
      $this->db->where("title", trim($para["title"]));
    }
    if (isset($para['desc'])) {
      $this->db->like("desc", trim($para["desc"]));
    }
		if (isset($para['links'])) {
      $this->db->like("links", trim($para["links"]));
    }
		if (isset($para['status'])) {
      $this->db->where("status", trim($para["status"]));
    }
		if (empty($para['all'])) {
      $this->db->where("start_tm>=", date("Y-m-d H:i:s"));
    }
    $this->db->order_by("training_id", "DESC");
 		return $this->db->get('training')->result_array();
	}
}