<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Backend\RequisitionBetweenBranch;

class HomeController extends Controller
{

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
              $customer_doc = DB::table('register_files')->select('id')->where('regis_doc_status',0)
              // ->where('type','!=',4)
              ->groupBy('customer_id')->get();
            }else{
              $customer_doc = DB::table('register_files')->select('id')->where('regis_doc_status',0)
              // ->where('type','!=',4)
              ->where('business_location_id_fk',$business_location_id_fk)
              ->groupBy('customer_id')->get();
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
