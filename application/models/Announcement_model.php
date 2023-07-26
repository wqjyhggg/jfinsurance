<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  CREATE TABLE `announcement` ( 
    `announcement_id` INT NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(64) NOT NULL ,
    `desc` TEXT,
    `start_tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `status` tinyint NOT NULL DEFAULT 1,
    `update_tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY  (`announcement_id`)) ENGINE = MyISAM;
*/
class Announcement_model extends CI_Model {
  public function get_by_id($announcement_id) {
    return $this->db->get("announcement", array("announcement_id" => $announcement_id))->row_array();
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
		if (isset($para['status'])) {
      $this->db->set("status", trim($para["status"]));
    }
    if ($this->db->insert('announcement')) {
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
		if (isset($para['status'])) {
      $this->db->set("status", trim($para["status"]));
    }
    $this->db->where("announcement_id", $id);
    if ($this->db->update('announcement')) {
      return $id;
    }
    return 0;
	}
	
	public function search($para, $limit=0, $start=-1) {
    if (isset($para['announcement_id'])) {
      $this->db->where("announcement_id", trim($para["announcement_id"]));
    }
    if (isset($para['title'])) {
      $this->db->where("title", trim($para["title"]));
    }
    if (isset($para['desc'])) {
      $this->db->where("desc", trim($para["desc"]));
    }
		if (isset($para['status'])) {
      $this->db->where("status", trim($para["status"]));
    }
		if (empty($para['all'])) {
      $this->db->where("start_tm>=", date("Y-m-d H:i:s"));
    }
    $this->db->order_by("announcement_id", "DESC");
 		return $this->db->get('announcement')->result_array();
	}
}