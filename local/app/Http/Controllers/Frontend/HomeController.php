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

}
