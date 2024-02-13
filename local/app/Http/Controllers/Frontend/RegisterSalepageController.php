<?php
namespace App\Http\Controllers\Frontend;
use DB;
use Illuminate\Http\Request;
use App\Services\AddressService;
use App\Models\Frontend\Register;
use App\Models\Frontend\LineModel;
use App\Http\Controllers\Controller;

class RegisterSalepageController extends Controller
{

  public function index($user_name,$line_setting){

    if($line_setting == 'A' || $line_setting == 'B' || $line_setting == 'C'){
      $customer = DB::table('customers')
      ->select('*')
      ->where('user_name','=',$user_name)
      ->limit(1)
      ->first();

      if(empty($customer)){

       $data = ['status' => 'fail', 'message' => 'ไม่มีข้อมูลผู้แนะนำกรุณาติดต่อ AIYARA'];
       return view('frontend/salepage/fail',compact('data'));
      }

      $provinces = DB::table('dataset_provinces')
      ->select('*')
      ->get();

      $nation_id = DB::table('db_country')
      ->select('*')
      ->get();

      $country = DB::table('dataset_business_location')
      ->select('*')
      ->where('lang_id','=',1)
      ->orderby('orderby')
      ->get();

      $registers_setting = DB::table('customers')
      ->select('user_name','registers_setting')
      ->where('user_name','=',$user_name)
      ->first();

      if(empty($registers_setting)){
        $data = ['status' => 'fail', 'message' => 'ไม่มีข้อมูลผู้แนะนำกรุณาติดต่อ AIYARA'];
        return view('frontend/salepage/fail',compact('data'));
      }


      $user_registers = $registers_setting->user_name;
      //$line_setting = $registers_setting->registers_setting;

      $last_user = LineModel::last_line($user_registers,$line_setting);





      if($customer->business_location_id == '1' || $customer->business_location_id == null ){
       $business_location_id = 1;
      }else{
       $business_location_id = 3;

      }



      $business_location = DB::table('dataset_business_location')
      ->select('*')

      ->where('lang_id','=',1)
      ->where('status','=',1)
      ->orderByRaw("FIELD(id, $business_location_id) desc")
      ->get();

      $data = ['data'=>$customer,'line_type_back'=>$line_setting,'provinces'=>$provinces,'business_location'=>$business_location,'country'=>$country,'nation_id'=>$nation_id,'last_user'=>$last_user];
     //  dd($data);

      return view('frontend/salepage/registers',compact('data'));

    }else{
      $data = ['status' => 'fail', 'message' => 'สายงานผิดกรุณาตรวจสอบ URL การสมัคร'];
      return view('frontend/salepage/fail',compact('data'));

    }




   }


   public function registermember_fail(){
    return view('frontend/salepage/fail');
   }


   public function register_member_salepage(Request $req){

    $customer = DB::table('customers')
    ->select('user_name','registers_setting')
    ->where('user_name','=',$req->upline_id)
    ->first();

    if(empty($customer)){
      $data = ['status' => 'fail', 'message' => 'ไม่มีข้อมูลผู้แนะนำกรุณาติดต่อ AIYARA'];
      return view('frontend/salepage/fail',compact('data'));
    }


    $user_name = $customer->user_name;
    $line_type = $customer->registers_setting;

    $rs = LineModel::last_line($user_name,$line_type);

    if(empty($rs)){
      $data = ['status' => 'fail', 'message' => 'ไม่มีข้อมูลผู้แนะนำกรุณาติดต่อ AIYARA'];
      return view('frontend/salepage/fail',compact('data'));
    }

    $req['upline_id'] = $rs;
    $req['introduce'] = $user_name;

    $data = Register::register($req,$req['introduce']);

      if($data['status'] == 'success'){
        //return redirect('home')->withInput()->withSuccess($data['massage']);
        return view('frontend/salepage/success',compact('data'));

      }else{
        return view('frontend/salepage/fail',compact('data'));
      }
   }

   public function getLocation(Request $request)
   {

      $addressService = new AddressService($request->business_location);

      return $addressService->query($request->input('query'), $request->input('id'))->renderOptions();
   }




}

