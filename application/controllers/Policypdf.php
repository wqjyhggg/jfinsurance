<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Policypdf extends CI_Controller {
  const DATE_LINE1 = "2021-08-16";

  public $tlist = array("p" => "Policy", "c" => "claimform");
  public $slist = array("JES", "JESP", "JFE", "JFGD", "JFOS", "JFP", "JFPL", "JFS", "JFSL", "JFR", "JFVTC", "TOP", "TOPN");
  public $clist = array("JES", "JESP");
  public $plist = array(
    "JES"   => array("JES.zip",   "JES2.zip"),
    "JESP"  => array("JESP.zip",  "JESP2.zip"),
    "JFE"   => array("JFE.zip",   "JFE.zip"),
    "JFGD"  => array("JFGD.zip",  "JFGD.zip"),
    "JFOS"  => array("JFOS.zip",  "JFOS.zip"),
    "JFP"   => array("JFP.zip",   "JFP.zip"),
    "JFPL"  => array("JFPL.zip",  "JFPL.zip"),
    "JFR"   => array("JFR.zip",   "JFR.zip"),
    "JFVTC" => array("JFVTC.zip", "JFVTC.zip"),
    "TOP"   => array("TOP.zip",   "TOP.zip"),
    "TOPN"   => array("TOPN.zip",   "TOPN.zip"),
  );
	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
    $policy = $this->input->get_post("p");
    $t = $this->input->get_post("t");
    $short = preg_replace('/[0-9]+/', '', $policy);
    if (in_array($short, $this->slist) && array_key_exists($t, $this->tlist)) {
      $this->load->database();
      $this->load->model("plan_model");
      if ($plan = $this->plan_model->get_plan_by_policy($policy)) {
        //Clear the cache
        clearstatcache();
        $btw = "";
        if (($t == "p") && in_array($short, $this->clist)) {
          if ($plan["effective_date"] >= SELF::DATE_LINE1) {
            $btw = "_new";
          }
        }
        $fname = DOWNLOADDIR . "files/" . $short. $btw."_" . $this->tlist[$t] . ".pdf";
        if (($plan['product_short'] == 'JFPL') && ($plan['effective_date'] < '2023-01-01')) {
          $fname = DOWNLOADDIR . "files/" . $short. $btw."_" . $this->tlist[$t] . "_old.pdf";
        } else if (($plan['product_short'] == 'JFVTC') && ($plan['effective_date'] < '2023-05-01')) {
          $fname = DOWNLOADDIR . "files/" . $short. $btw."_" . $this->tlist[$t] . "_old.pdf";
        }
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
