<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class App_model extends CI_Model
{
  public $error;
  const PROCESSING = 0;
  const FINISHED = 1;
  const ERROR = 2;
  public function __construct() {
    parent::__construct();
    $this->load->database();
    // Set db time zone
    $this->db->query("SET time_zone = 'America/Toronto'");
  }
  
  public function return_ok($data) {
    $dt = array("status" => 0, "data" => $data);
    $this->return_data($dt);
  }

  public function return_error($message) {
    $dt = array("status" => 1, "message" => $message, "data" => []);
    $this->return_data($dt);
  }

  private function return_data($data) {
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header('Cache-Control: no-store, no-cache, must-revalidate');
    echo json_encode($data);
  }

  function unixstamp($excelDateTime)
  {
    if (!is_string($excelDateTime)) {
      if (get_class($excelDateTime) == 'DateTime') {
        return $excelDateTime->getTimestamp();
      } else {
        return 0;
      }
    }
    $excelDateTime = trim($excelDateTime);
    if (is_numeric($excelDateTime)) {
      $t = $excelDateTime - 25569; // seconds since 1900
      if ($t > 0) {
        return round($t * 86400);
      } else {
        return 0;
      }
    } else {
      return strtotime($excelDateTime);
    }
  }

  public function check_token($token) {
    if ($r = $this->db->get_where("app", array("token" => $token))->row_array()) {
      if (($r["timeout"] + (15*60)) > time()) {
        $this->db->set("timeout", time());
        $this->db->where("user_id", $r["user_id"]);
        $this->db->update("app");

        $sql = "SELECT * FROM user WHERE user_id = '" . (int)$user_id . "'";
        return $this->db->query($sql)->row_array();
      }
    }
    return "";
  }

  public function create_token($r) {
    $token = md5(microtime()).$r["user_id"];
    if ($u = $this->db->get_where("app", array("user_id" => $r["user_id"]))->row_array()) {
      $this->db->set("token", $token);
      $this->db->set("timeout", time());
      $this->db->where("user_id", $r["user_id"]);
      $this->db->update("app");
    } else {
      $this->db->insert("app", array("user_id" => $r["user_id"], "timeout" => time(), "token" => $token));
    }
    return $token;
  }
}
