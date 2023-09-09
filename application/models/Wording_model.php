<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  CREATE TABLE `wording` ( 
    `word` VARCHAR(64) NOT NULL ,
    `desc` VARCHAR(128) NOT NULL ,
    `update_tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY  (`word`)) ENGINE = MyISAM;
*/
class Wording_model extends CI_Model {
  public function getAll($announcement_id) {
    return $this->db->get("wording")->result_array();
  }
	
	public function add($para) {
    return $this->db->replace('wording',$para);
	}
}
