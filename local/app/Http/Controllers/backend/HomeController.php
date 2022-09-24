<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Backend\RequisitionBetweenBranch;
use App\Models\Backend\Orders;

class HomeController extends Controller
{

  public function test_sql(Request $request)
  {
      // ทดสอบดึงสินค้า print_requisition_detail_real
                                                                        $sTable = DB::select(
                                                                        "
                                                                                  SELECT
                                                                                  db_pick_pack_packing.id,
                                                                                  db_pick_pack_packing.p_size,
                                                                                  db_pick_pack_packing.p_weight,
                                                                                  db_pick_pack_packing.p_amt_box,
                                                                                  db_pick_pack_packing.packing_code_id_fk as packing_code_id_fk,
                                                                                  db_pick_pack_packing.packing_code as packing_code,
                                                                                  db_delivery.id as db_delivery_id,
                                                                                  db_delivery.receipt,
                                                                                  db_delivery.packing_code as db_delivery_packing_code
                                                                                  FROM `db_pick_pack_packing`
                                                                                  LEFT JOIN db_delivery on db_delivery.id=db_pick_pack_packing.delivery_id_fk
                                                                                  WHERE
                                                                                  db_pick_pack_packing.packing_code_id_fk = 55
                                                                                  AND db_pick_pack_packing.delivery_id_fk = 2522
                                                                                  ORDER BY db_pick_pack_packing.id
                                                                                ",);

                                                                                $d2 = DB::select(' SELECT receipt from db_delivery WHERE packing_code=798');

                                                                                $receipt = '';
                                                                                $arr1 = [];

                                                                                foreach ($d2 as $key => $row) {
                                                                                      array_push($arr1, $row->receipt);
                                                                                  }
                                                                                $receipt = implode(',', $arr1);


                                                                                $fid_arr = explode(',', $receipt);
                                                                                $order_arr = DB::table('db_orders')
                                                                                ->select('code_order')
                                                                                ->whereIn('code_order', $fid_arr)
                                                                                ->pluck('code_order')
                                                                                ->toArray();


// dd($order_arr);
                                                                                foreach ($sTable as $key => $row) {
                                                                                  $sum_amt = 0;
                                                                                  $r_ch_t = '';
                                                                                  $fid_arr = explode(',', $receipt);
                                                                                  $order_arr = DB::table('db_orders')
                                                                                      ->select('id')
                                                                                      ->whereIn('code_order', $fid_arr)
                                                                                      ->pluck('id')
                                                                                      ->toArray();
                                                                                  $Products = Orders::getAllProduct($order_arr);
                                                                                  if (@$Products) {
                                                                                    foreach ($Products as $key => $value) {
                                                                                        if (!empty($value->product_id_fk)) {
                                                                                         echo 'product_name '.@$value->product_name .' ';
                                                                                         echo 'amt_sum '.@$value->amt_sum .' ';
                                                                                          @$sum_amt += @$value->amt_sum;
                                                                                        }
                                                                                    }
                                                                                    echo 'sum_amt '. @$sum_amt .' ';
                                                                                }
                                                                              }

  }

    public function index(Request $request)
    {
      $wait_approve_requisition = RequisitionBetweenBranch::waitApproveCount();

          // วุฒิเพิ่มมา
            if (\Auth::user()->permission == '1' ) {
              $wait_approve_requisition_transfer = RequisitionBetweenBranch::with('requisition_details')
              ->where('is_approve', RequisitionBetweenBranch::APPROVED)
              ->where('is_transfer', RequisitionBetweenBranch::WAIT_TRANSFER)->count();
            }else{
              $wait_approve_requisition_transfer = RequisitionBetweenBranch::with('requisition_details')
              ->where('is_approve', RequisitionBetweenBranch::APPROVED)
              ->where('is_transfer', RequisitionBetweenBranch::WAIT_TRANSFER)
              ->where('from_branch_id',\Auth::user()->branch_id_fk)
              ->orWhere('to_branch_id',\Auth::user()->branch_id_fk)
              ->where('is_approve', RequisitionBetweenBranch::APPROVED)
              ->where('is_transfer', RequisitionBetweenBranch::WAIT_TRANSFER)->count();
            }

            $sPermission = \Auth::user()->permission ;
            $business_location_id_fk = \Auth::user()->business_location_id_fk;
            if($sPermission==1){
              $customer_doc = DB::table('register_files')->select('id','type','regis_doc_status')->where('regis_doc_status',0)
              // ->where('type','!=',4)
              ->groupBy('customer_id')->get();
            }else{
              $customer_doc = DB::table('register_files')->select('id','type','regis_doc_status','customer_id')->where('regis_doc_status',0)
              // ->where('type','!=',4)
              ->where('business_location_id_fk',$business_location_id_fk)
              ->groupBy('customer_id')->get();

              $arr_not = [];
              foreach($customer_doc as $c){

                // type_1
                $c_null = DB::table('register_files')->select('id')
                ->where('business_location_id_fk',$business_location_id_fk)
                ->where('customer_id',$c->customer_id)
                ->where('regis_doc_status',0)
                ->where('type',1)
                ->orderBy('id','desc')
                ->get();
                foreach($c_null as $c_null_item){
                  $c_data = DB::table('register_files')->select('id')
                  ->where('business_location_id_fk',$business_location_id_fk)
                  ->where('customer_id',$c->customer_id)
                  ->where('regis_doc_status',1)
                  ->where('type',1)
                  ->orderBy('id','desc')
                  ->first();
                  $c_data2 = DB::table('register_files')->select('id','type')
                  ->where('business_location_id_fk',$business_location_id_fk)
                  ->where('customer_id',$c->customer_id)
                  ->where('regis_doc_status',2)
                  ->where('type',1)
                  ->orderBy('id','desc')
                  ->first();
                  if($c_data){
                    array_push($arr_not,$c_null_item->id);
                  }
                  if($c_data2){
                    if($c_data2->id > $c_null_item->id){
                      array_push($arr_not,$c_null_item->id);
                    }
                  }
                }

                 // type_2
                 $c_null = DB::table('register_files')->select('id')
                 ->where('business_location_id_fk',$business_location_id_fk)
                 ->where('customer_id',$c->customer_id)
                 ->where('regis_doc_status',0)
                 ->where('type',2)
                 ->orderBy('id','desc')
                 ->get();
                 foreach($c_null as $c_null_item){
                   $c_data = DB::table('register_files')->select('id')
                   ->where('business_location_id_fk',$business_location_id_fk)
                   ->where('customer_id',$c->customer_id)
                   ->where('regis_doc_status',1)
                   ->where('type',2)
                   ->orderBy('id','desc')
                   ->first();
                   $c_data2 = DB::table('register_files')->select('id','type')
                   ->where('business_location_id_fk',$business_location_id_fk)
                   ->where('customer_id',$c->customer_id)
                   ->where('regis_doc_status',2)
                   ->where('type',2)
                   ->orderBy('id','desc')
                   ->first();
                   if($c_data){
                     array_push($arr_not,$c_null_item->id);
                   }
                   if($c_data2){
                     if($c_data2->id > $c_null_item->id){
                       array_push($arr_not,$c_null_item->id);
                     }
                   }
                 }

                  // type_3
                  $c_null = DB::table('register_files')->select('id')
                  ->where('business_location_id_fk',$business_location_id_fk)
                  ->where('customer_id',$c->customer_id)
                  ->where('regis_doc_status',0)
                  ->where('type',3)
                  ->orderBy('id','desc')
                  ->get();
                  foreach($c_null as $c_null_item){
                    $c_data = DB::table('register_files')->select('id')
                    ->where('business_location_id_fk',$business_location_id_fk)
                    ->where('customer_id',$c->customer_id)
                    ->where('regis_doc_status',1)
                    ->where('type',3)
                    ->orderBy('id','desc')
                    ->first();
                    $c_data2 = DB::table('register_files')->select('id','type')
                    ->where('business_location_id_fk',$business_location_id_fk)
                    ->where('customer_id',$c->customer_id)
                    ->where('regis_doc_status',2)
                    ->where('type',3)
                    ->orderBy('id','desc')
                    ->first();
                    if($c_data){
                      array_push($arr_not,$c_null_item->id);
                    }
                    if($c_data2){
                      if($c_data2->id > $c_null_item->id){
                        array_push($arr_not,$c_null_item->id);
                      }
                    }
                  }

                   // type_4
                $c_null = DB::table('register_files')->select('id')
                ->where('business_location_id_fk',$business_location_id_fk)
                ->where('customer_id',$c->customer_id)
                ->where('regis_doc_status',0)
                ->where('type',4)
                ->orderBy('id','desc')
                ->get();
                foreach($c_null as $c_null_item){
                  $c_data = DB::table('register_files')->select('id')
                  ->where('business_location_id_fk',$business_location_id_fk)
                  ->where('customer_id',$c->customer_id)
                  ->where('regis_doc_status',1)
                  ->where('type',4)
                  ->orderBy('id','desc')
                  ->first();
                  $c_data2 = DB::table('register_files')->select('id','type')
                  ->where('business_location_id_fk',$business_location_id_fk)
                  ->where('customer_id',$c->customer_id)
                  ->where('regis_doc_status',2)
                  ->where('type',4)
                  ->orderBy('id','desc')
                  ->first();
                  if($c_data){
                    array_push($arr_not,$c_null_item->id);
                  }
                  if($c_data2){
                    if($c_data2->id > $c_null_item->id){
                      array_push($arr_not,$c_null_item->id);
                    }
                  }
                }

              }

              $customer_doc = DB::table('register_files')->select('id')->where('regis_doc_status',0)
              // ->where('type','!=',4)
              ->where('business_location_id_fk',$business_location_id_fk)
              ->whereNotIn('id',$arr_not)
              ->groupBy('customer_id')
              ->get();

              // dd($customer_doc);

            }



      $wait_approve_customer_doc = count($customer_doc);
      // return view('backend.index');
      // return view('backend.banner.index');

      return view('backend.index')->with([
        'wait_approve_requisition' => $wait_approve_requisition,
        'wait_approve_customer_doc' => $wait_approve_customer_doc,
        'wait_approve_requisition_transfer' => $wait_approve_requisition_transfer,
      ]);

    }
    public function testme(Request $request)
    {

       return view('backend.testme');
      // $r = DB::select("SELECT curdate();");
      // return $r;

    	$users = DB::table('ck_users_admin')
                 ->select(DB::raw('count(*) as user_count, isActive'))
                 ->where('isActive', '<>', 'N')
                 ->get();

    	// $users = DB::select('select count(*) as user_count, isActive from ck_users_admin where isActive<>"N" ');

        $users = DB::table('ck_users_admin')
			  ->select(DB::raw("
			  name,
			  email,
			  (CASE WHEN (permission = 1) THEN 'ADMIN' ELSE 'USER' END) as TYPE")
			)->get();

 		return $users;

 	   // $number_slow = $this->random_number();
       // usleep($number_slow);

    }



}
