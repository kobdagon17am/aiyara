<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;
use PhpParser\Node\Stmt\Return_;

class LineModel extends Model
{

  public static function last_line($username,$line_type){

		$data =array();

      $i =1;
      $j = 2;

      $last_id = DB::table('customers')
      ->select('upline_id','user_name')
      ->where('line_type','=',$line_type)
      ->where('upline_id','=',$username)
      ->first();

      if(empty($last_id)){
        return $username;
      }

      for ($i; $i<$j; $i++) {

        $last_id = DB::table('customers')
        ->select('upline_id','user_name')
		    ->where('line_type','=',$line_type)
        ->where('upline_id','=',$username)
        ->first();

        if($last_id){
          $i = 1;
          $j = 3;
          $username	= $last_id->user_name;
        }else{
          $i = 1;
          $j = 0;
          $last_id = DB::table('customers')
          ->select('upline_id','user_name')
		      ->where('line_type','=',$line_type)
          ->where('user_name','=',$username)
          ->first();

          $last_id = $last_id->upline_id;

        }

      }

    return $username;

  }

	public static function line_all($username,$line_type=''){
		$data =array();


    if (!empty($username) and !empty($line_type)){
      $i =1;
      $j = 2;

      for ($i; $i<$j; $i++) {

        $last_id = DB::table('customers')
        ->select('upline_id','user_name')
		    ->where('line_type','=',$line_type)
        ->where('upline_id','=',$username)
        ->first();

        if($last_id){
          $i = 1;
          $j = 3;
          $username	= $last_id->user_name;
        }else{
          $i = 1;
          $j = 0;
          $last_id = DB::table('customers')
          ->select('upline_id','user_name')
		      ->where('line_type','=',$line_type)
          ->where('user_name','=',$username)
          ->first();

          $last_id = $last_id->upline_id;

        }

      }

    }else{

      $last_id = $username;
    }

		if (empty($last_id)) {
			return null;
		}else{


			$lv1 = DB::table('customers')
			->select('id','user_name','business_name','prefix_name','first_name','last_name','profile_img','upline_id')
			->where('user_name','=',$last_id)
			->first();


			$lv2_a = DB::table('customers')
			->select('id','user_name','business_name','prefix_name','first_name','last_name','profile_img','upline_id')
			->where('upline_id','=',$lv1->user_name)
			->where('line_type','=','A')
			->first();


			if($lv2_a){

				$lv3_a_a = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','first_name','last_name','profile_img','upline_id')
				->where('upline_id','=',$lv2_a->user_name)
				->where('line_type','=','A')
				->first();

				$lv3_a_b = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','first_name','last_name','profile_img','upline_id')
				->where('upline_id','=',$lv2_a->user_name)
				->where('line_type','=','B')
				->first();

				$lv3_a_c = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','first_name','last_name','profile_img','upline_id')
				->where('upline_id','=',$lv2_a->user_name)
				->where('line_type','=','C')
				->first();

			}else{
				$lv2_a = null;
				$lv3_a_a = null;
				$lv3_a_b = null;
				$lv3_a_c = null;
			}


			$lv2_b = DB::table('customers')
			->select('id','user_name','business_name','prefix_name','first_name','last_name','profile_img','upline_id')
			->where('upline_id','=',$lv1->user_name)
			->where('line_type','=','B')
			->first();

			if($lv2_b){

				$lv3_b_a = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','first_name','last_name','profile_img','upline_id')
				->where('upline_id','=',$lv2_b->user_name)
				->where('line_type','=','A')
				->first();


				$lv3_b_b = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','first_name','last_name','profile_img','upline_id')
				->where('upline_id','=',$lv2_b->user_name)
				->where('line_type','=','B')
				->first();

				$lv3_b_c = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','first_name','last_name','profile_img','upline_id')
				->where('upline_id','=',$lv2_b->user_name)
				->where('line_type','=','C')
				->first();

			}else{
				$lv2_b = null;
				$lv3_b_a = null;
				$lv3_b_b = null;
				$lv3_b_c = null;
			}

			$lv2_c = DB::table('customers')
			->select('id','user_name','business_name','prefix_name','first_name','last_name','profile_img','upline_id')
			->where('upline_id','=',$lv1->user_name)
			->where('line_type','=','C')
			->first();

			if($lv2_c){

				$lv3_c_a = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','first_name','last_name','profile_img','upline_id')
				->where('upline_id','=',$lv2_c->user_name)
				->where('line_type','=','A')
				->first();


				$lv3_c_b = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','first_name','last_name','profile_img','upline_id')
				->where('upline_id','=',$lv2_c->user_name)
				->where('line_type','=','B')
				->first();


				$lv3_c_c = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','first_name','last_name','profile_img','upline_id')
				->where('upline_id','=',$lv2_c->user_name)
				->where('line_type','=','C')
				->first();

			}else{
				$lv2_c = null;
				$lv3_c_a = null;
				$lv3_c_b = null;
				$lv3_c_c = null;
			}

			$data = ['lv1'=>$lv1,
			'lv2_a'=>$lv2_a,'lv2_b'=>$lv2_b,'lv2_c'=>$lv2_c,
			'lv3_a_a'=>$lv3_a_a,'lv3_a_b'=>$lv3_a_b,'lv3_a_c'=>$lv3_a_c,
			'lv3_b_a'=>$lv3_b_a,'lv3_b_b'=>$lv3_b_b,'lv3_b_c'=>$lv3_b_c,
			'lv3_c_a'=>$lv3_c_a,'lv3_c_b'=>$lv3_c_b,'lv3_c_c'=>$lv3_c_c
		];

		return $data;
	}

}

public static function check_line($username){

	$data_user = DB::table('customers')
	->select('id','user_name','business_name','prefix_name','first_name','last_name','profile_img','upline_id')
	->where('user_name','=',$username)
	->first();

  if(empty($data_user)){
    $data_user = DB::table('customers')
    ->select('id','user_name','business_name','prefix_name','first_name','last_name','profile_img','upline_id')
    ->orwhere('id_card','=',$username)
    ->first();
  }

	if(!empty($data_user)){

	$user_name = Auth::guard('c_user')->user()->user_name;
    $user_name_c = Auth::guard('c_user')->user()->user_name;//ของผู้เซิท

		if( $data_user->user_name == $user_name){

			$resule = ['status'=>'success','message'=>'My Account','data'=>$data_user];
			return $resule;
		}

		$username = $data_user->upline_id;
		$j = 2;
		for ($i=1; $i <= $j ; $i++){
			if($i == 1){
				$data = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','first_name','last_name','profile_img','upline_id')
				->where('user_name','=',$username)
			//->where('upline_id','=',$use_id)
				->first();
			}

			if($data){
				if($data->user_name == $user_name_c || $data->upline_id == $user_name_c){
					$resule = ['status'=>'success','message'=>'Under line','data'=>$data_user];
					$j =0;

				}elseif($data->upline_id == 'AA'){

					$resule = ['status'=>'fail','message'=>'ไม่พบรหัสสมาชิกดังกล่าวหรือไม่ได้อยู่ในสายงานเดียวกัน'];
					$j =0;
				}else{

					$data = DB::table('customers')
					->select('upline_id','user_name')
					->where('user_name','=',$data->upline_id)
					->first();
					$j = $j+1;
				}

			}else{
				$resule = ['status'=>'fail','message'=>'ไม่พบรหัสสมาชิกดังกล่าวหรือไม่ได้อยู่ในสายงานเดียวกัน'];
				$j =0;
			}
		}
		return $resule;

	}else{
		$resule = ['status'=>'fail','message'=>'ไม่พบข้อมูลผู้ใช้งานรหัสนี้'];
		return $resule;

	}

}

 //check_type_introduce($introduce_id,$under_line_id){
public static function check_type_introduce($introduce_id,$under_line_id){//คนแนะนำ//สร้างภายใต้ id


	$data_user = DB::table('customers')
	->select('upline_id','user_name','upline_id','line_type')
	->where('user_name','=',$under_line_id)
	->first();

	if(!empty($data_user)){
		$upline_id = $data_user->upline_id;
		if($upline_id == $introduce_id){
			$resule = ['status'=>'success','message'=>'My Account','data'=>$data_user];
			return $resule;
		}

		//group 1 หาภายใต้ตัวเอง

		$username = $data_user->upline_id;
		$j = 2;
		for ($i=1; $i <= $j ; $i++){
			if($i == 1){
				$data = DB::table('customers')
        ->select('upline_id','user_name','upline_id','line_type')
				->where('user_name','=',$username)
			//->where('upline_id','=',$use_id)
				->first();


			}

			if($data){
				if($data->user_name == $introduce_id || $data->upline_id == $introduce_id ){
					$resule = ['status'=>'success','message'=>'Under line','data'=>$data];
					$j =0;
					return $resule;

				}elseif($data->upline_id == 'AA'){

					$resule = ['status'=>'fail','message'=>'ไม่พบรหัสสมาชิกดังกล่าวหรือไม่ได้อยู่ในสายงานเดียวกัน'];
					$j =0;
				}else{

					$data = DB::table('customers')
					->select('upline_id','user_name','upline_id','line_type')
					->where('id','=',$data->upline_id)
					->first();

					$j = $j+1;
				}

			}else{
				$resule = ['status'=>'fail','message'=>'ไม่พบรหัสสมาชิกดังกล่าวหรือไม่ได้อยู่ในสายงานเดียวกัน'];
				$j =0;
			}
		}

		//end group 1 หาภายใต้ตัวเอง
		/////////////////////////////////////////////////////////////////////////////////////

		//หาด้านบนตัวเอง
		$upline_id_arr = array();

		if($resule['status'] == 'fail'){

			$data_account = DB::table('customers')
			->select('upline_id','user_name','upline_id','line_type')
			->where('user_name','=',$introduce_id)
			->first();

      if(empty($data_account)){
        	$username = null;
        $j = 0;
      }else{
        	$username = $data_account->upline_id;
        $j = 2;
      }

			for ($i=1; $i <= $j ; $i++){
				if($username == 'AA'){
					$upline_id_arr[] = $data_account->user_name;
					$j =0;
				}else{
					$data_account = DB::table('customers')
					->select('upline_id','user_name','upline_id','line_type')
					->where('user_name','=',$username)
					->first();

          if(empty($data_account)){
            $j = 0;
          }else{
            $upline_id_arr[] = $data_account->user_name;
            $username = $data_account->upline_id;
            $j = $j+1;
          }


				}
			}

			//return $resule;
		}
		if (in_array($introduce_id, $upline_id_arr)) {
			$resule = ['status'=>'success','message'=>'Upline ID ','data'=>$data_account];
		}else{
			$resule = ['status'=>'fail','message'=>'ไม่พบรหัสสมาชิกดังกล่าวหรือไม่ได้อยู่ในสายงานเดียวกัน','data'=>null];

		}
		return $resule;

	}else{
		$resule = ['status'=>'fail','message'=>'ไม่พบข้อมูลผู้ใช้งานรหัสนี้'];
		return $resule;

	}
}

public static function check_line_backend($username_buy,$username_check){

	$data_user =  DB::table('customers')
	->select('customers.id','upline_id','user_name','first_name','last_name','aistockist_status','ai_cash','pv_mt_active','pv_tv_active','pv','dataset_package.dt_package','dataset_qualification.business_qualifications as qualification_name','business_name')
  ->leftjoin('dataset_package','dataset_package.id','=','customers.package_id')
  ->leftjoin('dataset_qualification', 'dataset_qualification.id', '=','customers.qualification_id')
	->where('user_name','=',$username_check)
	->first();

  $resule = ['status'=>'success','message'=>'Under line','data'=>$data_user];
  return $resule;

	if(!empty($data_user)){

		$username = $data_user->user_name;
		//$use_username =Auth::guard('c_user')->user()->user_name;
    $use_username = $username_buy;
		if( $username == $use_username){

			$resule = ['status'=>'success','message'=>'My Account','data'=>$data_user];
			return $resule;
		}

		//group 1 หาภายใต้ตัวเอง

		$username = $data_user->upline_id;
		$j = 2;
		for ($i=1; $i <= $j ; $i++){
			if($i == 1){
				$data = DB::table('customers')
				->select('upline_id','user_name','upline_id','pv_mt_active','pv_tv_active','pv')
				->where('user_name','=',$username)
			//->where('upline_id','=',$use_id)
				->first();
			}

			if($data){
				if($data->user_name == $use_username || $data->upline_id == $use_username || empty($data)){
					$resule = ['status'=>'success','message'=>'Under line','data'=>$data_user];
					$j =0;
					return $resule;

				}elseif($data->upline_id == 'AA' || empty($data)){

					$resule = ['status'=>'fail','message'=>'ไม่พบรหัสสมาชิกดังกล่าวหรือไม่ได้อยู่ในสายงานเดียวกัน'];
					$j =0;
				}else{

					$data = DB::table('customers')
					->select('upline_id','user_name','upline_id','pv_mt_active','pv_tv_active','pv')
					->where('user_name','=',$data->upline_id)
					->first();

					$j = $j+1;
				}

			}else{
				$resule = ['status'=>'fail','message'=>'ไม่พบรหัสสมาชิกดังกล่าวหรือไม่ได้อยู่ในสายงานเดียวกัน'];
				$j =0;
			}
		}

		//end group 1 หาภายใต้ตัวเอง
		/////////////////////////////////////////////////////////////////////////////////////

		//หาด้านบนตัวเอง
		$upline_id_arr = array();

		if($resule['status'] == 'fail'){

			$data_account = DB::table('customers')
			->select('upline_id','user_name','first_name','last_name','ai_cash','pv_mt_active','pv_tv_active','pv')
			->where('user_name','=',$use_username)
			->first();

      if(empty($data_account)){
        $id = null;
        $j = 0;
      }else{
        $id = $data_account->upline_id;
        $j = 2;
      }

			for ($i=1; $i <= $j ; $i++){
				if($id == 'AA'){
					$upline_id_arr[] = $data_account->user_name;
					$j =0;
				}else{
					$data_account = DB::table('customers')
          ->select('upline_id','user_name','first_name','last_name','ai_cash','pv_mt_active','pv_tv_active','pv')
					->where('user_name','=',$id)
					->first();
					$upline_id_arr[] = $data_account->user_name;
					$id = $data_account->upline_id;
					$j = $j+1;
				}
			}

			//return $resule;
		}

		if (in_array($username_check, $upline_id_arr)) {


			$resule = ['status'=>'success','message'=>'Upline ID ','data'=>$data_user];
		}else{
			$resule = ['status'=>'fail','message'=>'ไม่พบรหัสสมาชิกดังกล่าวหรือไม่ได้อยู่ในสายงานเดียวกัน '];

		}
		return $resule;

	}else{
		$resule = ['status'=>'fail','message'=>'ไม่พบข้อมูลผู้ใช้งานรหัสนี้'];
		return $resule;

	}
}


}
