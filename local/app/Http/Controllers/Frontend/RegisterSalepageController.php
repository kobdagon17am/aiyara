<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Frontend\Register;
use DB;
use App\Models\Frontend\LineModel;
class RegisterSalepageController extends Controller
{

  public function index($user_name){


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

       $country = DB::table('db_country')
       ->select('*')
       ->get();

       $business_location = DB::table('dataset_business_location')
       ->select('*')
       ->where('lang_id','=',1)
       ->where('status','=',1)
       ->get();

       $data = ['data'=>$customer,'line_type_back'=>$customer->registers_setting,'provinces'=>$provinces,'business_location'=>$business_location,'country'=>$country];

       return view('frontend/salepage/registers',compact('data'));



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






}

