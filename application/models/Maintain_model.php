<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
CREATE TABLE maintain (
  maintain_id int NOT NULL AUTO_INCREMENT,
	status tinyint NOT NULL DEFAULT 0 COMMENT '0: pause, 1: send notification, 2: notified, 3: in maitain (combain with start_time and end_time)',
  start_time datetime NULL,
  end_time datetime NULL,
  reason text,
  notes varchar(255) NULL COMMENT 'For human read as notes',
 PRIMARY KEY (maintain_id) );
*/
class Maintain_model extends CI_Model {
  public function get_first($maintain_id) {
    return $this->db->get("maintain")->row_array();
  }
  // public function get_by_id($maintain_id) {
  //   return $this->db->get_where("maintain", array("maintain_id" => $maintain_id))->row_array();
  // }

	public function get_available() {
		$now = date("Y-m-d H:i:s");
    return $this->db->get_where("maintain",["status"=>3,"start_time>="=>$now,"end_time<="=>$now])->row_array();
  }

	public function add($para) {
		if (isset($para['status'])) {
      $this->db->set("status", trim($para["status"]));
    }
		if (isset($para['start_time'])) {
      $this->db->set("start_time", trim($para["start_time"]));
    }
		if (isset($para['end_time'])) {
      $this->db->set("end_time", trim($para["end_time"]));
    }
		if (isset($para['reason'])) {
      $this->db->set("reason", trim($para["reason"]));
    }
		if (isset($para['notes'])) {
      $this->db->set("notes", trim($para["notes"]));
    }
    if ($this->db->insert('maintain')) {
      $id = $this->db->insert_id();
      return $id;
    }
    return 0;
	}
	
	public function update($id, $para) {
		if (isset($para['status'])) {
      $this->db->set("status", trim($para["status"]));
    }
		if (isset($para['start_time'])) {
      $this->db->set("start_time", trim($para["start_time"]));
    }
		if (isset($para['end_time'])) {
      $this->db->set("end_time", trim($para["end_time"]));
    }
		if (isset($para['reason'])) {
      $this->db->set("reason", trim($para["reason"]));
    }
		if (isset($para['notes'])) {
      $this->db->set("notes", trim($para["notes"]));
    }
    $this->db->where("maintain_id", $id);
    if ($this->db->update('maintain')) {
      return $id;
    }
    return 0;
	}
	
  public function search($para, $limit=0, $start=-1) {
    if (isset($para['maintain_id'])) {
      $this->db->where("maintain_id", trim($para["maintain_id"]));
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
    $this->db->order_by("maintain_id", "DESC");
    return $this->db->get('maintain')->result_array();
  }
	
  public function search_total($para) {
    if (isset($para['maintain_id'])) {
      $this->db->where("maintain_id", trim($para["maintain_id"]));
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
    return $this->db->get('maintain')->num_rows();
  }
}
