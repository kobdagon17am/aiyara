<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use DB;
use App\Models\Frontend\LineModel;
use App\Models\Frontend\Product;
use Illuminate\Support\Facades\Hash;
use Auth;
//use App\Http\Controllers\Session;
class HomeController extends Controller
{


  public function __construct()
  {
    $this->middleware('customer');
  }

  public function index(Request $request){

  	if($request->id){
  		$id = $request->id;
  	}else{
  		$id = Auth::guard('c_user')->user()->user_name;
  	}

  	$data = LineModel::line_all($id);
    return view('frontend/home',compact('data'));
  }

  public function search(Request $request){
    $data = LineModel::line_all($request->home_search_id);
    return view('frontend/home',compact('data'));
  }

  public function modal_tree(Request $request){
    $user_name = $request->user_name;

    $data = DB::table('customers')
    ->select('customers.business_name','customers.date_order_first','customers.prefix_name','customers.first_name','customers.last_name','customers.user_name','customers.created_at','customers.date_mt_first','customers.pv_mt_active',
    'customers.pv_mt','customers.pv_mt','customers.bl_a','customers.bl_b','customers.bl_c','customers.pv_a','customers.pv_b','customers.pv_c',
    'customers.pv','dataset_package.dt_package','dataset_qualification.code_name','q_max.code_name as max_code_name',
    'q_max.business_qualifications as max_q_name','customers.team_active_a','customers.team_active_b','customers.team_active_c')
    ->leftjoin('dataset_package','dataset_package.id','=','customers.package_id')
    ->leftjoin('dataset_qualification', 'dataset_qualification.id', '=','customers.qualification_id')
    ->leftjoin('dataset_qualification as q_max', 'q_max.id', '=','customers.qualification_max_id')
    ->where('customers.user_name','=',$user_name)
    ->first();

    return view('frontend/modal/modal_tree',['data'=>$data]);
  }

  public function modal_add(Request $request){

    $user_name = $request->user_name;
    $type = $request->type;
    $data = DB::table('customers')
    ->select('*')
    ->where('user_name','=',$user_name)
    ->first();
    return view('frontend/modal/modal_add',['data'=>$data,'type'=>$type]);
  }

  public function up_step(Request $request){

    $user_name = $request->user_name;


    $data = LineModel::line_all($user_name);
    return view('frontend/home',compact('data'));
  }

  public function under_a(Request $request){


    $user_name = $request->user_name;
    $line_type = $request->line_type;
    //$las_a_id = LineModel::under_a($user_name);
    $data = LineModel::line_all($user_name,$line_type);



    return view('frontend/home',compact('data'));
  }



  public function under_b(Request $request){
    $user_name = $request->user_name;
    $line_type = $request->line_type;
    //$las_a_id = LineModel::under_a($user_name);
    $data = LineModel::line_all($user_name,$line_type);

    return view('frontend/home',compact('data'));
  }

  public function under_c(Request $request){
    $user_name = $request->user_name;
    $line_type = $request->line_type;
    //$las_a_id = LineModel::under_a($user_name);
    $data = LineModel::line_all($user_name,$line_type);

    return view('frontend/home',compact('data'));
  }

  public function home_check_customer_id(Request $request){

    $resule =LineModel::check_line($request->user_name);

    if($resule['status'] == 'success'){
      $data = array('status'=>'success','user_name'=>$resule['data']->user_name);
    }else{
     $data = array('status'=>'fail','data'=>$resule);
   }
    //$data = ['status'=>'fail'];
   return $data;
 }

}
