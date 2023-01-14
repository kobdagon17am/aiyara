<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class EditPasswordController extends Controller
{
	public function index()
	{

		return view('frontend/edit_password');
	}

	public function edit_password_submit(Request $rs)
	{
		$user_password = Auth::guard('c_user')->user()->password;
		// $old_password = md5($rs->old_password);
		$confirm_edit_password = md5($rs->confirm_edit_password);

		// if($old_password != $user_password){
		// 	return redirect('chage_password')->withError('Old password is incorrect');
		// }else{
			// try {
				$update_pass = DB::table('customers')
				->where('id', Auth::guard('c_user')->user()->id)
				->update(['password' => $confirm_edit_password]);

				return redirect('chage_password')->withSuccess('Edit Password Success');

			// } catch (Exception $e) {
			// 	return redirect('chage_password')->withError($e);

			// }



		// }

	}



}
