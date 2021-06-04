<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class SalepageController extends Controller
{
	public function aimmura($user_name=''){

			$data = DB::table('customers')
			->select('db_salepage_setting.*','customers_detail.tel_mobile')
			->leftjoin('db_salepage_setting','customers.id', '=', 'db_salepage_setting.customers_id_fk')
			->leftjoin('customers_detail','customers_detail.customer_id', '=', 'customers.id')
			->where('customers.user_name','=',$user_name)
			->first();
			$rs = ['stattus'=>'success','data'=>$data];
		return view('frontend/salepage/aimmura',compact('rs'));
	}

  public function cashewy($user_name=''){

    $data = DB::table('customers')
    ->select('db_salepage_setting.*','customers_detail.tel_mobile')
    ->leftjoin('db_salepage_setting','customers.id', '=', 'db_salepage_setting.customers_id_fk')
    ->leftjoin('customers_detail','customers_detail.customer_id', '=', 'customers.id')
    ->where('customers.user_name','=',$user_name)
    ->first();
    $rs = ['stattus'=>'success','data'=>$data];
  return view('frontend/salepage/cashewy_drink',compact('rs'));
}

public function alada($user_name=''){

  $data = DB::table('customers')
  ->select('db_salepage_setting.*','customers_detail.tel_mobile')
  ->leftjoin('db_salepage_setting','customers.id', '=', 'db_salepage_setting.customers_id_fk')
  ->leftjoin('customers_detail','customers_detail.customer_id', '=', 'customers.id')
  ->where('customers.user_name','=',$user_name)
  ->first();
  $rs = ['stattus'=>'success','data'=>$data];
return view('frontend/salepage/alada',compact('rs'));
}

public function trimmax($user_name=''){

  $data = DB::table('customers')
  ->select('db_salepage_setting.*','customers_detail.tel_mobile')
  ->leftjoin('db_salepage_setting','customers.id', '=', 'db_salepage_setting.customers_id_fk')
  ->leftjoin('customers_detail','customers_detail.customer_id', '=', 'customers.id')
  ->where('customers.user_name','=',$user_name)
  ->first();
  $rs = ['stattus'=>'success','data'=>$data];
return view('frontend/salepage/trimmax',compact('rs'));
}

public function aiyara($user_name=''){

  $data = DB::table('customers')
  ->select('db_salepage_setting.*','customers_detail.tel_mobile')
  ->leftjoin('db_salepage_setting','customers.id', '=', 'db_salepage_setting.customers_id_fk')
  ->leftjoin('customers_detail','customers_detail.customer_id', '=', 'customers.id')
  ->where('customers.user_name','=',$user_name)
  ->first();
  $rs = ['stattus'=>'success','data'=>$data];
return view('frontend/salepage/aiyara',compact('rs'));
}



	public static function setting(){

		$customer_id =  Auth::guard('c_user')->user()->id;

		$data = DB::table('db_salepage_setting')
		->where('customers_id_fk','=',$customer_id)
		->first();

		return view('frontend/salepage/setting',compact('data'));
	}

	public static function save_contact(Request $req){
		$customer_id =  Auth::guard('c_user')->user()->id;
		try {

			$count_data = DB::table('db_salepage_setting')
			->where('customers_id_fk','=',$customer_id)
			->count();

			if($count_data > 0){
				$update_salepage_setting = DB::table('db_salepage_setting')//update บิล
				->where('customers_id_fk',$customer_id)
				->update(['url_fb'=>$req->fb,'url_ig'=>$req->ig,'url_line'=>$req->line,'tel_number'=>$req->tel_number]);

			}else{

				DB::table('db_salepage_setting')->insert([
					'customers_id_fk'=>$customer_id,
					'url_fb'=>$req->fb,
					'url_ig'=>$req->ig,
					'url_line'=>$req->line,
					'tel_number'=>$req->tel_number,
				]);
			}

			$resule = ['status'=>'success','message'=>'Save Contact Success'];

		}catch(Exception $e) {

			$resule = ['status'=>'fail','message'=>$e];

		}

		return $resule;
	}

		public static function save_js(Request $req){

			$js_page_1= (trim($req->js_page_1) == '') ? null : $req->js_page_1;
			$js_page_2= (trim($req->js_page_2) == '') ? null : $req->js_page_2;
			$js_page_3= (trim($req->js_page_3) == '') ? null : $req->js_page_3;
			$js_page_4= (trim($req->js_page_4) == '') ? null : $req->js_page_4;

		$customer_id =  Auth::guard('c_user')->user()->id;
		try {

			$count_data = DB::table('db_salepage_setting')
			->where('customers_id_fk','=',$customer_id)
			->count();

			if($count_data > 0){
				$update_salepage_setting = DB::table('db_salepage_setting')//update บิล
				->where('customers_id_fk',$customer_id)
				->update(['js_page_1'=>$req->js_page_1,'js_page_2'=>$req->js_page_2,'js_page_3'=>$req->js_page_3,'js_page_4'=>$req->js_page_4]);

			}else{

				DB::table('db_salepage_setting')->insert([
					'customers_id_fk'=>$req->customer_id,
					'js_page_1'=>$req->js_page_1,
					'js_page_2'=>$req->js_page_2,
					'js_page_3'=>$req->js_page_3,
					'js_page_4'=>$req->js_page_4,
				]);
			}

			$resule = ['status'=>'success','message'=>'Save JS Success'];

		}catch(Exception $e) {

			$resule = ['status'=>'fail','message'=>$e];

		}

		return $resule;
	}



}
