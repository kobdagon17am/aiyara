<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class EditPasswordAicashController extends Controller
{
  public function __construct()
  {
    $this->middleware('customer');
  }

	public function index()
	{
    $id = Auth::guard('c_user')->user()->id;
    $aicash_data = DB::table('customers_aicash_password')
                   ->where('customer_id_fk','=',$id)
                   ->first();

		return view('frontend/edit_password_aicash',compact('aicash_data'));
	}

	public function edit_password_aicash_submit(Request $rs)
	{
    $id = Auth::guard('c_user')->user()->id;
    $aicash_data = DB::table('customers_aicash_password')
                   ->where('customer_id_fk','=',$id)
                   ->first();
    $aicash_password = $aicash_data->aicash_password;

		//$old_password = md5($rs->old_password);
		$confirm_edit_password = md5($rs->confirm_edit_password);

		//if($old_password != $aicash_password){
	  //return redirect('chage_password_aicash')->withError('Old password is incorrect');
		//}else{
			try {
				$update_pass = DB::table('customers_aicash_password')
				->where('customer_id_fk', Auth::guard('c_user')->user()->id)
				->update(['aicash_password' => $confirm_edit_password]);
				return redirect('chage_password_aicash')->withSuccess('Edit Password Success');

			} catch (Exception $e) {
				return redirect('chage_password_aicash')->withError('Edit Password Fail');

			}

		//}

	}

  public function add_password_aicash_submit(Request $rs){
    $add_password = md5($rs->add_password);
    DB::table('customers_aicash_password')->insert([
      'customer_id_fk' => Auth::guard('c_user')->user()->id,
      'aicash_password' => $add_password]);
      return redirect('chage_password_aicash')->withSuccess('Add Password Ai-Cash Success');
  }


  public function check_pass_aicash(Request $rs){

      $id = Auth::guard('c_user')->user()->id;


      if(empty($rs->password_aicash)){
        $data=['status'=>'fail','message'=>'Password Ai-Cash is Null'];
        return $data;

      }
      $password_aicash = md5($rs->password_aicash);


      $aicash_data = DB::table('customers_aicash_password')
                     ->where('customer_id_fk','=',$id)
                     ->where('aicash_password','=',$password_aicash)
                     ->first();

      if($aicash_data){
        $data=['status'=>'success','message'=>'Password Ai-Cash is Success'];
        return $data;
      }else{
        $data=['status'=>'fail','message'=>'Password Ai-Cash is incorrect'];
        return $data;
      }

  }






}
