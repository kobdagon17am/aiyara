<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class DocsController extends Controller
{
	public function index()
	{
		$data = DB::table('register_files')
		->orderby('created_at')
    	        //->where('customer_id','=',Auth::guard('c_user')->user()->id)
		->where('customer_id','=',Auth::guard('c_user')->user()->id)
		->get();
		return view('frontend/docs',compact('data'));
	}

	public function docs_upload(Request $request){
		$file_1 = $request->file_1;
		$file_2 = $request->file_2;
		$file_3 = $request->file_3;

		if(!empty($file_1) || !empty($file_2) || !empty($file_3)){

			if(isset($file_1)){
            // $f_name = $file_1->getClientOriginalName().'_'.date('YmdHis').'.'.$file_1->getClientOriginalExtension();
				$url='local/public/files_register/1/'.date('Ym');
				
				$f_name = date('YmdHis').'_'.Auth::guard('c_user')->user()->id.'_1'.'.'.$file_1->getClientOriginalExtension();
				if($file_1->move($url,$f_name)){
					DB::table('register_files')
					->insert(['customer_id'=>Auth::guard('c_user')->user()->id,'type'=>'1','url'=>$url,'file'=>$f_name,'status'=>'W']);

				}
			}

			if(isset($file_2)){
            // $f_name = $file_2->getClientOriginalName().'_'.date('YmdHis').'.'.$file_2->getClientOriginalExtension();
				$url='local/public/files_register/2/'.date('Ym');
				$f_name =  date('YmdHis').'_'.Auth::guard('c_user')->user()->id.'_2'.'.'.$file_1->getClientOriginalExtension();
				if($file_2->move($url,$f_name)){
					DB::table('register_files')
					->insert(['customer_id'=>Auth::guard('c_user')->user()->id,'type'=>'2','url'=>$url,'file'=>$f_name,'status'=>'W']);

				}
			}

			if(isset($file_3)){
            // $f_name = $file_3->getClientOriginalName().'_'.date('YmdHis').'.'.$file_3->getClientOriginalExtension();
				$url='local/public/files_register/3/'.date('Ym');
				$f_name =  date('YmdHis').'_'.Auth::guard('c_user')->user()->id.'_2'.'.'.$file_1->getClientOriginalExtension();
				if($file_3->move($url,$f_name)){
					DB::table('register_files')
					->insert(['customer_id'=>Auth::guard('c_user')->user()->id,'type'=>'3','url'=>$url,'file'=>$f_name,'status'=>'W']);

				}
			}

			return redirect('docs')->withSuccess('Docs upload Success');

 
		}else{
			 return redirect('docs')->withError('Docs upload Fail');
		}
	}
}
