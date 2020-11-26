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

			$lv1 = DB::table('Customers')
			->select('*')
			->where('id','=',$id)
			->limit(1)
			->get();

			$lv1 = $lv1[0];

			$lv2_a = DB::table('Customers')
			->select('*')
			->where('upline_id','=',$lv1->id)
			->where('line_type','=','A')
			->limit(1)
			->get();

			if(count($lv2_a) > 0){

				$lv3_a_a = DB::table('Customers')
				->select('*')
				->where('upline_id','=',$lv2_a[0]->id)
				->where('line_type','=','A')
				->limit(1)
				->get();
				if(count($lv3_a_a)>0){
					$lv3_a_a = $lv3_a_a[0];
				}else{
					$lv3_a_a = null;

				}

				$lv3_a_b = DB::table('Customers')
				->select('*')
				->where('upline_id','=',$lv2_a[0]->id)
				->where('line_type','=','B')
				->limit(1)
				->get();
				if(count($lv3_a_b)>0){
					$lv3_a_b = $lv3_a_b[0];
				}else{
					$lv3_a_b = null;
				}


				$lv3_a_c = DB::table('Customers')
				->select('*')
				->where('upline_id','=',$lv2_a[0]->id)
				->where('line_type','=','C')
				->limit(1)
				->get();
				if(count($lv3_a_c)>0){
					$lv3_a_c = $lv3_a_c[0];
				}else{
					$lv3_a_c = null;
				}

			}else{
				$lv2_a = null;
				$lv3_a_a = null;
				$lv3_a_b = null;
				$lv3_a_c = null;
			}



			$lv2_b = DB::table('Customers')
			->select('*')
			->where('upline_id','=',$lv1->id)
			->where('line_type','=','B')
			->get();

			if(count($lv2_b) > 0){

				$lv3_b_a = DB::table('Customers')
				->select('*')
				->where('upline_id','=',$lv2_b[0]->id)
				->where('line_type','=','A')
				->limit(1)
				->get();

				if(count($lv3_b_a)>0){
					$lv3_b_a = $lv3_b_a[0];
				}else{
					$lv3_b_a = null;

				}

				$lv3_b_b = DB::table('Customers')
				->select('*')
				->where('upline_id','=',$lv2_b[0]->id)
				->where('line_type','=','B')
				->limit(1)
				->get();
				if(count($lv3_b_b)>0){
					$lv3_b_b = $lv3_b_b[0];
				}else{
					$lv3_b_b = null;
				}


				$lv3_b_c = DB::table('Customers')
				->select('*')
				->where('upline_id','=',$lv2_b[0]->id)
				->where('line_type','=','C')
				->limit(1)
				->get();
				if(count($lv3_b_c)>0){
					$lv3_b_c = $lv3_b_c[0];
				}else{
					$lv3_b_c = null;
				}

			}else{
				$lv2_b = null;
				$lv3_b_a = null;
				$lv3_b_b = null;
				$lv3_b_c = null;
			}


			$lv2_c = DB::table('Customers')
			->select('*')
			->where('upline_id','=',$lv1->id)
			->where('line_type','=','C')
			->get();

			

			if(count($lv2_c) > 0){

				$lv3_c_a = DB::table('Customers')
				->select('*')
				->where('upline_id','=',$lv2_c[0]->id)
				->where('line_type','=','A')
				->limit(1)
				->get();
				if(count($lv3_c_a)>0){
					$lv3_c_a = $lv3_c_a[0];
				}else{
					$lv3_c_a = null;

				}

				$lv3_c_b = DB::table('Customers')
				->select('*')
				->where('upline_id','=',$lv2_c[0]->id)
				->where('line_type','=','B')
				->limit(1)
				->get();
				if(count($lv3_c_b)>0){
					$lv3_c_b = $lv3_c_b[0];
				}else{
					$lv3_c_b = null;
				}


				$lv3_c_c = DB::table('Customers')
				->select('*')
				->where('upline_id','=',$lv2_c[0]->id)
				->where('line_type','=','C')
				->limit(1)
				->get();
				if(count($lv3_c_c)>0){
					$lv3_c_c = $lv3_c_c[0];
				}else{
					$lv3_c_c = null;
				}

			}else{
				$lv2_c = null;
				$lv3_c_a = null;
				$lv3_c_b = null;
				$lv3_c_c = null;
			}

			$data = ['lv1'=>$lv1,
			'lv2_a'=>$lv2_a[0],'lv2_b'=>$lv2_b[0],'lv2_c'=>$lv2_c[0],
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


			$last_id_a = DB::table('Customers')
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

				$last_id_a = DB::table('Customers')
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


			$last_id_a = DB::table('Customers')
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

				$last_id_a = DB::table('Customers')
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


			$last_id_a = DB::table('Customers')
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

				$last_id_a = DB::table('Customers')
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

	$data_user = DB::table('Customers')
	->select('*')
	->where('user_name','=',$username)
	->first();


	if(!empty($data_user)){

		$use_id =Auth::guard('c_user')->user()->id;
		
		if( $data_user->id == $use_id){

			$resule = ['status'=>'success','message'=>'My Account','data'=>$data_user];	
			return $resule;
		}

		$id = $data_user->upline_id;
		$j = 2;
		for ($i=1; $i <= $j ; $i++){ 
			if($i == 1){
				$data = DB::table('Customers')
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

					$data = DB::table('Customers')
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


}


?>