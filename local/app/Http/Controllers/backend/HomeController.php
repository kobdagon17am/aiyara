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
      // return view('backend.index');
      // return view('backend.banner.index');
      return view('backend.index')->with([
        'wait_approve_requisition' => $wait_approve_requisition
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
