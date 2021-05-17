<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use DB;
use App\Models\Frontend\LineModel;
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
  		$id = Auth::guard('c_user')->user()->id;
  	}

  	$data = LineModel::line_all($id);
    return view('frontend/home',compact('data'));
  }

  public function search(Request $request){

    $data = LineModel::line_all($request->home_search_id);
    return view('frontend/home',compact('data'));
  }

  public function modal_tree(Request $request){
    $id = $request->id;

    $data = DB::table('customers')
    ->select('customers.*','dataset_package.dt_package','dataset_qualification.code_name','q_max.code_name as max_code_name','q_max.business_qualifications as max_q_name')
    ->leftjoin('dataset_package','dataset_package.id','=','customers.package_id')
    ->leftjoin('dataset_qualification', 'dataset_qualification.id', '=','customers.qualification_id')
    ->leftjoin('dataset_qualification as q_max', 'q_max.id', '=','customers.qualification_max_id')
    ->where('customers.id','=',$id)
    ->first();
    return view('frontend/modal/modal_tree',['data'=>$data]);
  }

  public function modal_add(Request $request){
    $id = $request->id;
    $type = $request->type;
    $data = DB::table('Customers')
    ->select('*')
    ->where('id','=',$id)
    ->limit(1)
    ->first();
    return view('frontend/modal/modal_add',['data'=>$data,'type'=>$type]);
  }

  public function home_type_tree(Request $request){

    if($request->id){
      $id = $request->id;
    }else{
      $id = Auth::guard('c_user')->user()->id;
    }

    $data = LineModel::line_all($id);
    return view('frontend/home_type_tree',compact('data'));
  }

  public function under_a(Request $request){
    $id = $request->id;

    $las_a_id = LineModel::under_a($id);

    $data = LineModel::line_all($las_a_id);

    return view('frontend/home',compact('data'));
  }

  public function under_b(Request $request){
    $id = $request->id;

    $las_a_id = LineModel::under_b($id);

    $data = LineModel::line_all($las_a_id);

    return view('frontend/home',compact('data'));
  }

  public function under_c(Request $request){
    $id = $request->id;

    $las_a_id = LineModel::under_c($id);

    $data = LineModel::line_all($las_a_id);

    return view('frontend/home',compact('data'));
  }

  public function home_check_customer_id(Request $request){

    $resule =LineModel::check_line($request->user_name);

    if($resule['status'] == 'success'){
      $data = array('status'=>'success','id'=>$resule['data']->id);
    }else{
     $data = array('status'=>'fail','data'=>$resule);
   }
    //$data = ['status'=>'fail'];
   return $data;
 }

}
