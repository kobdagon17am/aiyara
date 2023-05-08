<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Frontend\Register;
use App\Models\Frontend\LineModel;
use Auth;

class RegisterController extends Controller
{


  // public function __construct()
  // {
  //   $this->middleware('customer');
  // }

  public function index($user_name,$line_type){

      if(empty($user_name) || empty($line_type)){
        return redirect('home')->withError('กรุณาเลือกตำแหน่งที่ต้องการ Add User');

      }else{
       $resule = DB::table('customers')
       ->select('*')
       ->where('user_name','=',$user_name)
       ->first();

       $provinces = DB::table('dataset_provinces')
       ->select('*')
       ->get();

       $nation_id = DB::table('db_country')
       ->select('*')
       ->get();

       if($resule->business_location_id == '1' || $resule->business_location_id == null ){
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





       $country = DB::table('dataset_business_location')
       ->select('*')
       ->where('lang_id','=',1)
       ->get();

       $data = ['data'=>$resule,'line_type_back'=>$line_type,'provinces'=>$provinces,'business_location'=>$business_location,'country'=>$country,'nation'=>$nation_id];

       return view('frontend/newregister',compact('data'));

     }

   }

   public function location(Request $request){

    if (isset($request->function) && $request->function == 'provinces') {
      $id = $request->id;

      $hasOldAmphures = isset($request->amphuresId) ? $request->amphuresId : '';

      $amphures = DB::table('dataset_amphures')
      ->select('*')
      ->where('province_id','=',$id)
      ->get();
      $data_amphures = '';
      $data_amphures .= '<option value="" > กรุณาเลือกอำเภอ </option>';
      foreach ($amphures as $amphures_value) {
        $selected = $hasOldAmphures && $amphures_value->id == $hasOldAmphures ? 'selected' : '';

        $data_amphures .= '<option value="'.$amphures_value->id.'" '.$selected.'>'.$amphures_value->name_th.'</option>';
      }
      return $data_amphures;
    }



    if (isset($request->function) && $request->function == 'amphures') {
      $id = $request->id;
      $hasOldDistrict = isset($request->districtId) ? $request->districtId : '';

      $district = DB::table('dataset_districts')
      ->select('*')
      ->where('amphure_id','=',$id)
      ->get();
      $data_district = '';
      $data_district .= '<option value="" > กรุณาเลือกตำบล </option>';

      foreach ($district as $district_value) {
        $selected = $hasOldDistrict && $district_value->id == $hasOldDistrict ? 'selected' : '';
        $data_district .= '<option value="'.$district_value->id.'" '.$selected.'>'.$district_value->name_th.'</option>';
      }
      return $data_district;
    }


    if (isset($request->function) && $request->function == 'district') {
      $id = $request->id;
      $district = DB::table('dataset_districts')
      ->select('*')
      ->where('id','=',$id)
      ->first();
      return $district->zip_code;
    }

  }

  public function check_user(Request $request){
    // $data =DB::table('customers')
    // ->where('user_name','=',$request->user_name)
    // ->first();
    $user_name = Auth::guard('c_user')->user()->user_name;
    $data = LineModel::check_line_backend($user_name,$request->user_name);

    if($data){
      $resule = ['status'=>'success','data'=>$data];
      return $resule;
    }else{
      $resule = ['status'=>'fail','data'=>$data];
      return $resule;
    }
  }

  public function register_new_member(Request $req){

    if(empty($req->introduce)){
      $data = Register::register($req);

      //add data

      if($data['status'] == 'success'){

        //return redirect('home')->withInput()->withSuccess($data['massage']);
        return view('frontend/register_success',compact('data'));

      }else{
        if($data['eror'] == 1){

          return redirect('tree_view');

        }else{
          return redirect('register/'.$req->upline_id.'/'.$req->line_type_back)->withInput()->withError($data['massage']);
        }

      }

    }else{

      $introduce_id = DB::table('customers')
      ->where('user_name','=',$req->introduce)
      ->first();

      if($introduce_id){
        //add data and introduce_id
        $data = Register::register($req,$req->introduce);

        if($data['status'] == 'success'){
          return view('frontend/register_success',compact('data'));
          //return redirect('home')->withInput()->withSuccess($data['massage']);
        }else{
          return redirect('register/'.$req->upline_id.'/'.$req->line_type_back)->withInput()->withError($data['massage']);
        }

      }else{
        return redirect('register/'.$req->upline_id.'/'.$req->line_type_back)->withInput()->withError('ไม่มีข้อมูลรหัสผู้แนะนำในระบบ');
      }

    }

  }

  public static function check_id_card(Request $rs){
    if($rs->id_card){
      $resule = DB::table('customers')
      ->select('id')
      ->where('id_card','=',$rs->id_card)
      ->first();
      if($resule){
        $rs = ['status'=>'fail'];
      }else{
        $rs = ['status'=>'success'];
      }

    }else{
      $rs = ['status'=>'fail'];
    }
		return $rs;
	}


}
