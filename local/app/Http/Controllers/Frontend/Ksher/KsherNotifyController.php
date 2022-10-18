<?php

namespace App\Http\Controllers\Frontend\Ksher;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\Fc\AicashConfirmeController;
use App\Http\Controllers\Frontend\Ksher\KsherController;
use App\Models\Frontend\PvPayment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KsherNotifyController extends Controller
{
    const SUCCESS = 'SUCCESS';

    public function ksher_notify()
    {
        $time = date("Y-m-d H:i:s", time());
        Log::info(">>>> Ksher Notify <<<<<");
        $ksherPay = new KsherController;
        // //1.接收参数
        $input = file_get_contents("php://input");
        $query = urldecode($input);
        if (!$query) {
            Log::info("NO RETURN DATA");
            return response()->json(array('result' => 'FAIL', "msg" => 'NO RETURN DATA'));
        }
        //2.验证参数
        $data_array = json_decode($query, true);
        Log::info($data_array);
        Log::info("notify data :" . json_encode($data_array));
        if (!isset($data_array['data']) || !isset($data_array['data']['mch_order_no']) || !$data_array['data']['mch_order_no']) {
            Log::info("notify data FAIL");
            return response()->json(array('result' => 'FAIL', "msg" => 'RETURN DATA ERROR'));
        }
        //3.处理订单
        if (array_key_exists("code", $data_array)
            && array_key_exists("sign", $data_array)
            && array_key_exists("data", $data_array)
            && array_key_exists("result", $data_array['data'])
            && $data_array['data']["result"] == self::SUCCESS) {
            //3.1验证签名
            $verify_sign = $ksherPay->verify_ksher_sign($data_array['data'], $data_array['sign']);
            Log::info("IN IF function sign : " . $verify_sign);
            if ($verify_sign == 1) {
                $this->updateOrInsertStatus($data_array);
                $this->updateOrder($data_array);
                Log::info('change order status');
            }
        }
        Log::info("------notify data " . $time . " end------");
        return response()->json(array('result' => 'SUCCESS', "msg" => 'OK'));
    }

    private function updateOrInsertStatus($response)
    {
        $ksher_status = DB::table('ksher_statuses')
            ->updateOrInsert([
                'mch_order_no' => Arr::get($response, 'data.mch_order_no'),
                'pay_mch_order_no' => Arr::get($response, 'data.pay_mch_order_no'),
            ],
                collect($response['data'])->except('appid', 'mch_order_no', 'pay_mch_order_no')
                    ->merge([
                        'sign' => Arr::get($response, 'sign'),
                        'created_at' => now(),
                        'total_price' => $this->formatPrice(Arr::get($response, 'data.total_fee')),
                    ])
                    ->toArray()
            );

        Log::info(">>>> Ksher Status Completed <<<<");
    }

    public function updateOrder($response)
    {
        $getKsherData = DB::table('ksher_statuses')
            ->where('mch_order_no', Arr::get($response, 'data.mch_order_no'))
            ->first();
        $type_code = substr($getKsherData->mch_order_no,0,1);


        if ($getKsherData->channel == 'promptpay') {

          $pay_type_id_fk = 15;

          $payInfo = [
              'pay_type_id_fk' =>  $pay_type_id_fk,
              'prompt_pay_price' => $this->formatPrice($getKsherData->total_fee),
          ];
        }

        if($getKsherData->channel == 'truemoney'){
          $pay_type_id_fk = 16;
          $payInfo = [
              'pay_type_id_fk' =>  $pay_type_id_fk,
              'true_money_price' => $this->formatPrice($getKsherData->total_fee),
          ];
      }

      if($getKsherData->channel == 'ktbcard'){
        $pay_type_id_fk = 2;
        $payInfo = [
            'pay_type_id_fk' =>  $pay_type_id_fk,
            'credit_price' => $this->formatPrice($getKsherData->total_fee),
        ];
    }

        if ($type_code == 'W') {
          $getOrderData = DB::table('db_add_ai_cash')
          ->where('code_order', Arr::get($response, 'data.mch_order_no'))
          ->first();

          if ($getOrderData->total_amt == $getKsherData->total_price) { //ยอดเงินเท่ากันให้อัพเดททันที
            $dataUpdate = collect([
                'order_status_id_fk' => 5,
            ]);

            if ($getOrderData->order_status_id_fk == 1 || $getOrderData->order_status_id_fk == 3) {

              $res = AicashConfirmeController::aicash_confirme($getOrderData->id, $getOrderData->customers_id_fk,'customer','',$pay_type_id_fk);

            }

        } else {
            $dataUpdate = collect([
                'order_status_id_fk' => 2,
            ]);
        }



      Log::info('>>>> Update Order After Ksher Insert <<<<');
      Log::info($dataUpdate->merge($payInfo));

      $order = DB::table('db_add_ai_cash')
      ->where('code_order', $getKsherData->mch_order_no)
      ->update($dataUpdate->merge($payInfo)->toArray());
      Log::info('Order Update Status ' . $order);


        } else {
            $getOrderData = DB::table('db_orders')
                ->where('code_order', Arr::get($response, 'data.mch_order_no'))
                ->first();

            if ($getOrderData->total_price == $getKsherData->total_price) { //ยอดเงินเท่ากันให้อัพเดททันที
                $dataUpdate = collect([
                    'order_status_id_fk' => 5,
                ]);

                if ($getOrderData->order_status_id_fk == 1 || $getOrderData->order_status_id_fk == 3) {
                    $resulePv = PvPayment::PvPayment_type_confirme($getOrderData->id, $getOrderData->customers_id_fk, '2', 'customer');


                }

            } else {
                $dataUpdate = collect([
                    'order_status_id_fk' => 2,
                ]);
            }

        if ($getKsherData->channel == 'promptpay') {

          if($getOrderData->purchase_type_id_fk == '5'){
            $pay_type_id_fk = 17;
          }else{
            $pay_type_id_fk = 15;
          }

          $payInfo = [
              'pay_type_id_fk' =>  $pay_type_id_fk,
              'prompt_pay_price' => $this->formatPrice($getKsherData->total_fee),
          ];
        }

        if($getKsherData->channel == 'truemoney'){
          if($getOrderData->purchase_type_id_fk == '5'){
            $pay_type_id_fk = 18;
          }else{
            $pay_type_id_fk = 16;
          }
          $payInfo = [
              'pay_type_id_fk' =>  $pay_type_id_fk,
              'true_money_price' => $this->formatPrice($getKsherData->total_fee),
          ];
        }

        if($getKsherData->channel == 'ktbcard'){
          if($getOrderData->purchase_type_id_fk == '5'){
            $pay_type_id_fk = 9;
          }else{
            $pay_type_id_fk = 2;
          }
          $payInfo = [
              'pay_type_id_fk' =>  $pay_type_id_fk,
              'credit_price' => $this->formatPrice($getKsherData->total_fee),
          ];
        }

          Log::info('>>>> Update Order After Ksher Insert <<<<');
          Log::info($dataUpdate->merge($payInfo));

          $order = DB::table('db_orders')
          ->where('code_order', $getKsherData->mch_order_no)
          ->update($dataUpdate->merge($payInfo)->toArray());
          Log::info('Order Update Status ' . $order);
        }

    }

    private function formatPrice($price)
    {
        return $price / 100;
    }

}
