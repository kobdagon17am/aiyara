<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\Frontend\Product;
class CouponCodeController extends Controller
{

    public function __construct()
    {
        $this->middleware('customer');
    }

   public static function coupon(Request $request){


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
            $resule = ['status'=>'fail','massage'=>'Coupon Code นี้ถูกใช้งานเเล้ว'];
            //1=ใช้งานได้,2=ถูกใช้แล้ว,3=หมดอายุแล้ว,4=import excel,5=Gen code
          }elseif($coupon->pro_status == 3 || $coupon->pro_status == 4 || $coupon->pro_status == 5){
            $resule = ['status'=>'fail','massage'=>'Coupon Code นี้ยังไม่พร้อมใช้งาน'];
          }else{
            $pro_edate =$coupon->pro_edate;
            $now_date = date('Y-m-d');
            if(strtotime($pro_edate) <= strtotime($now_date)){
              $resule = ['status'=>'fail','massage'=>'Promotions หมดอายุการใช้งานแล้ว'];
            }else{
               $category_id = 9;//coupon type
               $html = Product::product_list_coupon($coupon->promotion_id_fk,$request->type,$category_id,$request->coupon_code);
               $resule = ['status'=>'success','massage'=>'success','html'=>$html,'coupon'=>$request->coupon_code];
               return $resule;
            }

          }

      }else{
        $resule = ['status'=>'fail','massage'=>'ไม่พบ Coupon Code นี้ในระบบ'];
      }

       return $resule;

   }

public static function dt_coupon_code(Request $request){

    $columns = array(
        0 => 'id',
        1 => 'date',
        2 => 'expiry_date',
        3 => 'code',
        4 => 'detail',
        5 => 'status',
    );

    if( empty($request->input('search.value')) and empty($request->input('status')) ){

       $totalData =  DB::table('db_promotion_cus')
       ->leftjoin('db_promotion_code', 'db_promotion_code.id', '=', 'db_promotion_cus.promotion_code_id_fk')
       ->leftjoin('promotions', 'promotions.id', '=', 'db_promotion_code.promotion_id_fk')
       ->where('db_promotion_code.approve_status','=',1)
       ->where('db_promotion_cus.customer_id_fk','=',Auth::guard('c_user')->user()->id)
       ->count();
       $totalFiltered = $totalData;
       $limit = $request->input('length');
       $start = $request->input('start');
            //$order = $columns[$request->input('order.0.column')];
            //$dir = $request->input('order.0.dir');

       $coupon =  DB::table('db_promotion_cus')
       ->select('db_promotion_code.id','db_promotion_code.pro_sdate','db_promotion_code.pro_edate','db_promotion_cus.promotion_code','promotions.name_thai','promotions.name_eng','promotions.name_laos','promotions.name_burma','promotions.name_cambodia','db_promotion_cus.pro_status')
       ->leftjoin('db_promotion_code', 'db_promotion_code.id', '=', 'db_promotion_cus.promotion_code_id_fk')
       ->leftjoin('promotions', 'promotions.id', '=', 'db_promotion_code.promotion_id_fk')
       ->where('db_promotion_code.approve_status','=',1)
       ->where('db_promotion_cus.customer_id_fk','=',Auth::guard('c_user')->user()->id)
       ->offset($start)
       ->limit($limit)
        ->orderby('db_promotion_cus.pro_status','ASC')
       ->get();



// dd($request->input('status'));

   }else{
    $status = $request->status;
    $search=$request->input('search.value');
    $date = date('Y-m-d');

    // <option value="">ทั้งหมด</option>
    // <option value="1">ใช้งานได้(Usable)</option>
    // <option value="2">ถูกใช้แล้ว(Used)</option>
    // <option value="expiry_date">หมดอายุ(Expri)</option>
    // dd($status);

     $totalData =  DB::table('db_promotion_cus')
       ->leftjoin('db_promotion_code', 'db_promotion_code.id', '=', 'db_promotion_cus.promotion_code_id_fk')
       ->leftjoin('promotions', 'promotions.id', '=', 'db_promotion_code.promotion_id_fk')
       ->where('db_promotion_code.approve_status','=',1)
       ->where('db_promotion_cus.customer_id_fk','=',Auth::guard('c_user')->user()->id)
       ->whereRaw(("case
       WHEN '{$status}' = 1 THEN db_promotion_cus.pro_status = 1 and db_promotion_code.pro_edate >= '{$date}'
       WHEN '{$status}' = 2 THEN db_promotion_cus.pro_status = 2
       WHEN '{$status}' = 'expiry_date' THEN db_promotion_cus.pro_status = 1 and db_promotion_code.pro_edate < '{$date}'
       END"))
       ->whereRaw( "(db_promotion_cus.promotion_code LIKE '%{$search}%')" )
       ->count();
       $totalFiltered = $totalData;
       $limit = $request->input('length');
       $start = $request->input('start');


    $coupon =  DB::table('db_promotion_cus')
       ->select('db_promotion_code.id','db_promotion_code.pro_sdate','db_promotion_code.pro_edate','db_promotion_cus.promotion_code','promotions.name_thai','promotions.name_eng','promotions.name_laos','promotions.name_burma','promotions.name_cambodia','db_promotion_cus.pro_status')
       ->leftjoin('db_promotion_code', 'db_promotion_code.id', '=', 'db_promotion_cus.promotion_code_id_fk')
       ->leftjoin('promotions', 'promotions.id', '=', 'db_promotion_code.promotion_id_fk')
       ->where('db_promotion_code.approve_status','=',1)
       ->where('db_promotion_cus.customer_id_fk','=',Auth::guard('c_user')->user()->id)

       ->whereRaw(("case
       WHEN '{$status}' = 1 THEN db_promotion_cus.pro_status = 1 and db_promotion_code.pro_edate >= '{$date}'
       WHEN '{$status}' = 2 THEN db_promotion_cus.pro_status = 2
       WHEN '{$status}' = 'expiry_date' THEN db_promotion_cus.pro_status = 1 and db_promotion_code.pro_edate < '{$date}'
       END"))
       ->whereRaw( "(db_promotion_cus.promotion_code LIKE '%{$search}%')" )
       ->offset($start)
       ->limit($limit)
       ->orderby('db_promotion_cus.pro_status','ASC')
       ->get();

}

$data = array();
$i=0;
foreach ($coupon as $value){
    $i++;

       $columns = array(
        0 => 'id',
        1 => 'date',
        2 => 'expiry_date',
        3 => 'code',
        4 => 'detail',
        5 => 'status',
    );

    $nestedData['id'] = $i;

    $nestedData['date'] = '<label class="label label-inverse-info-border"><b>'.date('d/m/Y',strtotime($value->pro_sdate)).'</b></label>';


    if( strtotime($value->pro_edate) > strtotime(date('Y-m-d H:i:s')) ) {
      $nestedData['expiry_date'] = '<label class="label label-inverse-info-border"><b>'.date('d/m/Y',strtotime($value->pro_edate)).'</b></label>';
    }else{
      $nestedData['expiry_date'] = '<label class="label label-inverse-danger"><b>'.date('d/m/Y',strtotime($value->pro_edate)).'</b></label>';
    }

    $nestedData['code'] = '<label class="label label-inverse-primary"><b style="color:#000">'.$value->promotion_code.'</b></label>' ;

    $nestedData['detail'] = '<b>'.$value->name_thai.'</b>';

   if($value->pro_status == '1'){

    if(strtotime($value->pro_edate) < strtotime(date('Y-m-d H:i:s'))){
      $nestedData['status'] = '<span class="label label-danger">Expri</span>';
    }else{
      $nestedData['status'] = '<span class="label label-success">Usable</span>';
     }

  }elseif($value->pro_status == '2'){
      $nestedData['status'] = '<span class="label label-inverse">Used</span>';
  }else{
       $nestedData['status'] = '<span class="label label-danger">Expri</span>';
  }


 $data[] = $nestedData;
}

$json_data = array(
    "draw" => intval($request->input('draw')),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data,
);

return json_encode($json_data);
}



}


