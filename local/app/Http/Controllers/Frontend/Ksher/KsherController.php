<?php

namespace App\Http\Controllers\Frontend\Ksher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class KsherController extends Controller
{
    private  $time;
    private  $appid;
    private  $version; //SDK版本
    private  $pay_domain;
    private  $gateway_domain;
    private  $pubkey;
    private  $user_agent;

     public function __construct()
     {
         $this->middleware('customer');
         // $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
         $this->appid = 'mch37088';
         $this->privatekey =<<<EOD
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
         $this->time = date("YmdHis");
         $this->version = '3.0.0';
         $this->pay_domain = 'https://api.mch.ksher.net/KsherPay';
         $this->gateway_domain = 'https://gateway.ksher.com/api';
         $this->pubkey = <<<EOD
         -----BEGIN PUBLIC KEY-----
         MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAL7955OCuN4I8eYNL/mixZWIXIgCvIVE
         ivlxqdpiHPcOLdQ2RPSx/pORpsUu/E9wz0mYS2PY7hNc2mBgBOQT+wUCAwEAAQ==
         -----END PUBLIC KEY-----
         EOD;
     }

       public function index()
       {
         return view('frontend/ksher');
       }

       public function gateway_ksher(Request $rs)
       {

         if($rs->action == 'promptpay'){

           $gateway_pay_data = array('mch_order_no'=> $rs->mch_order_no,
               "total_fee" => round($rs->total_fee, 2)*100,
               "fee_type" => $rs->fee_type,
               //"channel_list" => 'truemoney',
               "channel_list" => 'promptpay',
               'mch_code' => $rs->mch_order_no,
               'mch_redirect_url' =>  route('payment/success'),
               'mch_redirect_url_fail' => route('payment/fail'),
               'product_name' => 'test_ksher',
               'refer_url' => route('ksher'),
              //  'shop_name' => 'AIYARA PLANET บริษัท ไอยรา แพลนเน็ต',
              //  'device' => 'PC',
              //'attach' =>'หมายเหตุหรือคถอธิบาย',
               'color' => '#18c160',
               'logo' => asset('frontend/assets/images/logo.png'),
             );
         $gateway_pay_response = KsherController::gateway_pay($gateway_pay_data);
         $gateway_pay_array = json_decode($gateway_pay_response, true);

         if (isset($gateway_pay_array['data']['pay_content'])){
          return redirect($gateway_pay_array['data']['pay_content']);
        }else{
          return redirect('ksher')->withError('Payment Fail');

        }
       }else{
         return redirect('ksher')->withError('การชำระเงินไม่ถูกต้อง');
       }
    }

     public function generate_nonce_str($len = 16)
     {
         $nonce_str = "";
         $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

         for ($i = 0; $i < $len; $i++) {
             $nonce_str .= $chars[mt_rand(0, strlen($chars) - 1)];
         }
         return $nonce_str;
     }

     public function ksher_sign($data)
     {
         $message = self::paramData($data);
         $private_key = openssl_get_privatekey($this->privatekey);

         openssl_sign($message,$encoded_sign,$private_key, OPENSSL_ALGO_MD5);

         openssl_free_key($private_key);
         $encoded_sign = bin2hex($encoded_sign);
         return $encoded_sign;
     }

     public function verify_ksher_sign($data, $sign)
     {
         $sign = pack("H*", $sign);
         $message = self::paramData($data);
         $res = openssl_get_publickey($this->pubkey);
         $result = openssl_verify($message, $sign, $res, OPENSSL_ALGO_MD5);
         openssl_free_key($res);
         return $result;
     }

     private static function paramData($data)
     {
         ksort($data);
         $message = '';
         foreach ($data as $key => $value) {
             $message .= $key . "=" . $value;
         }
         $message = mb_convert_encoding($message, "UTF-8");
         return $message;
     }

     public function _request($url, $data = array())
     {
         try {
             if (!empty($data) && is_array($data)) {
                 $params = '';
                 $data['sign'] = $this->ksher_sign($data);
                 foreach ($data as $temp_key => $temp_value) {
                     $params .= ($temp_key . "=" . urlencode($temp_value) . "&");
                 }
                 if (strpos($url, '?') === false) {
                     $url .= "?";
                 }
                 $url .= "&" . $params;
             }
             $ch = curl_init();
             curl_setopt($ch, CURLOPT_URL, $url);
             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
             curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
             curl_setopt($ch, CURLOPT_HEADER, false);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
             $output = curl_exec($ch);

             if ($output !== false) {
                 $response_array = json_decode($output, true);
                 if ($response_array['code'] == 0) {
                     if (!$this->verify_ksher_sign($response_array['data'], $response_array['sign'])) {
                         $temp = array(
                             "code" => 0,
                             "data" => array(
                                 "err_code" => "VERIFY_KSHER_SIGN_FAIL",
                                 "err_msg" => "verify signature failed",
                                 "result" => "FAIL"),
                             "msg" => "ok",
                             "sign" => "",
                             "status_code" => "",
                             "status_msg" => "",
                             "time_stamp" => $this->time,
                             "version" => $this->version,
                         );
                         return json_encode($temp);
                     }
                 }
             }
             curl_close($ch);
             return $output;
         } catch (Exception $e) {
             //echo 'curl error';
             return false;
         }
     }

     public function quick_pay($data)
     {
         $data['appid'] = $this->appid;
         $data['nonce_str'] = $this->generate_nonce_str();
         $data['time_stamp'] = $this->time;
         $response = $this->_request($this->pay_domain . '/quick_pay', $data);
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
     public function jsapi_pay($data)
     {
         $data['appid'] = $this->appid;
         $data['nonce_str'] = $this->generate_nonce_str();
         $data['time_stamp'] = $this->time;
         $response = $this->_request($this->pay_domain . '/jsapi_pay', $data);
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
     public function native_pay($data)
     {
         $data['appid'] = $this->appid;
         $data['nonce_str'] = $this->generate_nonce_str();
         $data['time_stamp'] = $this->time;
         $response = $this->_request($this->pay_domain . '/native_pay', $data);
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

     public function minipro_pay($data)
     {
         $data['appid'] = $this->appid;
         $data['nonce_str'] = $this->generate_nonce_str();
         $data['time_stamp'] = $this->time;
         $response = $this->_request($this->pay_domain . '/mini_program_pay', $data);
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
     public function app_pay($data)
     {
         $data['appid'] = $this->appid;
         $data['nonce_str'] = $this->generate_nonce_str();
         $data['time_stamp'] = $this->time;
         $response = $this->_request($this->pay_domain . '/app_pay', $data);
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
     public function wap_pay($data)
     {
         $data['appid'] = $this->appid;
         $data['nonce_str'] = $this->generate_nonce_str();
         $data['time_stamp'] = $this->time;
         $response = $this->_request($this->pay_domain . '/wap_pay', $data);
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
     public function web_pay($data)
     {
         $data['appid'] = $this->appid;
         $data['nonce_str'] = $this->generate_nonce_str();
         $data['time_stamp'] = $this->time;
         $response = $this->_request($this->pay_domain . '/web_pay', $data);
         return $response;
     }
 /**
  * 订单查询
  * 必传参数
  *     mch_order_no、ksher_order_no、channel_order_no三选一
  */
     public function order_query($data)
     {
         $data['appid'] = $this->appid;
         $data['nonce_str'] = $this->generate_nonce_str();
         $data['time_stamp'] = $this->time;
         $response = $this->_request($this->pay_domain . '/order_query', $data);
         return $response;
     }
 /**
  * 订单关闭
  * 必传参数
  *     mch_order_no、ksher_order_no、channel_order_no三选一
  * 选传参数
  *     operator_id
  */
     public function order_close($data)
     {
         $data['appid'] = $this->appid;
         $data['nonce_str'] = $this->generate_nonce_str();
         $data['time_stamp'] = $this->time;
         $response = $this->_request($this->pay_domain . '/order_close', $data);
         return $response;
     }
 /**
  * 订单撤销
  * 必传参数
  *     mch_order_no、ksher_order_no、channel_order_no三选一
  * 选传参数
  *     operator_id
  */
     public function order_reverse($data)
     {
         $data['appid'] = $this->appid;
         $data['nonce_str'] = $this->generate_nonce_str();
         $data['time_stamp'] = $this->time;
         $response = $this->_request($this->pay_domain . '/order_reverse', $data);
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
     public function order_refund($data)
     {
         $data['appid'] = $this->appid;
         $data['nonce_str'] = $this->generate_nonce_str();
         $data['time_stamp'] = $this->time;
         $data['version'] = $this->version;
         $response = $this->_request($this->pay_domain . '/order_refund', $data);
         return $response;
     }
 /**
  * 退款查询
  * 必传参数
  *     mch_refund_no、ksher_refund_no、channel_refund_no三选一
  *     mch_order_no、ksher_order_no、channel_order_no三选一
  */
     public function refund_query($data)
     {
         $data['appid'] = $this->appid;
         $data['nonce_str'] = $this->generate_nonce_str();
         $data['time_stamp'] = $this->time;
         $response = $this->_request($this->pay_domain . '/refund_query', $data);
         return $response;
     }
 /**
  * 汇率查询
  * 必传参数
  *     channel
  *     fee_type
  *     date
  */
     public function rate_query($data)
     {
         $data['appid'] = $this->appid;
         $data['nonce_str'] = $this->generate_nonce_str();
         $data['time_stamp'] = $this->time;
         $response = $this->_request($this->pay_domain . '/rate_query', $data);
         return $response;
     }
 /**
  *聚合支付商户查询订单支付状态
  * 必传参数
  * mch_order_no
  */
     public function gateway_order_query($data)
     {
         $data['appid'] = $this->appid;
         $data['nonce_str'] = $this->generate_nonce_str();
         $data['time_stamp'] = $this->time;
         $response = $this->_request($this->gateway_domain . '/gateway_order_query', $data);
         return $response;
     }
 /**
  *
  * 聚合支付商户通过API提交数据
  * :param kwargs:
  * 必传参数
  *   mch_order_no: 商户订单号 str
  *   total_fee: 金额(分) int
  *   fee_type: 货币种类 st
  *   channel_list: 支付通道 str
  *   mch_code: 商户订单code str
  *   mch_redirect_url: 商户通知url str
  *   mch_redirect_url_fail: 失败回调网址 str
  *   product_name: 商品描述 str
  *   refer_url: 商家refer str
  *   device: 设备名称(PC or H5) str
  * 选传参数
  *   color: 横幅颜色 str
  *   background: 横幅背景图片 str
  *   payment_color: 支付按钮颜色 str
  *   ksher_explain: 最下方文案 str
  *   hide_explain: 是否显示最下方文案(1显示 0不显示) int
  *   expire_time: 订单过期时间(min) int
  *   hide_exp_time: 是否显示过期时间(1显示 0不显示) int
  *   logo: 横幅logo str
  *   lang: 语言(en,cn,th) str
  *   shop_name: logo旁文案 str
  *   attach: 商户附加信息 str
  * :return:
  *   {'pay_content': 'https://gateway.ksher.com/mindex?order_uuid=订单uuid'}
  */
     public function gateway_pay($data)
     {
         $data['appid'] = $this->appid;
         $data['nonce_str'] = $this->generate_nonce_str();
         $data['time_stamp'] = $this->time;
         $response = $this->_request($this->gateway_domain.'/gateway_pay', $data);
         return $response;
     }

}
