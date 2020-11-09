<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
//use App\Http\Controllers\Session;
class RegisterController extends Controller
{


  public function __construct()
  {
    $this->middleware('customer');
  }

  public function index($id,$line_type){
    if(empty($id) || empty($line_type)){
      dd('เลื่อก Upline ไม่ถูกต้อง');

    }else{

    	$customer = DB::table('Customers')
    	->select('*')
    	->where('id','=',$id)
    	->limit(1)
    	->get();

    	$data = ['user_name'=>$customer[0]->user_name,'line_type'=>$line_type,'line_id'=>$id];

      return view('frontend/newregister',compact('data'));

    }

  }
  
  public function register_new_member(Request $req){
  	$id =DB::table('Customers')
  	->select('id')
  	->orderby('id','DESC')
  	->limit('1')
  	->get();
  	$new_id = $id[0]->id+1;
    $username ="000".$new_id;
    $pass=md5($username);


    $file_1 = $req->file_1;
    if(isset($file_1)){
            // $f_name = $file_1->getClientOriginalName().'_'.date('YmdHis').'.'.$file_1->getClientOriginalExtension();
      $f_name = $new_id.'_1'.date('YmdHis').'.'.$file_1->getClientOriginalExtension();
      $file_1->move(public_path().'/files_register/', $f_name);
            //$db = $f_name;
    }
    $file_2 = $req->file_2;
    if(isset($file_2)){
            // $f_name = $file_2->getClientOriginalName().'_'.date('YmdHis').'.'.$file_2->getClientOriginalExtension();
      $f_name = $new_id.'_2'.date('YmdHis').'.'.$file_2->getClientOriginalExtension();
      $file_2->move(public_path().'/files_register/', $f_name);
            //$db = $f_name;
    }

     $file_3 = $req->file_3;
    if(isset($file_3)){
            // $f_name = $file_3->getClientOriginalName().'_'.date('YmdHis').'.'.$file_3->getClientOriginalExtension();
      $f_name = $new_id.'_3'.date('YmdHis').'.'.$file_3->getClientOriginalExtension();
      $file_3->move(public_path().'/files_register/', $f_name);
            //$db = $f_name;
    }

    dd($req->all());

    $prefix_name= (trim($req->input('name_prefix')) == '') ? null : $req->input('name_prefix');
    $first_name= (trim($req->input('name_first')) == '') ? null : $req->input('name_first');
    $last_name= (trim($req->input('name_last')) == '') ? null : $req->input('name_last');
    $business_name= (trim($req->input('name_business')) == '') ? null : $req->input('name_business');
    $family_status= (trim($req->input('family_status')) == '') ? null : $req->input('family_status');
    
        //birth_day
    $birth_day= (trim($req->input('birth_day')) == '') ? null : $req->input('birth_day');

    $id_card= (trim($req->input('id_card')) == '') ? null : $req->input('id_card');
    $email= (trim($req->input('email')) == '') ? null : $req->input('email');
    $line_type = (trim($req->input('upline_type')) == '') ? null : $req->input('upline_type');
    $upline_id = (trim($req->input('upline_id')) == '') ? null : $req->input('upline_id');


    $house_no= (trim($req->input('house_no')) == '') ? null : $req->input('house_no');
    $house_name= (trim($req->input('house_name')) == '') ? null : $req->input('house_name');
    $moo= (trim($req->input('moo')) == '') ? null : $req->input('moo');
    $soi= (trim($req->input('soi')) == '') ? null : $req->input('soi');
    $road= (trim($req->input('road')) == '') ? null : $req->input('road');
    $district_sub= (trim($req->input('district_sub')) == '') ? null : $req->input('district_sub');
    $district= (trim($req->input('district')) == '') ? null : $req->input('district');
    $province= (trim($req->input('province')) == '') ? null : $req->input('province');
    $zipcode= (trim($req->input('zipcode')) == '') ? null : $req->input('zipcode');
        //$country= "Thailand";

    $benefit_name= (trim($req->input('benefit_name')) == '') ? null : $req->input('benefit_name');
    $benefit_relation= (trim($req->input('benefit_relation')) == '') ? null : $req->input('benefit_relation');
    $benefit_id= (trim($req->input('benefit_id')) == '') ? null : $req->input('benefit_id');
    $bank_no= (trim($req->input('bank_no')) == '') ? null : $req->input('bank_no');
    $bank_account= (trim($req->input('bank_account')) == '') ? null : $req->input('bank_account');
    $bank_type= (trim($req->input('bank_type')) == '') ? null : $req->input('bank_type');
    $bank_branch= (trim($req->input('bank_branch')) == '') ? null : $req->input('bank_branch');
    $bank_name= (trim($req->input('bank_name')) == '') ? null : $req->input('bank_name');
    $tel_home= (trim($req->input('tel_home')) == '') ? null : $req->input('tel_home');
    $tel_mobile= (trim($req->input('tel_mobile')) == '') ? null : $req->input('tel_mobile');

    $count = DB::table('customers')
    ->select('*')
    ->where('user_name','=',$username)
    ->count();

    if($count > 0){
     dd('username ซ้ำ');

   }else{
     $data_customer = [
      'user_name'=>$username,
      'password'=>$pass,

      'prefix_name'=>$prefix_name,
      'first_name'=>$first_name,
      'last_name'=>$last_name,
      'business_name'=>$business_name,
      'family_status'=>$family_status,
      'id_card'=>$id_card,
      'email'=>$email,
      'line_type'=>$line_type, 
      'upline_id'=>$upline_id,
      'birth_day'=>$birth_day];

      if(!empty($data_customer)){
       $id = DB::table('customers')->insertGetId($data_customer);

       if (!empty($id)) {
         $data_customer_detail = ['house_no'=>$house_no,
         'house_name'=>$house_name,
         'moo'=>$moo,
         'soi'=>$soi,
         'road'=>$road,
         'district_sub'=>$district_sub,
         'district'=>$district,
         'province'=>$province,
         'zipcode'=>$zipcode,
         'benefit_name'=>$benefit_name,
         'benefit_relation'=>$benefit_relation,
         'benefit_id_card'=>$benefit_id,
         'bank_no'=>$bank_no,
         'bank_account'=>$bank_account,
         'bank_type'=>$bank_type,
         'bank_branch'=>$bank_branch,
         'bank_name'=>$bank_name,
         'tel_home'=>$tel_home,
         'tel_mobile'=>$tel_mobile,
         'customer_id'=>$id];
         DB::table('customers_detail')->insert($data_customer_detail);
         return redirect('home');
       }else{ 
        dd('customers id null');

      }

    }else{
      dd('data_customer null');
    }



  }




}


}
