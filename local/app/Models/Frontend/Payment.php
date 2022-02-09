<?php
namespace App\Models\Frontend;

use App\Models\Frontend\GiftVoucher;
use App\Models\Frontend\PaymentAddProduct;
use App\Models\Frontend\PaymentSentAddressOrder;
use App\Models\Frontend\PvPayment;
use App\Models\Frontend\RunNumberPayment;
use Auth;
use Cart;
use DB;
use Illuminate\Database\Eloquent\Model;


class Payment extends Model
{
    public static function payment_uploadfile($rs)
    {

        DB::BeginTransaction();
        $business_location_id = Auth::guard('c_user')->user()->business_location_id;
        if(empty($business_location_id)){
          $business_location_id = 1;
        }
        $customer_id = Auth::guard('c_user')->user()->id;
        // เลขใบเสร็จ
        // ปีเดือน[รันเลข]
        // 2020110 00001
        try {
            $file_slip = $rs->file_slip;
            $orderstatus_id = 2;

            if (isset($file_slip)) {
                $url = 'local/public/files_slip/' . date('Ym');

                $f_name = date('YmdHis') . '_' . $customer_id . '.' . $file_slip->getClientOriginalExtension();
                if ($file_slip->move($url, $f_name)) {
                    DB::table('payment_slip')
                        ->insert(['customer_id' => $customer_id, 'url' => $url, 'file' => $f_name,'code_order' => $rs->code_order, 'order_id' => $rs->id]);

                    $db_orders = DB::table('db_orders')
                        ->where('id', $rs->id)
                        ->update(['order_status_id_fk' => $orderstatus_id,'approve_status'=>1,'transfer_price'=>$rs->total_price,'pay_type_id_fk'=>'1']);
                        $resule = ['status' => 'success', 'message' => 'ชำระเงินแบบโอนชำระสำเร็จ'];
                }
            }

            if ($resule['status'] == 'success') {
                DB::commit();
                return $resule;

            } else {
                DB::rollback();
                return $resule;
            }
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
    }


    public static function credit_card($rs)
    {
      dd('Coming Soon');
    }

    public static function ai_cash($rs)
    {

        $business_location_id = Auth::guard('c_user')->user()->business_location_id;
        if(empty($business_location_id)){
          $business_location_id = 1;
        }
        $customer_id = Auth::guard('c_user')->user()->id;

        $db_orders = DB::table('db_orders')
        ->where('id', $rs->id)
        ->update(['pay_type_id_fk'=>'3','aicash_price'=>$rs->total_price,'member_id_aicash'=>$customer_id]);

        $resulePv = PvPayment::PvPayment_type_confirme($rs->id,$customer_id,'2','customer');
        return $resulePv;

    }

    public static function gift_voucher($rs)
    {

        $business_location_id = Auth::guard('c_user')->user()->business_location_id;
        if(empty($business_location_id)){
          $business_location_id = 1;
        }
        $customer_id = Auth::guard('c_user')->user()->id;
        DB::BeginTransaction();
        try {
                $resulePv = PvPayment::PvPayment_type_confirme($rs->id,$customer_id,'2','customer');
                if ($resulePv['status'] == 'success') {
                    DB::commit();
                    return $resulePv;

                } else {
                    DB::rollback();
                    return $resulePv;
                }

        } catch (Exception $e) {
            DB::rollback();
            return $e;

        }
    }

}


