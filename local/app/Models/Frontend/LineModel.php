<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;
class LineModel extends Model
{

	public static function line_all($id=''){
		$data =array();

		if (empty($id)) {
			return null;
		}else{

			$lv1 = DB::table('customers')
			->select('*')
			->where('id','=',$id)
			->first();


			$lv2_a = DB::table('customers')
			->select('*')
			->where('upline_id','=',$lv1->id)
			->where('line_type','=','A')
			->first();



			if($lv2_a){

				$lv3_a_a = DB::table('customers')
				->select('*')
				->where('upline_id','=',$lv2_a->id)
				->where('line_type','=','A')
				->first();

				$lv3_a_b = DB::table('customers')
				->select('*')
				->where('upline_id','=',$lv2_a->id)
				->where('line_type','=','B')
				->first();

				$lv3_a_c = DB::table('customers')
				->select('*')
				->where('upline_id','=',$lv2_a->id)
				->where('line_type','=','C')
				->first();

			}else{
				$lv2_a = null;
				$lv3_a_a = null;
				$lv3_a_b = null;
				$lv3_a_c = null;
			}


			$lv2_b = DB::table('customers')
			->select('*')
			->where('upline_id','=',$lv1->id)
			->where('line_type','=','B')
			->first();

			if($lv2_b){

				$lv3_b_a = DB::table('customers')
				->select('*')
				->where('upline_id','=',$lv2_b->id)
				->where('line_type','=','A')
				->first();


				$lv3_b_b = DB::table('customers')
				->select('*')
				->where('upline_id','=',$lv2_b->id)
				->where('line_type','=','B')
				->first();

				$lv3_b_c = DB::table('customers')
				->select('*')
				->where('upline_id','=',$lv2_b->id)
				->where('line_type','=','C')
				->first();

			}else{
				$lv2_b = null;
				$lv3_b_a = null;
				$lv3_b_b = null;
				$lv3_b_c = null;
			}

			$lv2_c = DB::table('customers')
			->select('*')
			->where('upline_id','=',$lv1->id)
			->where('line_type','=','C')
			->first();

			if($lv2_c){

				$lv3_c_a = DB::table('customers')
				->select('*')
				->where('upline_id','=',$lv2_c->id)
				->where('line_type','=','A')
				->first();


				$lv3_c_b = DB::table('customers')
				->select('*')
				->where('upline_id','=',$lv2_c->id)
				->where('line_type','=','B')
				->first();


				$lv3_c_c = DB::table('customers')
				->select('*')
				->where('upline_id','=',$lv2_c->id)
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

public static function under_a($id=''){

	if (empty($id)) {

		return null;
	}else{
		$j = 2;

		for ($i=1; $i<$j; $i++) {


			$last_id_a = DB::table('customers')
			->select('*')
			->where('upline_id','=',$id)
			->where('line_type','=','A')
			->limit(1)
			->get();

			if(count($last_id_a)>0){
				$j = $j+$i;
				$id	= $last_id_a[0]->id;


			}else{
				$j = 0;

				$last_id_a = DB::table('customers')
				->select('*')
				->where('id','=',$id)
				->where('line_type','=','A')
				->limit(1)
				->get();

				return $last_id_a[0]->upline_id;
			}

		}

	}
}

public static function under_b($id=''){

	if (empty($id)) {

		return null;
	}else{
		$j = 2;

		for ($i=1; $i<$j; $i++) {


			$last_id_a = DB::table('customers')
			->select('*')
			->where('upline_id','=',$id)
			->where('line_type','=','B')
			->limit(1)
			->get();

			if(count($last_id_a)>0){
				$j = $j+$i;
				$id	= $last_id_a[0]->id;


			}else{
				$j = 0;

				$last_id_a = DB::table('customers')
				->select('*')
				->where('id','=',$id)
				->where('line_type','=','B')
				->limit(1)
				->get();

				return $last_id_a[0]->upline_id;
			}

		}

	}
}

public static function under_c($id=''){

	if (empty($id)) {

		return null;
	}else{
		$j = 2;

		for ($i=1; $i<$j; $i++) {


			$last_id_a = DB::table('customers')
			->select('*')
			->where('upline_id','=',$id)
			->where('line_type','=','C')
			->limit(1)
			->get();

			if(count($last_id_a)>0){
				$j = $j+$i;
				$id	= $last_id_a[0]->id;


			}else{
				$j = 0;

				$last_id_a = DB::table('customers')
				->select('*')
				->where('id','=',$id)
				->where('line_type','=','C')
				->limit(1)
				->get();

				return $last_id_a[0]->upline_id;
			}

		}

	}
}

public static function check_line($username){

	$data_user = DB::table('customers')
	->select('*')
	->where('user_name','=',$username)
	->first();

	if(!empty($data_user)){

		$use_id = Auth::guard('c_user')->user()->id;

		if( $data_user->id == $use_id){

			$resule = ['status'=>'success','message'=>'My Account','data'=>$data_user];
			return $resule;
		}

		$id = $data_user->upline_id;
		$j = 2;
		for ($i=1; $i <= $j ; $i++){
			if($i == 1){
				$data = DB::table('customers')
				->select('*')
				->where('id','=',$id)
			//->where('upline_id','=',$use_id)
				->first();
			}

			if($data){

				if($data->id == $use_id || $data->upline_id == $use_id ){
					$resule = ['status'=>'success','message'=>'Under line','data'=>$data_user];
					$j =0;

				}elseif($data->upline_id == 'AA'){

					$resule = ['status'=>'fail','message'=>'No Under line'];
					$j =0;
				}else{

					$data = DB::table('customers')
					->select('*')
					->where('id','=',$data->upline_id)
					->first();

					$j = $j+1;
				}

			}else{
				$resule = ['status'=>'fail','message'=>'No Under line'];
				$j =0;
			}
		}
		return $resule;

	}else{
		$resule = ['status'=>'fail','message'=>'No data'];
		return $resule;

	}

}

 //check_type_introduce($introduce_id,$under_line_id){
public static function check_type_introduce($introduce_id,$under_line_id){//คนแนะนำ//สร้างภายใต้ id

	$data_user = DB::table('customers')
	->select('*')
	->where('id','=',$under_line_id)
	->first();

	if(!empty($data_user)){
		$upline_id = $data_user->upline_id;
		if($upline_id == $introduce_id){
			$resule = ['status'=>'success','message'=>'My Account','data'=>$data_user];
			return $resule;
		}

		//group 1 หาภายใต้ตัวเอง

		$id = $data_user->upline_id;
		$j = 2;
		for ($i=1; $i <= $j ; $i++){
			if($i == 1){
				$data = DB::table('customers')
				->select('*')
				->where('id','=',$id)
			//->where('upline_id','=',$use_id)
				->first();


			}

			if($data){
				if($data->id == $introduce_id || $data->upline_id == $introduce_id ){
					$resule = ['status'=>'success','message'=>'Under line','data'=>$data];
					$j =0;
					return $resule;

				}elseif($data->upline_id == 'AA'){

					$resule = ['status'=>'fail','message'=>'No Under line'];
					$j =0;
				}else{

					$data = DB::table('customers')
					->select('*')
					->where('id','=',$data->upline_id)
					->first();

					$j = $j+1;
				}

			}else{
				$resule = ['status'=>'fail','message'=>'No Under line'];
				$j =0;
			}
		}

		//end group 1 หาภายใต้ตัวเอง
		/////////////////////////////////////////////////////////////////////////////////////

		//หาด้านบนตัวเอง
		$upline_id_arr = array();

		if($resule['status'] == 'fail'){

			$data_account = DB::table('customers')
			->select('*')
			->where('id','=',$introduce_id)
			->first();

			$id = $data_account->upline_id;
			$j = 2;
			for ($i=1; $i <= $j ; $i++){
				if($id == 'AA'){
					$upline_id_arr[] = $data_account->id;
					$j =0;
				}else{
					$data_account = DB::table('customers')
					->select('*')
					->where('id','=',$id)
					->first();
					$upline_id_arr[] = $data_account->id;
					$id = $data_account->upline_id;
					$j = $j+1;
				}
			}

			//return $resule;
		}
		if (in_array($introduce_id, $upline_id_arr)) {
			$resule = ['status'=>'success','message'=>'Upline ID ','data'=>$data_account];
		}else{
			$resule = ['status'=>'fail','message'=>'No Underline and Upline','data'=>null];

		}
		return $resule;

	}else{
		$resule = ['status'=>'fail','message'=>'No data'];
		return $resule;

	}
}

public static function check_line_aipocket($username){

	$data_user =  DB::table('customers')
  ->select('customers.*','dataset_package.dt_package','dataset_qualification.code_name','dataset_qualification.business_qualifications as qualification_name')
  ->leftjoin('dataset_package','dataset_package.id','=','customers.package_id')
  ->leftjoin('dataset_qualification', 'dataset_qualification.id', '=','customers.qualification_id')
	->where('user_name','=',$username)
	->first();

  //dd($data_user);

	if(!empty($data_user)){

		$id_username = $data_user->id;
		$use_id =Auth::guard('c_user')->user()->id;

		if( $id_username == $use_id){

			$resule = ['status'=>'success','message'=>'My Account','data'=>$data_user];
			return $resule;
		}

		//group 1 หาภายใต้ตัวเอง

		$id = $data_user->upline_id;
		$j = 2;
		for ($i=1; $i <= $j ; $i++){
			if($i == 1){
				$data = DB::table('customers')
				->select('*')
				->where('id','=',$id)
			//->where('upline_id','=',$use_id)
				->first();
			}

			if($data){

				if($data->id == $use_id || $data->upline_id == $use_id ){
					$resule = ['status'=>'success','message'=>'Under line','data'=>$data_user];
					$j =0;
					return $resule;

				}elseif($data->upline_id == 'AA'){

					$resule = ['status'=>'fail','message'=>'No Under line'];
					$j =0;
				}else{

					$data = DB::table('customers')
					->select('*')
					->where('id','=',$data->upline_id)
					->first();

					$j = $j+1;
				}

			}else{
				$resule = ['status'=>'fail','message'=>'No Under line'];
				$j =0;
			}
		}

		//end group 1 หาภายใต้ตัวเอง
		/////////////////////////////////////////////////////////////////////////////////////

		//หาด้านบนตัวเอง
		$upline_id_arr = array();

		if($resule['status'] == 'fail'){

			$data_account = DB::table('customers')
			->select('*')
			->where('id','=',$use_id)
			->first();

			$id = $data_account->upline_id;
			$j = 2;
			for ($i=1; $i <= $j ; $i++){
				if($id == 'AA'){
					$upline_id_arr[] = $data_account->id;
					$j =0;
				}else{
					$data_account = DB::table('customers')
					->select('*')
					->where('id','=',$id)
					->first();
					$upline_id_arr[] = $data_account->id;
					$id = $data_account->upline_id;
					$j = $j+1;
				}
			}

			//return $resule;
		}
		if (in_array($id_username, $upline_id_arr)) {
			$resule = ['status'=>'success','message'=>'Upline ID ','data'=>$data_account];
		}else{
			$resule = ['status'=>'fail','message'=>'No Underline and Upline '];

		}
		return $resule;

	}else{
		$resule = ['status'=>'fail','message'=>'No data'];
		return $resule;

	}
}


}
