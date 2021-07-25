<?php

namespace App\Http\Controllers\Frontend\Ksher;

use App\Http\Controllers\Controller;

class Ksher_pay_sdk extends Controller
{
    public $time;
    public $appid; //ksher appid
    public $privatekey;// 私钥
    public $pubkey;//ksher公钥
    public $version;//SDK版本
    public $pay_domain;
    public $gateway_domain;

    public  function __construct()
    {
      $this->time = date("YmdHis", time());
      $this->version = '3.0.0';
      $this->pay_domain = 'https://api.mch.ksher.net/KsherPay';
      $this->gateway_domain = 'https://gateway.ksher.com/api';
      $this->pubkey = <<<EOD
      -----BEGIN PUBLIC KEY-----
      MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAL7955OCuN4I8eYNL/mixZWIXIgCvIVE
      ivlxqdpiHPcOLdQ2RPSx/pORpsUu/E9wz0mYS2PY7hNc2mBgBOQT+wUCAwEAAQ==
      -----END PUBLIC KEY-----
      EOD;
      $this->appid = 'mch37088';
      $this->privatekey = <<<EOD
       -----BEGIN RSA PRIVATE KEY-----
       MIICYAIBAAKBgQCXlfMFlm4/PJBxtjDzpZupETZ7zQfY3ZJT+3AxJDbi4b+eN66B
       X5tUUCqGvVSrOoL4opJzuJVznlFA5d4/kFzYyl6LQb22pP1fHrbL7bftHmuJxqlJ
       m194uN57ALS9BRBrH7Hx2WfLQAeb8H/ykomKA2W7vRep/STFvyLjOW4oKQIDAQAB
       AoGAVzOewxcfwucPXSrnDDK30lGhmyR+oCYOmJtrlgl0PZ6KQfVcQPaD/8PCQxLF
       k18smuXDBCkqaIGp0oCprjFileWYrOfDPL0/kS0oNEuUwjYcFuYqyW/2BlxObF+6
       zujRdxMfFSBsvljnhk33ah/bXschaDx8YpdJvSomT6JoKBUCRQCuyARqTEifHmfI
       HpN0j+xPvXKVUexM/xxRsuNuvuAN+7UZb08cErpSyTxxGyJLUg1SIhknakVrPf1P
       enutsKCt+giJSwI9AN4GliNO4Q1w3uHfqo6k/PAaLG5/Oosrg0MvLNRNSbm9a0S8
       VzLu3qGdYbpY5fM5fCiV34dgkF6MBb3/2wJEeVCqB+I1EgT3wia+8MwpGVwE2XII
       k5ULYgXJ6Qeh2vLYS/Q/s9un6mh1hIhx8FfemSDD1uDjmEFpvq3khLWxgbCoFisC
       PQDLBE7I3lmfRrQm6bQ1VtwKWISETUYk95bBGiPtxPZDJtctNOKvKgjc0uIH2T36
       13eWTOTmDwAz0+l0QL0CRCsQQs3N5lHVQqjgXet6E87lxgaiItfJt7xiHl0o6xhr
       cNMDwPzQ7LKOeRY033dfVYHjFo8749MnLpU9bH71jLlJYHSb
       -----END RSA PRIVATE KEY-----
       EOD;
    }

    // public static function gateway_pay($data)
    // {
    //     $self = new static;
    //     $data['appid'] = $self->appid;
    //     $data['nonce_str'] = $self->generate_nonce_str();
    //     $data['time_stamp'] = $self->time;
    //     $response = $self->_request($self->gateway_domain . '/gateway_pay', $data);
    //     return $response;
    // }

    public static function generate_nonce_str($len=16) {

      $nonce_str = "";
      $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

      for ( $i = 0; $i < $len; $i++ ) {
          $nonce_str .= $chars[ mt_rand(0, strlen($chars) - 1) ];
      }
      return $nonce_str;
  }
  /**
   * 生成sign
   * @param $data
   * @param $private_key_content
   */
  public static function ksher_sign($data){
      $self = new static;
      $message = self::paramData( $data );
      $private_key = openssl_get_privatekey($self->privatekey);
      openssl_sign($message, $encoded_sign, $private_key,OPENSSL_ALGO_MD5);
      openssl_free_key($private_key);
      $encoded_sign = bin2hex($encoded_sign);

      return $encoded_sign;
  }
  /**
   * 验证签名
   */
  public static function verify_ksher_sign( $data, $sign){
      $self = new static;
      $sign = pack("H*",$sign);
      $message = self::paramData( $data );
      $res = openssl_get_publickey($self->pubkey);
      $result = openssl_verify($message, $sign, $res,OPENSSL_ALGO_MD5);
      openssl_free_key($res);
      return $result;
  }
  /**
   * 处理待加密的数据
   */
  private static function paramData($data){
      ksort($data);
      $message = '';
      foreach ($data as $key => $value) {
          $message .= $key . "=" . $value;
      }
      $message = mb_convert_encoding($message, "UTF-8");
      return $message;
  }
  /**
   * @access get方式请求数据
   * @params url //请求地址
   * @params data //请求的数据，数组格式
   * */
  public static function _request($url, $data=array()){
    $self = new static;
      try {
          if(!empty($data) && is_array($data)){
              $params = '';
              $data['sign'] = $self->ksher_sign($data);
              foreach($data as $temp_key =>$temp_value){
                  $params .= ($temp_key."=".urlencode($temp_value)."&");
              }
              if(strpos($url, '?') === false){
                  $url .= "?";
              }
              $url .= "&".$params;
          }
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
          curl_setopt($ch, CURLOPT_HEADER, FALSE);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
          $output = curl_exec($ch);

          if($output !== false){
              $response_array = json_decode($output, true);
              if($response_array['code'] == 0){
                  if(!$self->verify_ksher_sign($response_array['data'], $response_array['sign'])){
                      $temp = array(
                          "code"=> 0,
                          "data"=> array(
                                  "err_code"=> "VERIFY_KSHER_SIGN_FAIL",
                                  "err_msg"=> "verify signature failed",
                                  "result"=> "FAIL"),
                          "msg"=> "ok",
                          "sign"=> "",
                          "status_code"=> "",
                          "status_msg"=> "",
                          "time_stamp"=> $self->time,
                          "version"=> $self->version
                      );
                      return json_encode($temp);
                  }
              }
          }
          curl_close($ch);
          return $output;
      } catch (Exception $e) {
          echo 'curl error';
          return false;
      }
  }
  /**
   * B扫C支付
   * 必传参数
   *    mch_order_no
   *    total_fee
   *    fee_type
   *    auth_code
   *    channel
   * 选传参数
   *    operator_id
   */
  public static function quick_pay($data){
      $self = new static;
      $data['appid'] = $self->appid;
      $data['nonce_str'] = $self->generate_nonce_str();
      $data['time_stamp'] = $self->time;
      $response = $self->_request($self->pay_domain.'/quick_pay', $data);
      return $response;
  }

  /**
   * C扫B支付
   * 必传参数
   *     mch_order_no
   *     total_fee
   *     fee_type
   *     channel
   *     notify_url
   * 选传参数
   *     redirect_url
   *     paypage_title
   *     operator_id
   */
  public static function jsapi_pay($data){
      $self = new static;
      $data['appid'] = $self->appid;
      $data['nonce_str'] = $self->generate_nonce_str();
      $data['time_stamp'] = $self->time;
      $response = $self->_request($self->pay_domain.'/jsapi_pay', $data);
      return $response;
  }

  /**
   * 动态码支付
   * 必传参数
   *     mch_order_no
   *     total_fee
   *     fee_type
   *     channel
   *     notify_url
   * 选传参数
   *     redirect_url
   *     paypage_title
   *     product
   *     attach
   *     operator_id
   *     device_id
   *     img_type
   */
  public static function native_pay($data){
      $self = new static;
      $data['appid'] = $self->appid;
      $data['nonce_str'] = $self->generate_nonce_str();
      $data['time_stamp'] = $self->time;
      $response = $self->_request($self->pay_domain.'/native_pay', $data);
      return $response;
  }

  /**
   * 小程序支付
   * 必传参数
   *      mch_order_no
   *      local_total_fee
   *      fee_type
   *      channel
   *      sub_openid
   *      channel_sub_appid
   * 选传参数
   *     redirect_url
   *     notify_url
   *     paypage_title
   *     product
   *     operator_id
   */

  public static function minipro_pay($data){
      $self = new static;
      $data['appid'] = $self->appid;
      $data['nonce_str'] = $self->generate_nonce_str();
      $data['time_stamp'] = $self->time;
      $response = $self->_request($self->pay_domain.'/mini_program_pay', $data);
      return $response;
  }
  /**
   * app支付
   * 必传参数
   *     mch_order_no
   *     total_fee
   *     fee_type
   *     channel
   *     sub_openid
   *     channel_sub_appid
   * 选传参数
   *     redirect_url
   *     notify_url
   *     paypage_title
   *     product
   *     attach
   *     operator_id
   *     refer_url 仅当channel为alipay时需要
   */
  public static function app_pay($data){
      $self = new static;
      $data['appid'] = $self->appid;
      $data['nonce_str'] = $self->generate_nonce_str();
      $data['time_stamp'] = $self->time;
      $response = $self->_request($self->pay_domain.'/app_pay', $data);
      return $response;
  }
  /**
   * H5支付，仅支持channel=alipay
   *
   * 必传参数
   *     mch_order_no
   *     local_total_fee
   *     fee_type
   *     channel
   *     refer_url
   * 选传参数
   *     redirect_url
   *     notify_url
   *     paypage_title
   *     product
   *     attach
   *     operator_id
   *     device_id
   */
  public static function wap_pay($data){
      $self = new static;
      $data['appid'] = $self->appid;
      $data['nonce_str'] = $self->generate_nonce_str();
      $data['time_stamp'] = $self->time;
      $response = $self->_request($self->pay_domain.'/wap_pay', $data);
      return $response;
  }
  /**
   * PC网站支付，仅支持channel=alipay
   * 必传参数
   *     mch_order_no
   *     local_total_fee
   *     fee_type
   *     channel
   *     refer_url
   * 选传参数
   *     redirect_url
   *     notify_url
   *     paypage_title
   *     product
   *     attach
   *     operator_id
   *     device_id
   */
  public static function web_pay($data){
      $self = new static;
      $data['appid'] = $self->appid;
      $data['nonce_str'] = $self->generate_nonce_str();
      $data['time_stamp'] = $self->time;
      $response = $self->_request($self->pay_domain.'/web_pay', $data);
      return $response;
  }
  /**
   * 订单查询
   * 必传参数
   *     mch_order_no、ksher_order_no、channel_order_no三选一
   */
  public static function order_query($data){
      $self = new static;
      $data['appid'] = $self->appid;
      $data['nonce_str'] = $self->generate_nonce_str();
      $data['time_stamp'] = $self->time;
      $response = $self->_request($self->pay_domain.'/order_query', $data);
      return $response;
  }
  /**
   * 订单关闭
   * 必传参数
   *     mch_order_no、ksher_order_no、channel_order_no三选一
   * 选传参数
   *     operator_id
   */
  public static function order_close($data){
      $self = new static;
      $data['appid'] = $self->appid;
      $data['nonce_str'] = $self->generate_nonce_str();
      $data['time_stamp'] = $self->time;
      $response = $self->_request($self->pay_domain.'/order_close', $data);
      return $response;
  }
  /**
   * 订单撤销
   * 必传参数
   *     mch_order_no、ksher_order_no、channel_order_no三选一
   * 选传参数
   *     operator_id
   */
  public static function order_reverse($data){
      $self = new static;
      $data['appid'] = $self->appid;
      $data['nonce_str'] = $self->generate_nonce_str();
      $data['time_stamp'] = $self->time;
      $response = $self->_request($self->pay_domain.'/order_reverse', $data);
      return $response;
  }
  /**
   * 订单退款
   * 必传参数
   *     total_fee
   *     fee_type
   *     refund_fee
   *     mch_refund_no
   *     mch_order_no、ksher_order_no、channel_order_no三选一
   * 选传参数
   *     operator_id
   */
  public static function order_refund($data){
      $self = new static;
      $data['appid'] = $self->appid;
      $data['nonce_str'] = $self->generate_nonce_str();
      $data['time_stamp'] = $self->time;
      $data['version'] = $self->version;
      $response = $self->_request($self->pay_domain.'/order_refund', $data);
      return $response;
  }
  /**
   * 退款查询
   * 必传参数
   *     mch_refund_no、ksher_refund_no、channel_refund_no三选一
   *     mch_order_no、ksher_order_no、channel_order_no三选一
   */
  public static function refund_query($data){
      $self = new static;
      $data['appid'] = $self->appid;
      $data['nonce_str'] = $self->generate_nonce_str();
      $data['time_stamp'] = $self->time;
      $response = $self->_request($self->pay_domain.'/refund_query', $data);
      return $response;
  }
  /**
   * 汇率查询
   * 必传参数
   *     channel
   *     fee_type
   *     date
   */
  public static function rate_query($data){
      $self = new static;
      $data['appid'] = $self->appid;
      $data['nonce_str'] = $self->generate_nonce_str();
      $data['time_stamp'] = $self->time;
      $response = $self->_request($self->pay_domain.'/rate_query', $data);
      return $response;
  }
  /**
   *聚合支付商户查询订单支付状态
   * 必传参数
   * mch_order_no
   */
  public static function gateway_order_query($data){
      $self = new static;
      $data['appid'] = $self->appid;
      $data['nonce_str'] = $self->generate_nonce_str();
      $data['time_stamp'] = $self->time;
      $response = $self->_request($self->gateway_domain.'/gateway_order_query', $data);
      return $response;
  }

}
