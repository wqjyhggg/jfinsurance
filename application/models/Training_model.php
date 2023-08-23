<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  CREATE TABLE `training` ( 
    `training_id` INT NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(64) NOT NULL ,
    `desc` TEXT,
    `links` TEXT,
    `start_tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `status` tinyint NOT NULL DEFAULT 1,
    `orderby` tinyint NOT NULL DEFAULT 0,
    `update_tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY  (`training_id`)) ENGINE = MyISAM;
    ALTER TABLE training  MODIFY COLUMN title VARCHAR(64) CHARACTER  SET UTF8 COLLATE UTF8_GENERAL_CI. 
    ALTER TABLE training  MODIFY COLUMN desc TEXT CHARACTER  SET UTF8 COLLATE UTF8_GENERAL_CI. 
*/
class Training_model extends CI_Model {
  public function get_by_id($training_id) {
    return $this->db->get_where("training", array("training_id" => $training_id))->row_array();
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
		if (isset($para['status'])) {
      $this->db->set("status", trim($para["status"]));
    }
		if (isset($para['orderby'])) {
      $this->db->set("orderby", trim($para["orderby"]));
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
      $this->db->like("title", trim($para["title"]));
    }
    if (isset($para['desc'])) {
      $this->db->like("desc", trim($para["desc"]));
    }
    if (isset($para['status'])) {
      $this->db->where("status", trim($para["status"]));
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
    $this->db->order_by("training_id", "DESC");
    return $this->db->get('training')->result_array();
  }
	
  public function search_total($para) {
    if (isset($para['training_id'])) {
      $this->db->where("training_id", trim($para["training_id"]));
    }
    if (isset($para['title'])) {
      $this->db->like("title", trim($para["title"]));
    }
    if (isset($para['desc'])) {
      $this->db->like("desc", trim($para["desc"]));
    }
    if (isset($para['status'])) {
      $this->db->where("status", trim($para["status"]));
    }
    if (empty($para['all'])) {
      $this->db->where("start_tm<=", date("Y-m-d H:i:s"));
    }
    return $this->db->get('training')->num_rows();
  }
}
