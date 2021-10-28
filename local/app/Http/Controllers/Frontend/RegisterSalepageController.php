<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
 use Illuminate\Http\Request;
use DB;

class RegisterSalepageController extends Controller
{

  public function index($user_name){


      if(empty($user_name)){
        return redirect('home')->withError('กรุณาเลือกตำแหน่งที่ต้องการ Add User');

      }else{
       $resule = DB::table('customers')
       ->select('*')
       ->where('user_name','=',$user_name)
       ->limit(1)
       ->first();

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

       $data = ['data'=>$resule,'line_type_back'=>$resule->registers_setting,'provinces'=>$provinces,'business_location'=>$business_location,'country'=>$country];

       return view('frontend/salepage/registers',compact('data'));

     }

   }
}

