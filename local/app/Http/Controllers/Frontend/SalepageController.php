<?php

namespace App\Http\Controllers\Frontend;
use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class SalepageController extends Controller
{
	public function aimmura($user_name='',$type=''){
    return redirect('salepage/warring');
			$data = DB::table('customers')
      ->select('db_salepage_setting.*','customers_detail.tel_mobile','customers.user_name','customers.first_name',
  'customers.last_name','customers.business_name','customers.profile_img','customers.email')
			->leftjoin('db_salepage_setting','customers.id', '=', 'db_salepage_setting.customers_id_fk')
			->leftjoin('customers_detail','customers_detail.customer_id', '=', 'customers.id')
			->where('customers.user_name','=',$user_name)
      ->orwhere('db_salepage_setting.name_s2','=',$user_name)
      ->first();
      if($data){
        $rs = ['stattus'=>'success','data'=>$data];
        return view('frontend/salepage/aimmura',compact('rs'));
      }else{
        return view('errors/404');
      }
	}

  public function cashewy($user_name=''){
    return redirect('salepage/warring');

    $data = DB::table('customers')
    ->select('db_salepage_setting.*','customers_detail.tel_mobile','customers.user_name','customers.first_name',
    'customers.last_name','customers.business_name','customers.profile_img','customers.email')
    ->leftjoin('db_salepage_setting','customers.id', '=', 'db_salepage_setting.customers_id_fk')
    ->leftjoin('customers_detail','customers_detail.customer_id', '=', 'customers.id')
    ->where('customers.user_name','=',$user_name)
    ->orwhere('db_salepage_setting.name_s3','=',$user_name)
    ->first();
    if($data){
      $rs = ['stattus'=>'success','data'=>$data];
      return view('frontend/salepage/cashewy_drink',compact('rs'));
    }else{
      return view('errors/404');

    }


}

public function aifacad($user_name=''){
  return redirect('salepage/warring');

    $data = DB::table('customers')
    ->select('db_salepage_setting.*','customers_detail.tel_mobile','customers.user_name','customers.first_name',
  'customers.last_name','customers.business_name','customers.profile_img','customers.email')
    ->leftjoin('db_salepage_setting','customers.id', '=', 'db_salepage_setting.customers_id_fk')
    ->leftjoin('customers_detail','customers_detail.customer_id', '=', 'customers.id')
    ->where('customers.user_name','=',$user_name)
    ->orwhere('db_salepage_setting.name_s4','=',$user_name)
    ->first();
    if($data){
      $rs = ['stattus'=>'success','data'=>$data];
      return view('frontend/salepage/aifacad',compact('rs'));
    }else{
      return view('errors/404');

    }


}



public function ailada($user_name=''){
  return redirect('salepage/warring');

  $data = DB::table('customers')
  ->select('db_salepage_setting.*','customers_detail.tel_mobile','customers.user_name','customers.first_name',
  'customers.last_name','customers.business_name','customers.profile_img','customers.email')
  ->leftjoin('db_salepage_setting','customers.id', '=', 'db_salepage_setting.customers_id_fk')
  ->leftjoin('customers_detail','customers_detail.customer_id', '=', 'customers.id')
  ->where('customers.user_name','=',$user_name)
  ->orwhere('db_salepage_setting.name_s5','=',$user_name)
  ->first();
  if($data){
    $rs = ['stattus'=>'success','data'=>$data];
    return view('frontend/salepage/ailada',compact('rs'));
  }else{
    return view('errors/404');

  }

}

public function trimmax($user_name=''){
  return redirect('salepage/warring');

  $data = DB::table('customers')
  ->select('db_salepage_setting.*','customers_detail.tel_mobile','customers.user_name','customers.first_name',
  'customers.last_name','customers.business_name','customers.profile_img','customers.email')
  ->leftjoin('db_salepage_setting','customers.id', '=', 'db_salepage_setting.customers_id_fk')
  ->leftjoin('customers_detail','customers_detail.customer_id', '=', 'customers.id')
  ->where('customers.user_name','=',$user_name)
  ->orwhere('db_salepage_setting.name_s6','=',$user_name)
  ->first();
  if($data){
    $rs = ['stattus'=>'success','data'=>$data];
    return view('frontend/salepage/trimmax',compact('rs'));
  }else{
    return view('errors/404');

  }

}

public function aiyara($user_name=''){
  return redirect('salepage/warring');


  $data = DB::table('customers')
  ->select('db_salepage_setting.*','customers_detail.tel_mobile','customers.user_name','customers.first_name',
  'customers.last_name','customers.business_name','customers.profile_img','customers.email')
  ->leftjoin('db_salepage_setting','customers.id', '=', 'db_salepage_setting.customers_id_fk')
  ->leftjoin('customers_detail','customers_detail.customer_id', '=', 'customers.id')
  ->where('customers.user_name','=',$user_name)
  ->orwhere('db_salepage_setting.name_s1','=',$user_name)
  ->first();
  if($data){
    $rs = ['stattus'=>'success','data'=>$data];
    return view('frontend/salepage/aiyara',compact('rs'));
  }else{
    return view('errors/404');

  }

}

	public static function setting(){
    return redirect('salepage/warring');

		$customer_id =  Auth::guard('c_user')->user();

		$data = DB::table('db_salepage_setting')
		->where('customers_id_fk','=',$customer_id->id)
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


  public static function save_type_register(Request $req){
		$user_name =  Auth::guard('c_user')->user()->user_name;
		try {

				$update_type = DB::table('customers')//update บิล
				->where('user_name',$user_name)
				->update(['registers_setting'=>$req->type]);

			$resule = ['status'=>'success','message'=>'Save Type Register Success'];

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
      $js_page_5= (trim($req->js_page_5) == '') ? null : $req->js_page_5;
			$js_page_6= (trim($req->js_page_6) == '') ? null : $req->js_page_6;

		$customer_id =  Auth::guard('c_user')->user()->id;
		try {

			$count_data = DB::table('db_salepage_setting')
			->where('customers_id_fk','=',$customer_id)
			->count();

			if($count_data > 0){
				$update_salepage_setting = DB::table('db_salepage_setting')//update บิล
				->where('customers_id_fk',$customer_id)
				->update(['js_page_1'=>$req->js_page_1,'js_page_2'=>$req->js_page_2,
        'js_page_3'=>$req->js_page_3,'js_page_4'=>$req->js_page_4,'js_page_5'=>$req->js_page_5,'js_page_6'=>$req->js_page_6]);

			}else{

				DB::table('db_salepage_setting')->insert([
					'customers_id_fk'=>$req->customer_id,
					'js_page_1'=>$req->js_page_1,
					'js_page_2'=>$req->js_page_2,
					'js_page_3'=>$req->js_page_3,
					'js_page_4'=>$req->js_page_4,
          'js_page_3'=>$req->js_page_5,
					'js_page_4'=>$req->js_page_6,
				]);
			}

			$resule = ['status'=>'success','message'=>'Save JS Success'];

		}catch(Exception $e) {

			$resule = ['status'=>'fail','message'=>$e];

		}

		return $resule;
	}

	public function saveUrl(Request $request)
	{

		$key = collect($request->except('_token'))->keys()->first();
		$rules = [
			'max:20',
			Rule::unique('db_salepage_setting', $key)
		];

		$validate = collect($request->except('_token'))->map(function () use ($rules) {
			return $rules;
		})->toArray();

		$this->validate($request, $validate, ['unique' => "{$request->$key} has already been taken."]);

		try {

			DB::table('db_salepage_setting')
				->updateOrInsert([
					'customers_id_fk' => auth('c_user')->id()
				], $request->except('_token'));

			return response()->json([
				'success' => true,
			]);

		} catch (\Exception $e) {
			\Log::info('>>>> Error: SalepageController@saveUrl <<<<');
			\Log::info($e->getMessage());
		}
	}


}
