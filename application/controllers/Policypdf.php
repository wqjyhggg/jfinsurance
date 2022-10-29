<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Policypdf extends CI_Controller {
  const DATE_LINE1 = "2021-08-16";

  public $plist = array(
    "JES"   => array("JES.zip",   "JES2.zip"),
    "JESP"  => array("JESP.zip",  "JESP2.zip"),
    "JFE"   => array("JFE.zip",   "JFE.zip"),
    "JFGD"  => array("JFGD.zip",  "JFGD.zip"),
    "JFP"   => array("JFP.zip",   "JFP.zip"),
    "JFPL"  => array("JFPL.zip",  "JFPL.zip"),
    "JFR"   => array("JFR.zip",   "JFR.zip"),
    "JFVTC" => array("JFVTC.zip", "JFVTC.zip"),
    "TOP"   => array("TOP.zip",   "TOP.zip"),
  );
	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
    $policy = $this->input->get_post("p");
    $short = preg_replace('/[0-9]+/', '', $policy);
    if (array_key_exists($short, $this->plist)) {
      $this->load->database();
      $this->load->model("plan_model");
      if ($plan = $this->plan_model->get_plan_by_policy($policy)) {
        //Clear the cache
        clearstatcache();
        $idx = 0;
        if ($plan["effective_date"] >= SELF::DATE_LINE1) {
          $idx = 1;
        }
        $fname = DOWNLOADDIR . "files/" . $this->plist[$short][$idx];
				if (file_exists($fname)) {
          //Define header information
          header('Content-Type: application/pdf');
          header('Content-Disposition: attachment; filename="'.basename($fname).'"');
          header('Content-Length: ' . filesize($fname));
          header('Pragma: public');
    
          //Clear system output buffer
          flush();
    
          //Read the size of the file
          readfile($fname,true);
    
          //Terminate from the script
          die();
        }
      }
    }
    echo "File does not exist.";
	}
}
