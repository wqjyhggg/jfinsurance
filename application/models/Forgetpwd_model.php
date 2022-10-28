<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Forgetpwd_model extends CI_Model {
  const FORGETKEY = "jfresetpwd";
  const FORGETDAYS = 3;

  public $mailtitle = [
    "Reset Your password",
    "Change your password",
    "Did you forgot how to login",
    "Reset link",
    "Your requested link",
  ];

  public $mailbody = [
    "If you didn't forget your passwrod, just ignore this email. Please follow the link to reset your password: __URLLING__",
    "If you have your passwrod already, just ignore this link. Please follow the link to reset your password: __URLLING__",
    "You still can use you old password to login if you remember. If you want to reset it just following the URL link: __URLLING__",
  ];

  public function email_body($key) {
    $url = base_url("/user/setpassword")."?key=".$key;
    $tidx = array_rand($this->mailtitle);
    $bidx = array_rand($this->mailbody);
    $rc = array(
      "title" => $this->mailtitle[$tidx],
      "body" => str_replace("__URLLING__", $url, $this->mailbody[$bidx]),
    );

    return $rc;
	}
	
  public function clear_old_data() {
    $day3 = time() - (3 * 86400);
    $this->db->where("add_time<", date("Y-m-d H:i:s", $day3));
    $this->db->delete("forgetpwd");
	}
	
  public function remove_me($key) {
    $this->db->where("forgetid", $key);
    $this->db->delete("forgetpwd");
  }

  public function get_key($user_id) {
    $part1 = md5($user_id.SELF::FORGETKEY);
    $part2 = md5($part1.SELF::FORGETKEY);
		$this->db->set("forgetid", $part1.$part2);
    $this->db->set("user_id", $user_id);
		if ($this->db->insert("forgetpwd")) {
      return $part1.$part2;
    }
		return NULL;
	}
	
	public function get_by_key($key) {
		$this->db->where('forgetid', trim($key));
		return $this->db->get('forgetpwd')->row_array();
	}

  public function verifykey($key) {
    if (strlen($key) != 64) {
      return false;
    }
    $part1 = substr($key, 0, 32);
    $part2 = substr($key, 32);
    if ($part2 != md5($part1.SELF::FORGETKEY)) {
      return false;
    }

    if ($rc = $this->get_by_key($key)) {
      $user_id = $rc["user_id"];
      if ($part1 != md5($user_id.SELF::FORGETKEY)) {
        return false;
      }
      return $user_id;
    }
    return false;
  }
}
