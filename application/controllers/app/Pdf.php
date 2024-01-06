<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Pdf extends CI_Controller
{
  public $uploadtype = array('pdf', 'doc', 'docx', 'xls', 'xlsx');
  public $error;

  public function index()
  {
    $this->load->model("app_model");
    return $this->app_model->return_error("Unknow function");
  }

  public function pdflist()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    } else if ($user["user_group_id"] != 1) {
      return $this->app_model->return_error("You can't access this function");
    }
    $filelist = array();
    if ($handle = opendir(DOWNLOADDIR)) {
      while (($file = readdir($handle)) !== false) {
        if (substr($file, 0, 1) == '.') {
          continue;
        }
        if (filetype($file) != "file") {
          continue;
        }
        $filelist[] = $file;
      }
      asort($filelist);
      $data['filelist'] = array_values($filelist);
    }
    $this->app_model->return_ok($data);
  }

  public function upload()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    } else if ($user["user_group_id"] != 1) {
      return $this->app_model->return_error("You can't access this function");
    }

    if ($this->input->post()) {
      foreach ($_FILES as $uf) {
        $name = $uf['name'];
        $type = $uf['type'];
        $tmp_name = $uf['tmp_name'];
        $size = $uf['size'];
        if (!empty($uf['error'])) {
          $data['errormsg'] .= sprintf($this->lang->line('error_file_upload'), $name) . "<br />";
          continue;
        }
        $fileinfo = pathinfo($name);
        $filename = $this->input->post('lcfilename');
        if (!in_array($fileinfo['extension'], $this->uploadtype)) {
          $data['errormsg'] .= sprintf($this->lang->line('error_file_type'), $name) . "<br />";
        } else {
          $filename = DOWNLOADDIR . $filename;
          move_uploaded_file($tmp_name, $filename);
        }
        break; // Only need one file
      }
      $this->app_model->return_ok(array("Filename" => $filename));
    }
    return $this->app_model->return_error("Missing upload file data");
  }
}
