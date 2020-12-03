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

class RegisterController extends Controller
{


  public function __construct()
  {
    $this->middleware('customer');
  }

  public function index($id,$line_type){
    if(empty($id) || empty($line_type)){
      return redirect('home')->withError('กรุณาเลือกตำแหน่งที่ต้องการ Add User');

    }else{
    	$data = DB::table('Customers')
    	->select('*')
    	->where('id','=',$id)
    	->limit(1)
    	->first();

      return view('frontend/newregister',compact('data'));

    }

  }
  
  public function check_user(Request $request){
    $data =DB::table('Customers')
    ->where('user_name','=',$request->user_name)
    ->first();

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

        return redirect('register/30/B')->withInput()->withSuccess($data['massage']);

      }else{
        return redirect('register/30/B')->withInput()->withError($data['massage']);

      }
    }else{
      $introduce_id = DB::table('Customers')
      ->where('user_name','=',$req->introduce)
      ->first();

      if($introduce_id){
        //add data and introduce_id
        $data = Register::register($req,$introduce_id->id);

        if($data['status'] == 'success'){         
          return redirect('register/30/B')->withInput()->withSuccess($data['massage']);
        }else{
          return redirect('register/30/B')->withInput()->withError($data['massage']);
        }

      }else{
        return redirect('register/30/B')->withInput()->withError('ไม่มีข้อมูลรหัสผู้แนะนำในระบบ');
      }

    }

  }


}
