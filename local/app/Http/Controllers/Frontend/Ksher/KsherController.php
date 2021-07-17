<?php

namespace App\Http\Controllers\Frontend\Ksher;

use App\Http\Controllers\Controller;

class KsherController extends Controller
{
    protected $time;
    protected $appid;
    protected $version; //SDK版本
    protected $pay_domain;
    protected $gateway_domain;
    protected $pubkey;
    protected $user_agent;

    public function __construct()
    {

        $this->middleware('customer');
        // $self->user_agent = $_SERVER['HTTP_USER_AGENT'];
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

    public static function gateway_ksher($data)
    {
        //$data['total_fee'] = 0.1;
        $gateway_pay_data = array('mch_order_no' => $data['mch_order_no'],
            "total_fee" => round($data['total_fee'], 2) * 100,
            "fee_type" => $data['fee_type'],
            // "channel_list" => 'truemoney',
            "channel_list" => $data['channel_list'],
            'mch_code' => $data['mch_code'],
            'mch_redirect_url' => route('payment/success'),
            'mch_redirect_url_fail' => route('payment/fail'),
            'product_name' => $data['product_name'],
            'refer_url' => route('ksher'),
            'mch_notify_url' => route('ksher_notify'),
            //  'shop_name' => 'AIYARA PLANET บริษัท ไอยรา แพลนเน็ต',
            //  'device' => 'PC',
            //'attach' =>'หมายเหตุหรือคถอธิบาย',
            'color' => '#18c160',
            'logo' => asset('frontend/assets/images/logo.png'),
        );
        $gateway_pay_response = KsherController::gateway_pay($gateway_pay_data);
        $gateway_pay_array = json_decode($gateway_pay_response, true);

        if (isset($gateway_pay_array['data']['pay_content'])) {
          $resule = ['status' => 'success', 'url' => $gateway_pay_array['data']['pay_content']];
            return $resule;
        } else {
          $resule = ['status' => 'fail', 'url' => $gateway_pay_array['data']['pay_content'] ='' ];
          return $resule;
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

    public static function ksher_sign($data)
    {
        $self = new static; //OBJECT INSTANTIATION
        $message = self::paramData($data);
        $private_key = openssl_get_privatekey($self->privatekey);

        openssl_sign($message, $encoded_sign, $private_key, OPENSSL_ALGO_MD5);

        openssl_free_key($private_key);
        $encoded_sign = bin2hex($encoded_sign);
        return $encoded_sign;
    }

    public static function verify_ksher_sign($data, $sign)
    {
        $self = new static;
        $sign = pack("H*", $sign);
        $message = self::paramData($data);
        $res = openssl_get_publickey($self->pubkey);
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

    public static function _request($url, $data = array())
    {
        $self = new static;
        try {
            if (!empty($data) && is_array($data)) {
                $params = '';
                $data['sign'] = $self->ksher_sign($data);
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
                    if (!$self->verify_ksher_sign($response_array['data'], $response_array['sign'])) {
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
                            "time_stamp" => $self->time,
                            "version" => $self->version,
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

    public static function quick_pay($data)
    {
        $self = new static;
        $data['appid'] = $self->appid;
        $data['nonce_str'] = $self->generate_nonce_str();
        $data['time_stamp'] = $self->time;
        $response = $self->_request($self->pay_domain . '/quick_pay', $data);
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
    public static function jsapi_pay($data)
    {
        $self = new static;
        $data['appid'] = $self->appid;
        $data['nonce_str'] = $self->generate_nonce_str();
        $data['time_stamp'] = $self->time;
        $response = $self->_request($self->pay_domain . '/jsapi_pay', $data);
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
    public static function native_pay($data)
    {
        $self = new static;
        $data['appid'] = $self->appid;
        $data['nonce_str'] = $self->generate_nonce_str();
        $data['time_stamp'] = $self->time;
        $response = $self->_request($self->pay_domain . '/native_pay', $data);
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

    public static function minipro_pay($data)
    {
        $self = new static;
        $data['appid'] = $self->appid;
        $data['nonce_str'] = $self->generate_nonce_str();
        $data['time_stamp'] = $self->time;
        $response = $self->_request($self->pay_domain . '/mini_program_pay', $data);
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
    public static function app_pay($data)
    {
        $self = new static;
        $data['appid'] = $self->appid;
        $data['nonce_str'] = $self->generate_nonce_str();
        $data['time_stamp'] = $self->time;
        $response = $self->_request($self->pay_domain . '/app_pay', $data);
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
    public static function wap_pay($data)
    {
        $self = new static;
        $data['appid'] = $self->appid;
        $data['nonce_str'] = $self->generate_nonce_str();
        $data['time_stamp'] = $self->time;
        $response = $self->_request($self->pay_domain . '/wap_pay', $data);
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
    public static function web_pay($data)
    {
        $self = new static;
        $data['appid'] = $self->appid;
        $data['nonce_str'] = $self->generate_nonce_str();
        $data['time_stamp'] = $self->time;
        $response = $self->_request($self->pay_domain . '/web_pay', $data);
        return $response;
    }
    /**
     * 订单查询
     * 必传参数
     *     mch_order_no、ksher_order_no、channel_order_no三选一
     */
    public static function order_query($data)
    {
        $self = new static;
        $data['appid'] = $self->appid;
        $data['nonce_str'] = $self->generate_nonce_str();
        $data['time_stamp'] = $self->time;
        $response = $self->_request($self->pay_domain . '/order_query', $data);
        return $response;
    }
    /**
     * 订单关闭
     * 必传参数
     *     mch_order_no、ksher_order_no、channel_order_no三选一
     * 选传参数
     *     operator_id
     */
    public static function order_close($data)
    {
        $self = new static;
        $data['appid'] = $self->appid;
        $data['nonce_str'] = $self->generate_nonce_str();
        $data['time_stamp'] = $self->time;
        $response = $self->_request($self->pay_domain . '/order_close', $data);
        return $response;
    }
    /**
     * 订单撤销
     * 必传参数
     *     mch_order_no、ksher_order_no、channel_order_no三选一
     * 选传参数
     *     operator_id
     */
    public static function order_reverse($data)
    {
        $self = new static;
        $data['appid'] = $self->appid;
        $data['nonce_str'] = $self->generate_nonce_str();
        $data['time_stamp'] = $self->time;
        $response = $self->_request($self->pay_domain . '/order_reverse', $data);
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
    public static function order_refund($data)
    {
        $self = new static;
        $data['appid'] = $self->appid;
        $data['nonce_str'] = $self->generate_nonce_str();
        $data['time_stamp'] = $self->time;
        $data['version'] = $self->version;
        $response = $self->_request($self->pay_domain . '/order_refund', $data);
        return $response;
    }
    /**
     * 退款查询
     * 必传参数
     *     mch_refund_no、ksher_refund_no、channel_refund_no三选一
     *     mch_order_no、ksher_order_no、channel_order_no三选一
     */
    public static function refund_query($data)
    {
        $self = new static;
        $data['appid'] = $self->appid;
        $data['nonce_str'] = $self->generate_nonce_str();
        $data['time_stamp'] = $self->time;
        $response = $self->_request($self->pay_domain . '/refund_query', $data);
        return $response;
    }
    /**
     * 汇率查询
     * 必传参数
     *     channel
     *     fee_type
     *     date
     */
    public static function rate_query($data)
    {
        $self = new static;
        $data['appid'] = $self->appid;
        $data['nonce_str'] = $self->generate_nonce_str();
        $data['time_stamp'] = $self->time;
        $response = $self->_request($self->pay_domain . '/rate_query', $data);
        return $response;
    }
    /**
     *聚合支付商户查询订单支付状态
     * 必传参数
     * mch_order_no
     */
    public static function gateway_order_query($data)
    {
        $self = new static;
        $data['appid'] = $self->appid;
        $data['nonce_str'] = $self->generate_nonce_str();
        $data['time_stamp'] = $self->time;
        $response = $self->_request($self->gateway_domain . '/gateway_order_query', $data);
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
     *   hide_exp_time: 是否显示过期时间(1显示 0不显示) intf
     *   logo: 横幅logo str
     *   lang: 语言(en,cn,th) str
     *   shop_name: logo旁文案 str
     *   attach: 商户附加信息 str
     * :return:
     *   {'pay_content': 'https://gateway.ksher.com/mindex?order_uuid=订单uuid'}
     */
    public static function gateway_pay($data)
    {
        $self = new static;
        $data['appid'] = $self->appid;
        $data['nonce_str'] = $self->generate_nonce_str();
        $data['time_stamp'] = $self->time;
        $response = $self->_request($self->gateway_domain . '/gateway_pay', $data);
        return $response;
    }

}
