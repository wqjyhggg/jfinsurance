<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Snappay_model extends CI_Model {
  public $url = "https://open.snappay.ca/api/gateway";
  public $MerchantId = "901800000116";
  public $AppId = "9f00cd9a873c511e";
  public $key = "7e2083699dd510575faa1c72f9e35d43";
  public $last_err = "";

	private function get_trans_id($plan_id) {
    $this->db->set("plan_id", $plan_id);
    if ($this->db->insert('snappay_trans')) {
      $id = $this->db->insert_id();
      return $id;
    }
    return 0;
	}

  private function create_linkstring($array)    {
    $arg  = "";
    foreach($array as $key=>$val)  {
      if ($val!=''){
          $arg.=$key."=".$val."&";
      }
    }
    $arg = substr($arg, 0, strlen($arg)-1);		     //去掉最后一个&字符
    return $arg;
  }

  private function SendJsonRequest($params) {
    $data_string = json_encode($params); 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data_string))                                                                       
    );
     
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//如果不加验证,就设false,商户自行处理
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
     
    $output = curl_exec($ch);
    curl_close($ch);
    return  $output;
  }

  public function get_pay_url($plan, $amount, $sekey="", $returnurl="") {
    $this->last_err = "";
    $uid = $this->get_trans_id($plan["plan_id"]);
    if (!$uid) {
      $this->last_err = "Create uid error";
      return "";
    }
    $postArr = array();
    $postArr['app_id'] = $this->AppId;
    $postArr['format'] = "JSON";
    $postArr['charset'] = "UTF-8";
    $postArr['description'] = "Pay for " . $plan["policy"];
    $postArr['effective_minutes'] = 5;
    $postArr['merchant_no'] = $this->MerchantId;
    $postArr['out_order_no'] = $plan["plan_id"] . "-" . $uid;
    $postArr['trans_currency'] = "CAD";
    $postArr['trans_amount'] = $amount;
    $postArr['notify_url'] = base_url('snappay/' . $plan["plan_id"]);
    $postArr['browser_type'] = "PC";
    if ($returnurl) {
      $postArr['browser_type'] = "WAP";
      $postArr['return_url'] = $returnurl;
    } else if (empty($sekey)) {
      $postArr['return_url'] = base_url('plan/snappay/' . $plan["plan_id"]);
    } else {
      $postArr['return_url'] = base_url('plan/snappay/' . $plan["plan_id"] . "/" . $sekey);
    }
    $postArr['timestamp'] = gmdate('Y-m-d H:i:s');
    $postArr['version'] = "1.0";
    $postArr['method'] = "pay.webpay";
    $postArr['payment_method'] = "ALIPAY";

    ksort($postArr);
    reset($postArr);
    $prestr = $this->create_linkstring($postArr);
    $postArr['sign'] = md5($prestr . $this->key); //md5密钥
    $postArr['sign_type'] = "MD5";

    $resBody = $this->SendJsonRequest($postArr); //提交到网关		
    $this->db->set("send", json_encode($postArr));
    $this->db->set("recv", $resBody);
    $this->db->where("trans_id", $uid);
    $this->db->update("snappay_trans");

    $res = json_decode($resBody, true);
    if (isset($res['code'])) {
      if ($res['code'] === "0") {
        // $pay_url = $res['data'][0]['h5pay_url'];
        $pay_url = $res['data'][0]['webpay_url'];
        $this->db->set("status", 1);
        $this->db->where("trans_id", $uid);
        $this->db->update("snappay_trans");
        return $pay_url;
      }
    }
    $this->last_err = "Get Paymentu URL error";
    return "";
  }

  public function record_postback($plan_id, $recv, $plan) {
    $this->db->set("plan_id", $plan_id);
    $this->db->set("recv", $recv);
    $postback_id = 0;
    if ($this->db->insert('snappay_postback')) {
      $postback_id = $this->db->insert_id();
    }
    $req = json_decode($recv, true);

    if (!is_array($req) || empty($req['sign']) || empty($req['sign_type']) || ($req['sign_type'] != 'MD5')) {
      log_message('debug', "snappay: req not array or parameter error");
      die('{"code":"1","message":"no sign or sign_type!"}');
    }
    $trans_no = $req['trans_no']; //开放服务网关平台交易号
    // $charset = $req['charset']; //UTF-8
    $merchant_no = $req['merchant_no']; //商户号，在SnapPay入驻后生成的定常数字，用于标识商户身份
    // $trans_end_time = $req['trans_end_time']; //交易成功时间
    // $exchange_rate = $req['exchange_rate']; //标价币种兑换支付币种汇率，非RMB标价时有值
    // $pay_user_account_id = $req['pay_user_account_id']; //支付宝返回支付用户的ID, 微信返回商户appid下用户唯一标识, 银行卡交易返回加脱敏的卡号信息
    // $format = $req['format']; //JSON
    $sign = $req['sign']; //JSON
    $out_order_no = $req['out_order_no']; //商户系统内部订单号，同一商户下订单号不能重复
    // $pay_user_account_name = $req['pay_user_account_name']; //支付宝支付返回账号登录名
    $trans_amount = $req['trans_amount']; //交易总金额 12.34
    // $version = $req['version']; //1.0
    // $customer_paid_amount = $req['customer_paid_amount']; //交易过程中从顾客资金账户中实际扣减的金额
    // $discount_bpc = $req['discount_bpc']; //0.0000
    // $trans_currency = $req['trans_currency']; //符合ISO 4217标准的三位字母代码，币种列表详见货币类型
    // $app_id = $req['app_id']; //
    // $sign_type = $req['sign_type']; //MD5
    $trans_status = $req['trans_status']; //SUCCESS - 交易成功
    $payment_method = $req['payment_method']; //ALIPAY 支付宝, WECHATPAY 微信支付, UNIONPAY 银联
    // $c_trans_fee = $req['c_trans_fee']; //
    // $timestamp = $req['timestamp']; //
    // $discount_bmopc = $req['discount_bmopc']; //附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
    if (!empty($req['attach'])) {
      $attach = json_decode($req['attach'], true); //附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
      if (!$attach) {
        $attach = $req['attach'];
      }
    }

    if ($pos = strpos($out_order_no, "-")) {
      $out_order_no = substr($out_order_no, 0, $pos);
    }
    if ($out_order_no != $plan_id) {
      log_message('debug', "snappay: out_order_no parameter error: [".$req['out_order_no']."]");
      die('{"code":"1","message":"out_order_no wrong, Something wrong."}');
    }

    unset($req['sign']);
    unset($req['sign_type']);

    ksort($req);
    reset($req);
    $prestr = $this->create_linkstring($req);
    $postArrsign = md5($prestr . $this->key); //md5密钥
    if ($postArrsign != $sign) {
      log_message('debug', "snappay: sign not match! [".$postArrsign."][".$sign."]");
      die('{"code":"1","message":"sign not match!"}');
    }

    if ($this->MerchantId != $merchant_no) {
      log_message('debug', "snappay: MerchantId not match! [".$this->MerchantId."][".$merchant_no."]");
      die('{"code":"1","message":"Wrong MerchantId, something wrong"}');
    }

    if (($plan["status_id"] == 2) || ($plan["status_id"] == 3)) { // Paied already
      log_message('debug', "snappay: Plan paid, donothing ");
      exit;
    }

    $trans = [
      'type' => "snappay-ali",
      'amount' => $trans_amount
    ];

    $this->db->set("status", 1);
    $this->db->where("postback_id", $postback_id);
    $this->db->update("snappay_postback");
    return $trans;
  }
}
