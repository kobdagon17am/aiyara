<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Backend\RequisitionBetweenBranch;
use App\Models\Backend\Orders;

class HomeController extends Controller
{

  // แก้นำสินค้าออกเกิน คืนสินค้าเข้าคลัง
  public function test_general_takeout_remove(Request $request)
  {
    $db_general_takeout = DB::table('db_general_takeout')->select('id','ref_doc','business_location_id_fk','branch_id_fk')->where('id',1012)->first();
    $db_general_takeout_item = DB::table('db_general_takeout_item')->where('general_takeout_id',$db_general_takeout->id)->get();
    DB::beginTransaction();
    try {

      foreach($db_general_takeout_item as $item){

        DB::table('db_stock_movement')->insert([
          'stock_type_id_fk' => 5,
          'stock_id_fk' => $item->stocks_id_fk,
          'ref_table' => 'db_general_takeout',
          'ref_table_id' => $db_general_takeout->id,
          'ref_doc' => $db_general_takeout->ref_doc,
          'doc_date' => $item->created_at,
          'business_location_id_fk' => $db_general_takeout->business_location_id_fk,
          'branch_id_fk' => $db_general_takeout->branch_id_fk,
          'product_id_fk' => $item->product_id_fk,
          'lot_number' => $item->lot_number,
          'lot_expired_date' => $item->lot_expired_date,
          'amt' => $item->amt,
          'in_out' => 1,
          'product_unit_id_fk' => $item->product_unit_id_fk,
          'warehouse_id_fk' => $item->warehouse_id_fk,
          'zone_id_fk' => $item->zone_id_fk,
          'shelf_id_fk' => $item->shelf_id_fk,
          'shelf_floor' => $item->shelf_floor,
          'status' => 1,
          'note' => 'คืนสินค้าจากการยกเลิก',
          'note2' => 'ยกเลิกนำสินค้าออก '.$db_general_takeout->ref_doc,
          'action_user' => 1,
          'action_date' => date('Y-m-d H:i:s'),
          'approver' => 1,
          'approve_date' => date('Y-m-d H:i:s'),
          'sender' => 0,
          'who_cancel' => 0,
          'created_at' => date('Y-m-d H:i:s'),
          'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $db_stocks = DB::table('db_stocks')->select('id','amt')->where('id',$item->stocks_id_fk)->first();
        DB::table('db_stocks')->where('id',$db_stocks->id)->update([
          'amt' => $db_stocks->amt+$item->amt,
        ]);

      }

        DB::commit();

        return 'OK Bro!!';

        } catch (\Exception $e) {
          DB::rollback();
          return $e->getMessage();
        } catch (\FatalThrowableError $fe) {
          DB::rollback();
          return $e->getMessage();
        }
  }

  // จากรับเองเป็นจัดส่ง
  public function test_add_delivery($order_id)
  {

    DB::table('db_orders')->where('id',$order_id)->update([
      'delivery_location' => 4,
      'delivery_location_frontend' => 'sent_address_other',
    ]);
    \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddress($order_id);
    \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddressDefault($order_id);
    return 'ok';
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
