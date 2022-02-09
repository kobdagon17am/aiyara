<?php

namespace App\Http\Controllers\Frontend;
use App\Models\Frontend\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class DocsController extends Controller
{
  public function __construct()
  {
    $this->middleware('customer');
  }
	public function index()
	{
		$data = DB::table('register_files')
    ->select('register_files.*')
		->where('register_files.customer_id','=',Auth::guard('c_user')->user()->id)
    // ->leftjoin('dataset_regis_doc_status', 'dataset_regis_doc_status.id', '=', 'register_files.regis_doc_status')
    ->orderby('register_files.created_at','DESC')
		->get();


		$registeredDocs = $this->registeredDocs();

		return view('frontend/docs',compact('data', 'registeredDocs'));
	}

	public function docs_upload(Request $request){
		$file_1 = $request->file_1;
		$file_2 = $request->file_2;
		$file_3 = $request->file_3;
		$file_4 = $request->file_4;

    $business_location_id = Auth::guard('c_user')->user()->business_location_id;
    if(empty($business_location_id)){
      $business_location_id = 1;
    }

    $update_use = Customer::find(Auth::guard('c_user')->user()->id);

		if(!empty($file_1) || !empty($file_2) || !empty($file_3) || !empty($file_4)){

			if(isset($file_1)){
            // $f_name = $file_1->getClientOriginalName().'_'.date('YmdHis').'.'.$file_1->getClientOriginalExtension();
				$url='local/public/files_register/1/'.date('Ym');

				$f_name = date('YmdHis').'_'.Auth::guard('c_user')->user()->id.'_1'.'.'.$file_1->getClientOriginalExtension();
				if($file_1->move($url,$f_name)){
					DB::table('register_files')
					->insert(['customer_id'=>Auth::guard('c_user')->user()->id,'type'=>'1','url'=>$url,'file'=>$f_name,'business_location_id_fk'=>$business_location_id]);
          $update_use->regis_doc1_status = 0;
				}
			}

			if(isset($file_2)){
            // $f_name = $file_2->getClientOriginalName().'_'.date('YmdHis').'.'.$file_2->getClientOriginalExtension();
				$url='local/public/files_register/2/'.date('Ym');
				$f_name =  date('YmdHis').'_'.Auth::guard('c_user')->user()->id.'_2'.'.'.$file_2->getClientOriginalExtension();
				if($file_2->move($url,$f_name)){
					DB::table('register_files')
					->insert(['customer_id'=>Auth::guard('c_user')->user()->id,'type'=>'2','url'=>$url,'file'=>$f_name,'business_location_id_fk'=>$business_location_id]);
          $update_use->regis_doc2_status = 0;

				}
			}

			if(isset($file_3)){
            // $f_name = $file_3->getClientOriginalName().'_'.date('YmdHis').'.'.$file_3->getClientOriginalExtension();
				$url='local/public/files_register/3/'.date('Ym');
				$f_name =  date('YmdHis').'_'.Auth::guard('c_user')->user()->id.'_3'.'.'.$file_3->getClientOriginalExtension();
				if($file_3->move($url,$f_name)){
					DB::table('register_files')
					->insert(['customer_id'=>Auth::guard('c_user')->user()->id,'type'=>'3','url'=>$url,'file'=>$f_name,'business_location_id_fk'=>$business_location_id]);
          $update_use->regis_doc3_status = 0;

				}
			}

			if(isset($file_4)){
            // $f_name = $file_3->getClientOriginalName().'_'.date('YmdHis').'.'.$file_3->getClientOriginalExtension();
				$url='local/public/files_register/4/'.date('Ym');
				$f_name =  date('YmdHis').'_'.Auth::guard('c_user')->user()->id.'_4'.'.'.$file_4->getClientOriginalExtension();
				if($file_4->move($url,$f_name)){
					DB::table('register_files')
					->insert(['customer_id'=>Auth::guard('c_user')->user()->id,'type'=>'4','url'=>$url,'file'=>$f_name,'business_location_id_fk'=>$business_location_id]);
          $update_use->regis_doc4_status = 0;
				}
			}
      $update_use->save();
			return redirect('docs')->withSuccess('Docs upload Success');


		}else{
			return redirect('docs')->withError('Docs upload Fail');
		}
	}

	public function registeredDocs() {

		$registeredDocs = DB::table('customers')
				->select('regis_doc1_status', 'regis_doc2_status', 'regis_doc3_status', 'regis_doc4_status')
				->where('id', auth('c_user')->id())
				->first();

		return $registeredDocs;
	}
}
