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
    `orderby` tinyint NOT NULL DEFAULT 0,
    `update_tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY  (`announcement_id`)) ENGINE = MyISAM;
*/
class Announcement_model extends CI_Model {
  public function get_by_id($announcement_id) {
    return $this->db->get_where("announcement", array("announcement_id" => $announcement_id))->row_array();
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
		if (isset($para['orderby'])) {
      $this->db->set("orderby", trim($para["orderby"]));
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
		if (isset($para['orderby'])) {
      $this->db->set("orderby", trim($para["orderby"]));
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
      $this->db->like("title", trim($para["title"]));
    }
    if (isset($para['desc'])) {
      $this->db->like("desc", trim($para["desc"]));
    }
    if (isset($para['status'])) {
      $this->db->where("status", intval($para["status"]));
    } else {
      $this->db->where("status", 1);
    }
    if (!empty($para['search'])) {
      $this->db->group_start();
      $this->db->like("title", trim($para["search"]));
      $this->db->or_like("desc", trim($para["search"]));
      $this->db->group_end();
    }
    if (empty($para['all'])) {
      $this->db->where("start_tm<=", date("Y-m-d H:i:s"));
    }
    if ($limit > 0) {
      if ($start > 0) {
        $this->db->limit($limit, $start);
      } else {
        $this->db->limit($limit);
      }
    }
    $this->db->order_by("orderby", "DESC");
    $this->db->order_by("announcement_id", "DESC");
    return $this->db->get('announcement')->result_array();
  }
	
  public function search_total($para) {
    if (isset($para['announcement_id'])) {
      $this->db->where("announcement_id", trim($para["announcement_id"]));
    }
    if (isset($para['title'])) {
      $this->db->like("title", trim($para["title"]));
    }
    if (isset($para['desc'])) {
      $this->db->like("desc", trim($para["desc"]));
    }
    if (isset($para['status'])) {
      $this->db->where("status", intval($para["status"]));
    } else {
      $this->db->where("status", 1);
    }
    if (!empty($para['search'])) {
      $this->db->group_start();
      $this->db->like("title", trim($para["search"]));
      $this->db->or_like("desc", trim($para["search"]));
      $this->db->group_end();
    }
    if (empty($para['all'])) {
      $this->db->where("start_tm<=", date("Y-m-d H:i:s"));
    }
    return $this->db->get('announcement')->num_rows();
  }
}
