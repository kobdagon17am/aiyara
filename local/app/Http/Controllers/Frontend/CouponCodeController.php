<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\Frontend\Product;
use Cart;
use DataTables;
class CouponCodeController extends Controller
{

    public function __construct()
    {
        $this->middleware('customer');
    }

   public static function coupon(Request $request){


    $cartCollection = Cart::session($request->type)->getContent();
    $data = $cartCollection->toArray();

    if ($data) {
        foreach ($data as $value) {

            if ($value['attributes']['coupong']) {
                $data = ['status' => 'fail', 'message' => 'Coupong Code สามารถใช้งานได้เพียง 1 ครั้ง ต่อ 1 บิลเท่านั้น'];
                return $data;
            }
        }
    }


       $coupon =  DB::table('db_promotion_cus')
        ->select('db_promotion_code.promotion_id_fk','db_promotion_code.id','db_promotion_code.pro_sdate','db_promotion_code.pro_edate','db_promotion_cus.promotion_code','promotions.name_thai','promotions.name_eng','promotions.name_laos','promotions.name_burma','promotions.name_cambodia','db_promotion_cus.pro_status')
       ->leftjoin('db_promotion_code', 'db_promotion_code.id', '=', 'db_promotion_cus.promotion_code_id_fk')
       ->leftjoin('promotions', 'promotions.id', '=', 'db_promotion_code.promotion_id_fk')

       ->where('promotions.orders_type_id','LIKE','%'.$request->type.'%')
       ->wheredate('promotions.show_startdate','<=',date('Y-m-d'))
       ->wheredate('promotions.show_enddate','>=',date('Y-m-d'))
       ->wheredate('promotions.promotion_coupon_status','=',1)//คูปองเท่านั้น
       ->wheredate('promotions.status','=',1)//แสดงผล
       ->wheredate('db_promotion_code.pro_sdate','<=',date('Y-m-d'))
       ->wheredate('db_promotion_code.pro_edate','>=',date('Y-m-d'))
       ->where('db_promotion_cus.pro_status','=',1)//อนุมัติ code นี้ใช้ได้
       ->where('db_promotion_cus.promotion_code','=',$request->coupon_code)
       ->first();

       if($coupon){
          if($coupon->pro_status == 2){
            $resule = ['status'=>'fail','message'=>'Coupon Code นี้ถูกใช้งานเเล้ว'];
            //1=ใช้งานได้,2=ถูกใช้แล้ว,3=หมดอายุแล้ว,4=import excel,5=Gen code
          }elseif($coupon->pro_status == 3 || $coupon->pro_status == 4 || $coupon->pro_status == 5){
            $resule = ['status'=>'fail','message'=>'Coupon Code นี้ยังไม่พร้อมใช้งาน'];
          }else{
            $pro_edate =$coupon->pro_edate;
            $now_date = date('Y-m-d');
            if(strtotime($pro_edate) <= strtotime($now_date)){
              $resule = ['status'=>'fail','message'=>'Promotions หมดอายุการใช้งานแล้ว'];
            }else{
               $category_id = 9;//coupon type
               $html = Product::product_list_coupon($coupon->promotion_id_fk,$request->type,$category_id,$request->coupon_code);
               $resule = ['status'=>'success','message'=>'success','html'=>$html,'coupon'=>$request->coupon_code];
               return $resule;
            }

          }

      }else{
        $resule = ['status'=>'fail','message'=>'ไม่พบ Coupon Code นี้ในระบบ'];
      }

       return $resule;

   }


public static function dt_coupon_code(Request $rs)
    {
      $date = date('Y-m-d');
        $sTable = DB::table('db_promotion_cus')
       ->select('db_promotion_code.id','db_promotion_code.pro_sdate','db_promotion_code.pro_edate','db_promotion_cus.promotion_code',
       'promotions.name_thai','promotions.name_eng','promotions.name_laos','promotions.name_burma','promotions.name_cambodia',
       'db_promotion_cus.used_user_name','db_promotion_cus.pro_status','customers.user_name','customers.first_name','customers.last_name')
       ->leftjoin('db_promotion_code', 'db_promotion_code.id', '=', 'db_promotion_cus.promotion_code_id_fk')
       ->leftjoin('promotions', 'promotions.id', '=', 'db_promotion_code.promotion_id_fk')
       ->leftjoin('customers', 'customers.user_name', '=', 'db_promotion_cus.used_user_name')
       ->where('db_promotion_code.approve_status','=',1)
       ->where('db_promotion_cus.customer_id_fk','=',Auth::guard('c_user')->user()->id)

       ->whereRaw(("case
       WHEN '{$rs->status}' = 1 THEN db_promotion_cus.pro_status = 1 and db_promotion_code.pro_edate >= '{$date}'
       WHEN '{$rs->status}' = 2 THEN db_promotion_cus.pro_status = 2
       WHEN '{$rs->status}' = 'expiry_date' THEN db_promotion_cus.pro_status = 1 and db_promotion_code.pro_edate < '{$date}'
       END"));


        $sQuery = DataTables::of($sTable);

        return $sQuery

            ->addColumn('date', function ($row) {

             $date = '<label class="label label-inverse-info-border"><b>'.date('d/m/Y',strtotime($row->pro_sdate)).'</b></label>';

                return $date;
            })
            ->addColumn('expiry_date', function ($row) {
              if( strtotime($row->pro_edate) > strtotime(date('Y-m-d H:i:s')) ) {
                $expiry_date = '<label class="label label-inverse-info-border"><b>'.date('d/m/Y',strtotime($row->pro_edate)).'</b></label>';
              }else{
                $expiry_date = '<label class="label label-inverse-danger"><b>'.date('d/m/Y',strtotime($row->pro_edate)).'</b></label>';
              }
              return $expiry_date;
            })
            ->addColumn('code', function ($row) {
              $code = '<label class="label label-inverse-primary"><b style="color:#000">'.$row->promotion_code.'</b></label>' ;
                return $code;
            })
            ->addColumn('detail', function ($row) {
              $detail = '<b>'.$row->name_thai.'</b>';
              return $detail;
            })


            ->addColumn('status', function ($row) {
              if($row->pro_status == '1'){

                if(strtotime($row->pro_edate) < strtotime(date('Y-m-d H:i:s'))){
                  $status = '<span class="label label-danger">Expried</span>';
                }else{
                  $status = '<span class="label label-success">Usable</span>';
                 }

              }elseif($row->pro_status == '2'){
                  $status = '<span class="label label-inverse">Used</span>';
              }else{
                   $status = '<span class="label label-danger">Expried</span>';
              }

                return $status;
            })

            ->addColumn('used', function ($row) {
              if($row->used_user_name){
                $used = '<b>('.@$row->user_name.') '.@$row->first_name.' '.@$row->last_name.'</b>';
              }else{
                $used = '';
              }

                 return $used;
             })

            ->rawColumns(['date','expiry_date','detail','status','code','used'])
            ->make(true);
    }



}


