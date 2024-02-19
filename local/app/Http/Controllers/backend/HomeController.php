<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Backend\RequisitionBetweenBranch;
use App\Models\Backend\Orders;

class HomeController extends Controller
{

  // จากรับเองเป็นจัดส่ง
  public function test_add_delivery(Request $request)
  {
    //
    // DB::table('db_orders')->where('id',109961)->update([
    //   'delivery_location' => 4,
    //   'delivery_location_frontend' => 'delivery_location_frontend',
    // ]);
    // \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddress(109961);
    // \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddressDefault(109961);
  }

  public function test_sql(Request $request)
  {
    //  $db_delivery_packing = DB::table('db_delivery_packing')->select('delivery_id_fk')->where('packing_code','P101787')->pluck('delivery_id_fk')->toArray();
    //  $db_delivery = DB::table('db_delivery')->whereIn('id',$db_delivery_packing)->get();
    //  dd( $db_delivery);

    $sTable = DB::select("
    SELECT register_files.customer_id,register_files.type,register_files.regis_doc_status
    FROM `register_files`
    WHERE register_files.regis_doc_status = 0
    GROUP BY register_files.type, register_files.customer_id;
     ");

    foreach($sTable as $td){
      $files_same = DB::table('register_files')->select('id')->where('customer_id',$td->customer_id)->where('type',$td->type)->where('regis_doc_status',1)->first();
      if($files_same){
        $files_same = DB::table('register_files')->where('customer_id',$td->customer_id)->where('type',$td->type)->where('regis_doc_status',0)->update([
          'regis_doc_status' => 9
        ]);
      }
      $files_same = DB::table('register_files')->select('id')->where('customer_id',$td->customer_id)->where('type',$td->type)->where('regis_doc_status',2)->first();
      if($files_same){
        $files_same = DB::table('register_files')->where('customer_id',$td->customer_id)->where('type',$td->type)->where('regis_doc_status',0)->update([
          'regis_doc_status' => 9
        ]);
      }
     }

     $sTable = DB::select("
     SELECT register_files.customer_id,register_files.type,register_files.regis_doc_status
     FROM `register_files`
     WHERE register_files.regis_doc_status = 0
     GROUP BY register_files.type, register_files.customer_id;
      ");

      dd($sTable);


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
              // $customer_doc = DB::table('register_files')->select('id','type','regis_doc_status','customer_id')->where('regis_doc_status',0)
              // // ->where('type','!=',4)
              // ->where('business_location_id_fk',$business_location_id_fk)
              // ->groupBy('customer_id')->get();

              // $arr_not = [];
              // foreach($customer_doc as $c){

              //   // type_1
              //   $c_null = DB::table('register_files')->select('id')
              //   ->where('business_location_id_fk',$business_location_id_fk)
              //   ->where('customer_id',$c->customer_id)
              //   ->where('regis_doc_status',0)
              //   ->where('type',1)
              //   ->orderBy('id','desc')
              //   ->get();
              //   foreach($c_null as $c_null_item){
              //     $c_data = DB::table('register_files')->select('id')
              //     ->where('business_location_id_fk',$business_location_id_fk)
              //     ->where('customer_id',$c->customer_id)
              //     ->where('regis_doc_status',1)
              //     ->where('type',1)
              //     ->orderBy('id','desc')
              //     ->first();
              //     $c_data2 = DB::table('register_files')->select('id','type')
              //     ->where('business_location_id_fk',$business_location_id_fk)
              //     ->where('customer_id',$c->customer_id)
              //     ->where('regis_doc_status',2)
              //     ->where('type',1)
              //     ->orderBy('id','desc')
              //     ->first();
              //     if($c_data){
              //       array_push($arr_not,$c_null_item->id);
              //     }
              //     if($c_data2){
              //       if($c_data2->id > $c_null_item->id){
              //         array_push($arr_not,$c_null_item->id);
              //       }
              //     }
              //   }

              //    // type_2
              //    $c_null = DB::table('register_files')->select('id')
              //    ->where('business_location_id_fk',$business_location_id_fk)
              //    ->where('customer_id',$c->customer_id)
              //    ->where('regis_doc_status',0)
              //    ->where('type',2)
              //    ->orderBy('id','desc')
              //    ->get();
              //    foreach($c_null as $c_null_item){
              //      $c_data = DB::table('register_files')->select('id')
              //      ->where('business_location_id_fk',$business_location_id_fk)
              //      ->where('customer_id',$c->customer_id)
              //      ->where('regis_doc_status',1)
              //      ->where('type',2)
              //      ->orderBy('id','desc')
              //      ->first();
              //      $c_data2 = DB::table('register_files')->select('id','type')
              //      ->where('business_location_id_fk',$business_location_id_fk)
              //      ->where('customer_id',$c->customer_id)
              //      ->where('regis_doc_status',2)
              //      ->where('type',2)
              //      ->orderBy('id','desc')
              //      ->first();
              //      if($c_data){
              //        array_push($arr_not,$c_null_item->id);
              //      }
              //      if($c_data2){
              //        if($c_data2->id > $c_null_item->id){
              //          array_push($arr_not,$c_null_item->id);
              //        }
              //      }
              //    }

              //     // type_3
              //     $c_null = DB::table('register_files')->select('id')
              //     ->where('business_location_id_fk',$business_location_id_fk)
              //     ->where('customer_id',$c->customer_id)
              //     ->where('regis_doc_status',0)
              //     ->where('type',3)
              //     ->orderBy('id','desc')
              //     ->get();
              //     foreach($c_null as $c_null_item){
              //       $c_data = DB::table('register_files')->select('id')
              //       ->where('business_location_id_fk',$business_location_id_fk)
              //       ->where('customer_id',$c->customer_id)
              //       ->where('regis_doc_status',1)
              //       ->where('type',3)
              //       ->orderBy('id','desc')
              //       ->first();
              //       $c_data2 = DB::table('register_files')->select('id','type')
              //       ->where('business_location_id_fk',$business_location_id_fk)
              //       ->where('customer_id',$c->customer_id)
              //       ->where('regis_doc_status',2)
              //       ->where('type',3)
              //       ->orderBy('id','desc')
              //       ->first();
              //       if($c_data){
              //         array_push($arr_not,$c_null_item->id);
              //       }
              //       if($c_data2){
              //         if($c_data2->id > $c_null_item->id){
              //           array_push($arr_not,$c_null_item->id);
              //         }
              //       }
              //     }

              //      // type_4
              //   $c_null = DB::table('register_files')->select('id')
              //   ->where('business_location_id_fk',$business_location_id_fk)
              //   ->where('customer_id',$c->customer_id)
              //   ->where('regis_doc_status',0)
              //   ->where('type',4)
              //   ->orderBy('id','desc')
              //   ->get();
              //   foreach($c_null as $c_null_item){
              //     $c_data = DB::table('register_files')->select('id')
              //     ->where('business_location_id_fk',$business_location_id_fk)
              //     ->where('customer_id',$c->customer_id)
              //     ->where('regis_doc_status',1)
              //     ->where('type',4)
              //     ->orderBy('id','desc')
              //     ->first();
              //     $c_data2 = DB::table('register_files')->select('id','type')
              //     ->where('business_location_id_fk',$business_location_id_fk)
              //     ->where('customer_id',$c->customer_id)
              //     ->where('regis_doc_status',2)
              //     ->where('type',4)
              //     ->orderBy('id','desc')
              //     ->first();
              //     if($c_data){
              //       array_push($arr_not,$c_null_item->id);
              //     }
              //     if($c_data2){
              //       if($c_data2->id > $c_null_item->id){
              //         array_push($arr_not,$c_null_item->id);
              //       }
              //     }
              //   }

              // }

              $customer_doc = DB::table('register_files')->select('id')->where('regis_doc_status',0)
              // ->where('type','!=',4)
              ->where('business_location_id_fk',$business_location_id_fk)
              // ->whereNotIn('id',$arr_not)
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
